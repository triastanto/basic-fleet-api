<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriverReview>
 */
class DriverReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'driver_id' => Driver::factory()->create()->id,
            'rate' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence,
        ];
    }
}
