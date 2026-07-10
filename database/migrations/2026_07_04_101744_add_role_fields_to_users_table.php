<?php
// database/migrations/..._add_role_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Only vendors/riders actually need admin approval; customers
            // and admins default to 'approved' so they can use the system
            // right after registering
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                  ->default('approved')
                  ->after('email');

            // Extra profile fields every role needs
            $table->string('phone')->nullable()->after('approval_status');
            $table->string('address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'phone', 'address']);
        });
    }
};