<?php
// database/migrations/..._create_payouts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');

            $table->decimal('amount', 12, 2);
            $table->enum('status', ['requested', 'approved', 'rejected', 'paid'])->default('requested');
            $table->text('admin_note')->nullable(); // optional reason if rejected

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};