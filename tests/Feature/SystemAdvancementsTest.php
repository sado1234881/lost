<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\LostItem;
use App\Models\FoundItem;
use App\Models\Claim;
use App\Models\Review;
use App\Models\AuditLog;
use App\Models\SmsLog;
use App\Models\Notification;
use App\Services\MatchEngineService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemAdvancementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $student;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone_number' => '+255712345678',
        ]);

        $this->staff = User::factory()->create([
            'role' => 'staff',
            'email_verified_at' => now(),
            'phone_number' => '+255712345679',
        ]);

        $this->student = User::factory()->create([
            'role' => 'user', // maps to student
            'email_verified_at' => now(),
            'phone_number' => '+255712345680',
            'registration_number' => 'SJUT/BAC/2026/001',
        ]);

        $this->category = Category::create([
            'category_name' => 'Electronics & Laptops',
            'icon' => 'fa-laptop',
        ]);
    }

    public function test_report_submission_starts_with_pending_approval_status()
    {
        $response = $this->actingAs($this->student)->post(route('lost-items.store'), [
            'item_name' => 'MacBook Pro 14 Inch',
            'category_id' => $this->category->id,
            'description' => 'Space Grey Apple M2 laptop with SJUT sticker',
            'location_lost' => 'Main Library 2nd Floor',
            'date_lost' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lost_items', [
            'item_name' => 'MacBook Pro 14 Inch',
            'approval_status' => 'pending_review',
            'user_id' => $this->student->id,
        ]);
    }

    public function test_staff_can_approve_pending_lost_report_and_trigger_sms_and_alert()
    {
        $lostItem = LostItem::create([
            'user_id' => $this->student->id,
            'category_id' => $this->category->id,
            'item_name' => 'HP EliteBook Laptop',
            'description' => 'Silver laptop in black sleeve',
            'location_lost' => 'Computer Lab A',
            'date_lost' => now(),
            'approval_status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->staff)->post(route('lost-items.approve', $lostItem->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('lost_items', [
            'id' => $lostItem->id,
            'approval_status' => 'approved',
        ]);

        // Verify SMS log and Alert creation
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->student->id,
            'type' => 'approval',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'report_approved',
            'target_id' => $lostItem->id,
        ]);
    }

    public function test_admin_can_reject_report_with_reason()
    {
        $lostItem = LostItem::create([
            'user_id' => $this->student->id,
            'category_id' => $this->category->id,
            'item_name' => 'Vague Item',
            'description' => 'Just something',
            'location_lost' => 'Campus',
            'date_lost' => now(),
            'approval_status' => 'pending_review',
        ]);

        $response = $this->actingAs($this->admin)->post(route('lost-items.reject', $lostItem->id), [
            'rejection_reason' => 'Vague description. Please re-submit with specific identifiers.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lost_items', [
            'id' => $lostItem->id,
            'approval_status' => 'rejected',
            'rejection_reason' => 'Vague description. Please re-submit with specific identifiers.',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'report_rejected',
            'target_id' => $lostItem->id,
        ]);
    }

    public function test_admin_can_freeze_and_unfreeze_item_records()
    {
        $foundItem = FoundItem::create([
            'user_id' => $this->staff->id,
            'category_id' => $this->category->id,
            'item_name' => 'Rolex Watch',
            'description' => 'Gold wristwatch',
            'location_found' => 'Administration Block',
            'storage_location' => 'Vault A-1',
            'date_found' => now(),
            'approval_status' => 'approved',
            'status' => 'reported',
        ]);

        // Freeze item
        $freezeResp = $this->actingAs($this->admin)->post(route('found-items.freeze', $foundItem->id), [
            'freeze_reason' => 'Ownership dispute under campus security investigation',
        ]);

        $freezeResp->assertRedirect();
        $this->assertDatabaseHas('found_items', [
            'id' => $foundItem->id,
            'is_frozen' => true,
            'freeze_reason' => 'Ownership dispute under campus security investigation',
        ]);

        // Non-admin student attempts to delete frozen item -> 403 Forbidden
        $delResp = $this->actingAs($this->student)->delete(route('found-items.destroy', $foundItem->id));
        $delResp->assertStatus(403);

        // Admin unfreezes item
        $unfreezeResp = $this->actingAs($this->admin)->post(route('found-items.unfreeze', $foundItem->id));
        $unfreezeResp->assertRedirect();
        $this->assertDatabaseHas('found_items', [
            'id' => $foundItem->id,
            'is_frozen' => false,
        ]);
    }

    public function test_automatic_matching_engine_links_lost_and_found_items()
    {
        $foundItem = FoundItem::create([
            'user_id' => $this->staff->id,
            'category_id' => $this->category->id,
            'item_name' => 'Dell XPS 15 Laptop',
            'description' => 'Silver laptop with Intel i7 sticker found near Library Hall',
            'location_found' => 'Main Library Hall',
            'storage_location' => 'Cabinet B',
            'date_found' => now(),
            'approval_status' => 'approved',
            'status' => 'reported',
        ]);

        // Student submits matching lost report
        $this->actingAs($this->student)->post(route('lost-items.store'), [
            'item_name' => 'Dell XPS 15 Laptop',
            'category_id' => $this->category->id,
            'description' => 'Silver laptop lost near Library Hall',
            'location_lost' => 'Main Library Hall',
            'date_lost' => now()->format('Y-m-d'),
        ]);

        $lostItem = LostItem::where('item_name', 'Dell XPS 15 Laptop')->first();
        $this->assertNotNull($lostItem);

        // Approve lost report to trigger matching engine
        $this->actingAs($this->staff)->post(route('lost-items.approve', $lostItem->id));

        $lostItem->refresh();
        $matches = MatchEngineService::findMatchesForLostItem($lostItem);
        $this->assertTrue($matches->contains('id', $foundItem->id));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->student->id,
            'type' => 'match',
        ]);
    }

    public function test_claim_verification_and_handover_flow_with_feedback_review()
    {
        $foundItem = FoundItem::create([
            'user_id' => $this->staff->id,
            'category_id' => $this->category->id,
            'item_name' => 'Sony Noise Cancelling Headphones',
            'description' => 'Black WH-1000XM4 in case',
            'location_found' => 'Cafeteria',
            'storage_location' => 'Desk 3',
            'date_found' => now(),
            'approval_status' => 'approved',
            'status' => 'reported',
        ]);

        // Student submits ownership claim
        $claimResp = $this->actingAs($this->student)->post(route('claims.store'), [
            'item_type' => 'found_item',
            'item_id' => $foundItem->id,
            'proof_of_ownership' => 'Serial number S/N-998811 engraved on left earcup',
        ]);
        $claimResp->assertRedirect();

        $claim = Claim::where('item_id', $foundItem->id)->first();
        $this->assertNotNull($claim);
        $this->assertEquals('pending', $claim->claim_status);

        // Staff approves claim and generates QR
        $approveResp = $this->actingAs($this->staff)->patch(route('claims.status', $claim->id), [
            'claim_status' => 'approved',
            'admin_notes' => 'Serial number matches item details in vault.',
        ]);
        $approveResp->assertRedirect();
        $claim->refresh();
        $this->assertEquals('approved', $claim->claim_status);
        $this->assertNotNull($claim->qr_code_token);

        // Staff scans QR code / verifies handover at Security Desk
        $handoverResp = $this->actingAs($this->staff)->post(route('admin.verify-release'), [
            'qr_code_token' => $claim->qr_code_token,
        ]);
        $handoverResp->assertRedirect();

        $claim->refresh();
        $foundItem->refresh();
        $this->assertEquals('released', $claim->claim_status);
        $this->assertEquals('recovered', $foundItem->status);

        // Student submits post-release review
        $reviewResp = $this->actingAs($this->student)->post(route('reviews.store'), [
            'claim_id' => $claim->id,
            'rating' => 5,
            'returned_successfully' => 1,
            'comments' => 'Excellent service by SJUT Security team! Item returned intact.',
        ]);
        $reviewResp->assertRedirect();

        $this->assertDatabaseHas('reviews', [
            'claim_id' => $claim->id,
            'rating' => 5,
            'returned_successfully' => true,
        ]);

        $claim->refresh();
        $this->assertEquals('closed', $claim->claim_status);
    }
}
