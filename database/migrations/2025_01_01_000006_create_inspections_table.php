<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('type', ['check_in', 'check_out']);
            $table->datetime('inspection_date');
            $table->integer('odometer');
            $table->enum('fuel_level', ['empty', 'quarter', 'half', 'three_quarter', 'full']);
            $table->text('exterior_condition')->nullable();
            $table->text('interior_condition')->nullable();
            $table->text('equipment_condition')->nullable();
            $table->text('previous_damage_notes')->nullable();
            $table->text('new_damage_notes')->nullable();
            $table->text('officer_notes')->nullable();
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('damage_fee', 12, 2)->default(0);
            $table->timestamps();

            $table->index('booking_id');
            $table->index('vehicle_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
