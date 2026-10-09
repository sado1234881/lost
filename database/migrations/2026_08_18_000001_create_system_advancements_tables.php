<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add fields to lost_items
        Schema::table('lost_items', function (Blueprint $table) {
            $table->string('approval_status')->default('pending_review')->after('status'); // pending_review, approved, rejected, requires_correction
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->boolean('is_frozen')->default(false)->after('rejection_reason');
            $table->text('freeze_reason')->nullable()->after('is_frozen');
            $table->timestamp('frozen_at')->nullable()->after('freeze_reason');
            $table->foreignId('frozen_by')->nullable()->constrained('users')->onDelete('set null')->after('frozen_at');
            $table->unsignedBigInteger('matched_item_id')->nullable()->after('frozen_by');
        });

        // 2. Add fields to found_items
        Schema::table('found_items', function (Blueprint $table) {
            $table->string('approval_status')->default('pending_review')->after('status'); // pending_review, approved, rejected, requires_correction
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->boolean('is_frozen')->default(false)->after('rejection_reason');
            $table->text('freeze_reason')->nullable()->after('is_frozen');
            $table->timestamp('frozen_at')->nullable()->after('freeze_reason');
            $table->foreignId('frozen_by')->nullable()->constrained('users')->onDelete('set null')->after('frozen_at');
            $table->unsignedBigInteger('matched_item_id')->nullable()->after('frozen_by');
        });

        // 3. Enhance notifications (Alerts)
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('action_url')->nullable()->after('is_read');
            $table->string('entity_type')->nullable()->after('action_url'); // lost_item, found_item, claim, message
            $table->unsignedBigInteger('entity_id')->nullable()->after('entity_type');
        });

        // 4. Enhance messages table
        Schema::table('messages', function (Blueprint $table) {
            $table->string('report_type')->nullable()->after('message'); // lost_item or found_item
            $table->unsignedBigInteger('report_id')->nullable()->after('report_type');
        });

        // 5. Add action compliance status to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('action_required')->default(false)->after('phone_number');
            $table->text('action_required_message')->nullable()->after('action_required');
            $table->string('action_required_url')->nullable()->after('action_required_message');
        });

        // 6. Create SMS Delivery Logs table
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('phone_number');
            $table->text('message');
            $table->string('status')->default('pending'); // pending, sent, delivered, failed
            $table->text('error_message')->nullable();
            $table->timestamps();
        });

        // 7. Create Audit Logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action'); // report_approved, report_rejected, item_frozen, item_unfrozen, claim_approved, claim_released, user_updated, status_changed
            $table->string('target_type')->nullable(); // LostItem, FoundItem, Claim, User
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        // 8. Create Case Reviews table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->nullable()->constrained('claims')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('item_type'); // lost_item, found_item
            $table->unsignedBigInteger('item_id');
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5
            $table->boolean('returned_successfully')->default(true);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('sms_logs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['action_required', 'action_required_message', 'action_required_url']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['report_type', 'report_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['action_url', 'entity_type', 'entity_id']);
        });

        Schema::table('found_items', function (Blueprint $table) {
            $table->dropForeign(['frozen_by']);
            $table->dropColumn(['approval_status', 'rejection_reason', 'is_frozen', 'freeze_reason', 'frozen_at', 'frozen_by', 'matched_item_id']);
        });

        Schema::table('lost_items', function (Blueprint $table) {
            $table->dropForeign(['frozen_by']);
            $table->dropColumn(['approval_status', 'rejection_reason', 'is_frozen', 'freeze_reason', 'frozen_at', 'frozen_by', 'matched_item_id']);
        });
    }
};
