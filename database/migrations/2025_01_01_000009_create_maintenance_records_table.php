<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->string('service_type');
            $table->date('service_date');
            $table->integer('odometer_at_service')->nullable();
            $table->string('workshop')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->date('next_service_date')->nullable();
            $table->integer('next_service_odometer')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('vehicle_id');
            $table->index('service_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_records');
    }
};
