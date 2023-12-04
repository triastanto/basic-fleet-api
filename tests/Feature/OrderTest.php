<?php

namespace Tests\Feature;

use App\Enums\State as EnumsState;
use App\Models\Cost;
use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use App\Models\Place;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\EOSAPI;
use App\Services\Workflow;
use Carbon\Carbon;
use Mockery\MockInterface;
use Tests\FeatureTestCase;

class OrderTest extends FeatureTestCase
{
    protected Workflow $workflow;

    public function setUp(): void
    {
        parent::setUp();
        $this->workflow = $this->app->make(Workflow::class);
    }

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

    /** @test */
    public function an_approver_approve_order(): void
    {
        $approver = $this->auth();
        $order = Order::factory()->initialState()->create([
            'approver_id' => $approver->id,
        ]);

        $this->postJson(route('orders.approve', ['order' => $order]))
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => EnumsState::APPROVED]
        );
    }

    /** @test */
    public function an_approver_reject_order(): void
    {
        $approver = $this->auth();
        $order = Order::factory()->initialState()->create([
            'approver_id' => $approver->id,
        ]);

        $this->postJson(route('orders.reject', ['order' => $order]))
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => EnumsState::REJECTED]
        );
    }

    /** @test */
    public function a_customer_replaces_driver(): void
    {
        $customer = $this->auth();
        $order = Order::factory()->initialState()->create([
            'customer_id' => $customer->id,
        ]);
        $driver = $order->driver_review->driver;

        $this->postJson(
            route('orders.driver', ['order' => $order]),
            ['driver' => $driver]
        )
            ->assertCreated();

        $this->assertDatabaseHas(
            'driver_reviews',
            ['id' => $order->driver_review->id, 'driver_id' => $driver->id]
        );
    }

    /** @test */
    public function a_driver_starts_trip(): void
    {
        // TODO: implement guard for driver
        $this->auth();

        $driver_review = DriverReview::factory()->create();
        $order = Order::factory()->create([
            'driver_review_id' => $driver_review->id,
            'state_id' => EnumsState::APPROVED->value
        ]);

        // TODO: add image, initial_odo, latitude, longitude
        $this->postJson(route('orders.start', ['order' => $order]))
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => EnumsState::ON_THE_WAY->value]
        );
    }

    /** @test */
    public function a_driver_can_input_cost(): void
    {
        $this->auth();

        $driver_review = DriverReview::factory()->create();
        $order = Order::factory()->create([
            'driver_review_id' => $driver_review->id,
            'state_id' => EnumsState::ON_THE_WAY->value
        ]);
        $costs = Cost::factory()->count(3)->make(['order_id' => $order->id]);

        $this->postJson(
            route('orders.costs', ['order' => $order]),
            ['costs' => $costs]
        )
            ->assertCreated();

        $costs->each(
            fn ($cost) => $this->assertDatabaseHas('costs', $cost->toArray())
        );
    }
}
