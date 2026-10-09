<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique(); // e.g. SJUT-LF-2026-000125
            $table->string('qr_token')->unique(); // e.g. QRTAG-A1B2C3D4E5F6
            $table->string('item_type'); // found_item or lost_item
            $table->unsignedBigInteger('item_id');
            $table->string('status')->default('active'); // active, inactive, replaced, recovered
            $table->foreignId('generated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedInteger('scans_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
