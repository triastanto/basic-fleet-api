<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrackingNumber>
 */
class TrackingNumberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $latitude = fake()->regexify('[-\+]?[0-9]{1,2}\.[0-9]{0,5}');
        $longitude = (float) fake()->regexify('[-\+]?[0-9]{1,2}\.[0-9]{0,5}') * 2;

        return [
            'name' => fake()->sentence,
            'order_id' => Order::factory()->create()->id,
            'odo_image' => fake()->imageUrl(640, 480, 'vehicle', true),
            'odo' => fake()->numberBetween(10000, 200000),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
}
