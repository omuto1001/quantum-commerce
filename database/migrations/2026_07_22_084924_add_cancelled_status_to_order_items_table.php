<?php
// database/migrations/..._add_cancelled_status_to_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE order_items MODIFY status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE order_items MODIFY status ENUM('pending', 'confirmed', 'shipped', 'delivered') DEFAULT 'pending'");
    }
};