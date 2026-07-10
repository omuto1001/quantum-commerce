<?php
// database/migrations/..._create_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            $table->unsignedInteger('quantity');
            $table->decimal('price', 10, 2); // price at time of purchase, in case product price changes later

            // Each vendor manages the status of just their own portion of the order
            $table->enum('status', ['pending', 'confirmed', 'shipped', 'delivered'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};