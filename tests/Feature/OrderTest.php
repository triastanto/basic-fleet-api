<?php

namespace Tests\Feature;

use App\Enums\State as EnumsState;
use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use App\Models\Place;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Workflow\State;
use App\Services\EOSAPI;
use App\Services\Workflow;
use Carbon\Carbon;
use Database\Seeders\WorkflowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(WorkflowSeeder::class);
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
        $order = Order::factory()->create([
            'approver_id' => $approver->id,
            'state_id' => Workflow::getInstance()->getInitialState()->id,
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
        $order = Order::factory()->create([
            'approver_id' => $approver->id,
            'state_id' => Workflow::getInstance()->getInitialState()->id,
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
        $order = Order::factory()->create(['customer_id' => $customer->id]);
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

        $driver = Driver::factory()->create();
        $driver_review = DriverReview::factory()->create(['driver_id' => $driver->id]);
        $order = Order::factory()->create([
            'driver_review_id' => $driver_review->id,
            'state_id' => EnumsState::APPROVED
        ]);

        // TODO: add image, initial_odo, latitude, longitude
        $this->postJson(route('orders.start', ['order' => $order]))
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => EnumsState::ON_THE_WAY]
        );
    }
}
