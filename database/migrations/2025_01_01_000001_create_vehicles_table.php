<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->smallInteger('year');
            $table->string('color');
            $table->enum('transmission', ['manual', 'automatic']);
            $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid']);
            $table->integer('passenger_count')->default(5);
            $table->string('chassis_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->decimal('daily_rate', 12, 2);
            $table->decimal('weekly_rate', 12, 2)->nullable();
            $table->decimal('monthly_rate', 12, 2)->nullable();
            $table->decimal('seasonal_rate', 12, 2)->nullable();
            $table->enum('status', ['available', 'booked', 'rented', 'maintenance', 'unavailable'])->default('available');
            $table->string('photo')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('tax_expiry_date')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->integer('current_odometer')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('brand');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
