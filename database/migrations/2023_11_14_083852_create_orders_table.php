<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('users');
            $table->foreignId('pickup_id')->references('id')->on('places');
            $table->foreignId('dropoff_id')->references('id')->on('places');
            $table->timestamp('scheduled_at');
            $table->jsonb('meta');
            $table->foreignId('driver_id')->references('id')->on('drivers');
            $table->foreignId('approver_id')->references('id')->on('users');
            $table->enum('status', ['waiting_approval'])->default('waiting_approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
