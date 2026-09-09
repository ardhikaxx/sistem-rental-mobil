<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('identity_type')->nullable();
            $table->string('sim_number')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->enum('verification_status', ['unverified', 'verified', 'problem'])->default('unverified');
            $table->text('verification_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
