<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('item_type'); // 'found_item' or 'lost_item'
            $table->unsignedBigInteger('item_id');
            $table->text('proof_of_ownership');
            $table->date('claim_date');
            $table->string('claim_status')->default('pending'); // pending, approved, rejected, released
            $table->string('qr_code_token')->nullable()->unique();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
