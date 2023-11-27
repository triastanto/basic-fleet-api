<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_can_submit_driver_review(): void
    {
        $customer = $this->auth();

        $order = Order::factory()->create(['customer_id' => $customer->id]);
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
