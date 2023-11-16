<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_an_order_with_default_values(): void
    {
        $customer = $this->auth();

        $sa = Carbon::instance(fake()->dateTimeThisMonth())->toDateTimeString();

        $attributes = [
            'customer_id' => $customer->id,
            'pickup_id' => Place::factory()->create()->id,
            'dropoff_id' => Place::factory()->create()->id,
            'scheduled_at' => $sa,
        ];

        $title = fake()->sentence();
        $pickup_details = fake()->address();
        $is_odd_even = rand(0, 1) ? true : false;

        $post_data = array_merge($attributes, ['meta' => [
            'title' => $title,
            'pickup_details' => $pickup_details,
            'is_odd_even' => $is_odd_even,
        ]]);
        $this->postJson('/api/orders', $post_data)->assertCreated();

        $assert_data = array_merge($attributes, [
            'meta->title' => $title,
            'meta->pickup_details' => $pickup_details,
            'meta->is_odd_even' => $is_odd_even,
        ]);
        $this->assertDatabaseHas('orders', $assert_data);
        $this->assertNotNull(Order::first()->driver);
        $this->assertNotNull(Order::first()->approver);
    }
}
