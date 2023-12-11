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
            $table->foreignId('customer_id')->references('id')->on('customers');
            $table->foreignId('pickup_id')->references('id')->on('places');
            $table->foreignId('dropoff_id')->references('id')->on('places');
            $table->timestamp('scheduled_at');
            $table->jsonb('meta');
            $table->foreignId('driver_review_id')->references('id')->on('driver_reviews');
            $table->foreignId('approver_id')->references('id')->on('customers');
            $table->foreignId('state_id')->references('id')->on('workflow_states');
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
