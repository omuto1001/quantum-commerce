<?php
// database/migrations/..._create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Links back to the specific order item that makes this review
            // legitimate - proof the customer actually received this product
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');

            $table->unsignedTinyInteger('rating'); // 1 to 5
            $table->text('comment')->nullable();

            $table->timestamps();

            // A customer can only review a specific delivered item once
            $table->unique('order_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};