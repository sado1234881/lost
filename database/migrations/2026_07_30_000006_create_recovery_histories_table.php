<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recovery_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->nullable()->constrained('claims')->onDelete('set null');
            $table->string('item_name');
            $table->string('category_name');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('finder_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('recovery_date');
            $table->string('verification_method')->default('QR Code Release Verification');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_histories');
    }
};
