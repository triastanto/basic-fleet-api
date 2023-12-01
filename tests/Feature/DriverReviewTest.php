<?php

namespace Tests\Feature;

use App\Models\Order;
use Tests\FeatureTestCase;

class DriverReviewTest extends FeatureTestCase
{
    /** @test */
    public function a_customer_can_submit_driver_review(): void
    {
        $customer = $this->auth();

        $order = Order::factory()->initialState()->create(
            ['customer_id' => $customer->id]
        );
        $attributes = [
            'rate' => fake()->numberBetween(1, 5),
            'comment' => fake()->sentence
        ];

        $this->postJson(
            route(
                'drivers.review.store',
                ['driver' => $order->driver_review->driver]
            ),
            $attributes,
        )->assertCreated();

        $this->assertDatabaseHas('driver_reviews', $attributes);
    }
}
