<?php
// database/migrations/..._create_riders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('vehicle_type'); // Motorcycle, Bicycle, Car
            $table->string('license_plate')->nullable();
            $table->string('national_id_number')->nullable();
            $table->string('id_document')->nullable();

            // Whether rider is currently free to take deliveries
            $table->boolean('is_available')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};