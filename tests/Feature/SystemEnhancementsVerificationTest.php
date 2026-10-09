<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemEnhancementsVerificationTest extends TestCase
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
            'name' => 'Dr. Administrator',
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone_number' => '+255712000001',
        ]);

        $this->staff = User::factory()->create([
            'name' => 'Officer Security',
            'role' => 'staff',
            'email_verified_at' => now(),
            'phone_number' => '+255712000002',
        ]);

        $this->student = User::factory()->create([
            'name' => 'Emmanuel Student',
            'role' => 'user',
            'email_verified_at' => now(),
            'phone_number' => '+255712000003',
            'registration_number' => 'SJUT/BAC/2026/088',
        ]);

        $this->category = Category::create([
            'category_name' => 'Electronics & Laptops',
            'icon' => 'fa-laptop',
        ]);
    }

    public function test_admin_security_center_displays_in_system_notification_logs()
    {
        Notification::create([
            'user_id' => $this->student->id,
            'title' => 'Ownership Claim Verified',
            'message' => 'Your claim has been verified by the Security Desk.',
            'type' => 'claim',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.security.index', ['tab' => 'notifications']));

        $response->assertStatus(200);
        $response->assertSee('In-System Notification Logs', false);
        $response->assertSee('In-System Notification Log Index', false);
        $response->assertSee('Ownership Claim Verified', false);
        $response->assertSee('In-System Alerts Dispatched', false);
    }

    public function test_workspace_renders_with_elevated_professional_terminology_and_replaces_dashboard()
    {
        // 1. Backward-compatibility redirect from /dashboard to /workspace
        $redirectResp = $this->actingAs($this->admin)->get('/dashboard');
        $redirectResp->assertRedirect('/workspace');

        // 2. Management / Admin Workspace
        $adminResp = $this->actingAs($this->admin)->get(route('workspace'));
        $adminResp->assertStatus(200);
        $adminResp->assertSee('Management Workspace & Technical Control Center', false);
        $adminResp->assertSee('SJUT Operational Governance', false);
        $adminResp->assertSee('Institutional Recovery Velocity', false);
        $adminResp->assertSee('My Workspace', false);

        // 3. Personal / User Workspace
        $userResp = $this->actingAs($this->student)->get(route('workspace'));
        $userResp->assertStatus(200);
        $userResp->assertSee('Personal Property Recovery Workspace', false);
        $userResp->assertSee('Institutional Recovery Operations', false);
        $userResp->assertSee('Personal Lost Property Repository', false);
        $userResp->assertSee('Campus Found Property Central Registry', false);
        $userResp->assertSee('My Workspace', false);

        // 4. Staff Portal Workspace
        $staffResp = $this->actingAs($this->staff)->get(route('staff.index'));
        $staffResp->assertStatus(200);
        $staffResp->assertSee('Campus Security Staff Operations Portal', false);
        $staffResp->assertSee('Intake Review Queue', false);
        $staffResp->assertSee('Central Storage Log', false);
        $staffResp->assertSee('My Workspace', false);
    }

    public function test_in_system_notifications_dispatch_and_can_be_marked_read()
    {
        $lostItem = LostItem::create([
            'user_id' => $this->student->id,
            'category_id' => $this->category->id,
            'item_name' => 'Scientific Calculator FX-991',
            'description' => 'Black Casio FX-991EX',
            'location_lost' => 'Lecture Hall 1',
            'date_lost' => now(),
            'approval_status' => 'pending_review',
        ]);

        // Staff approves lost item
        $this->actingAs($this->staff)->post(route('lost-items.approve', $lostItem->id));

        // Verify In-System Notification was generated for student
        $notification = Notification::where('user_id', $this->student->id)->latest()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('approval', $notification->type);
        $this->assertFalse($notification->is_read);

        // Student marks notification as read
        $readResp = $this->actingAs($this->student)->post(route('notifications.read', $notification->id));
        $readResp->assertRedirect();

        $notification->refresh();
        $this->assertTrue($notification->is_read);
        $this->assertNotNull($notification->read_at);
    }

    public function test_online_guide_page_renders_and_links_to_pdf_download()
    {
        $response = $this->actingAs($this->student)->get(route('guide.index'));

        $response->assertStatus(200);
        $response->assertSee('How the Recovery System Works', false);
        $response->assertSee('Download User Guide (PDF)', false);
    }
}
