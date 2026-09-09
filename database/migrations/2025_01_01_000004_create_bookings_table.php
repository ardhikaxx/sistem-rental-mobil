<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('user_id')->constrained('users');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('actual_return_date')->nullable();
            $table->integer('rental_days');
            $table->decimal('daily_rate_snapshot', 12, 2);
            $table->decimal('rental_subtotal', 12, 2);
            $table->decimal('additional_fees', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('discount_reason', 12, 2)->default(0);
            $table->string('discount_note')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('dp_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('remaining_amount', 12, 2)->default(0);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('damage_fee', 12, 2)->default(0);
            $table->enum('status', ['pending_payment', 'booked', 'ready_pickup', 'rented', 'completed', 'cancelled'])->default('pending_payment');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('customer_id');
            $table->index('vehicle_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
