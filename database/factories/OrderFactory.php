<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Place;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory()->create()->id,
            'pickup_id' => Place::factory()->create()->id,
            'dropoff_id' => Place::factory()->create()->id,
            'scheduled_at' => fake()->dateTimeThisMonth(),
            'meta' => json_encode([
                'title' => fake()->sentence(),
                'pickup_details' => fake()->address(),
                'is_odd_even' => rand(0, 1) ? true : false,
            ]),
            'driver_id' => Driver::factory()->create()->id,
            'approver_id' => User::factory()->create()->id,
            'status' => 'waiting_approval',
        ];
    }
}
