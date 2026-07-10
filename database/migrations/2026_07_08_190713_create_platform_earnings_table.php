<?php
// database/migrations/..._create_platform_earnings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_earnings', function (Blueprint $table) {
            $table->id();

            // Links back to the specific order item this commission came from,
            // so admin can trace every commission to its source sale
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            $table->decimal('commission_amount', 12, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_earnings');
    }
};