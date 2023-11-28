<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Place;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Workflow\State;
use App\Services\EOSAPI;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_customer_can_create_an_order_with_default_values(): void
    {
        // authenticated user
        $customer = $this->auth();

        // customer's approver
        User::factory()->create(['meta->personnel_no' => 99999]);
        $this->mock(EOSAPI::class, function (MockInterface $mock) {
            $mock->shouldReceive('minManagerBoss')
                ->andReturn(["personnel_no" => 99999]);
        });

        // driver and vehicle
        Driver::factory()->create(
            ['vehicle_id' => Vehicle::factory()->even()->create()->id]
        );

        // Initial state 1 => new
        State::factory()->create();

        // required attributes for order
        $sa = Carbon::instance(fake()->dateTimeThisMonth())->toDateTimeString();
        $attributes = [
            'customer_id' => $customer->id,
            'pickup_id' => Place::factory()->create()->id,
            'dropoff_id' => Place::factory()->create()->id,
            'scheduled_at' => $sa,
        ];

        // order meta for additional information
        $title = fake()->sentence();
        $pickup_details = fake()->address();
        $is_odd_even = rand(0, 1) ? true : false;

        // act on the post order endpoint and assert the response
        $this->postJson(
            route('orders.store'),
            array_merge($attributes, [
                'meta' => [
                    'title' => $title,
                    'pickup_details' => $pickup_details,
                    'is_odd_even' => $is_odd_even,
                ]
            ])
        )->assertCreated();

        // additional data assertion
        $this->assertDatabaseHas(
            'orders',
            array_merge($attributes, [
                'meta->title' => $title,
                'meta->pickup_details' => $pickup_details,
                'meta->is_odd_even' => $is_odd_even,
            ])
        );

        $this->assertNotNull(Order::first()->driver_review);
        $this->assertNotNull(Order::first()->approver);
    }
}
