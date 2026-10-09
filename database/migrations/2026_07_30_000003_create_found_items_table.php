<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('found_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('item_name');
            $table->string('color')->nullable();
            $table->text('description');
            $table->date('date_found');
            $table->string('location_found');
            $table->string('storage_location')->default('Security Office / Central Admin');
            $table->string('image')->nullable();
            $table->string('status')->default('reported'); // reported, claimed, recovered, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('found_items');
    }
};
