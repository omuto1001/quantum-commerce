<?php
// database/migrations/..._create_vendors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // Links this vendor profile to a row in the users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('shop_name');
            $table->text('shop_description')->nullable();
            $table->string('shop_logo')->nullable();

            // % the platform keeps from every sale this vendor makes
            $table->decimal('commission_rate', 5, 2)->default(10.00);

            // Vendor's running earnings balance (used later for payouts)
            $table->decimal('wallet_balance', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};








