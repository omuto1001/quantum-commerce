<?php
// database/migrations/..._create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // The customer who placed this order
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->decimal('total_amount', 12, 2);
            $table->string('delivery_address');

            // Overall payment status for the whole order
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};