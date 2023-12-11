<?php

namespace Tests\Feature;

use App\Enums\State;
use App\Models\Cost;
use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Order;
use App\Models\Place;
use App\Models\TrackingNumber;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Services\EOSAPI;
use App\Services\Workflow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
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

    private function createNewOrderAttributes(
        Carbon $scheduled_at,
        bool $is_odd_even
    ): array {
        $attributes = [
            'customer_id' => $this->auth()->id,
            'pickup_id' => Place::factory()->create()->id,
            'dropoff_id' => Place::factory()->create()->id,
            'scheduled_at' => $scheduled_at,
        ];

        $title = fake()->sentence();
        $pickup_details = fake()->address();

        return [
            'post' => array_merge($attributes, [
                'meta' => [
                    'title' => $title,
                    'pickup_details' => $pickup_details,
                    'is_odd_even' => $is_odd_even,
                ]
            ]),
            'assert' => array_merge($attributes, [
                'meta->title' => $title,
                'meta->pickup_details' => $pickup_details,
                'meta->is_odd_even' => $is_odd_even,
            ])
        ];
    }

    private function testCreateAnOrderWithOddEvenToggle($is_odd_even): Order
    {
        // mock customer's approver
        Customer::factory()->create(['meta->personnel_no' => 99999]);
        $this->mock(EOSAPI::class, function (MockInterface $mock) {
            $mock->shouldReceive('minManagerBoss')
                ->andReturn(["personnel_no" => 99999]);
        });

        // prepare drivers and even & odd plate number vehicles
        Driver::factory()
            ->count(2)
            ->state(new Sequence(
                ['vehicle_id' => Vehicle::factory()->odd()->create()->id],
                ['vehicle_id' => Vehicle::factory()->even()->create()->id],
            ))
            ->create();

        $scheduled_at = Carbon::instance(fake()->dateTimeThisMonth());

        // set the odd even toggle and extract $post and $assert variables
        extract($this->createNewOrderAttributes($scheduled_at, $is_odd_even));

        // assert 201 in the orders.store route
        $response = $this->postJson(route('orders.store'), $post);
        $response->assertCreated();

        // assert the record in the database
        $this->assertDatabaseHas('orders', $assert);

        $order = Order::find($response->json()['id']);

        // assert the driver review and approver is referenced
        $this->assertInstanceOf(DriverReview::class, $order->driver_review);
        $this->assertInstanceOf(Customer::class, $order->approver);

        return $order;
    }

    /** @test */
    public function a_customer_create_an_order_with_odd_even_toggle(): void
    {
        $order = $this->testCreateAnOrderWithOddEvenToggle(true);

        // make sure the day is matched with the odd even vehicle plate number selection
        if ($order->scheduled_at->day % 2 == 0) {
            $this->assertTrue($order->driver_review->driver->hasEven());
        } else {
            $this->assertTrue($order->driver_review->driver->hasOdd());
        }
    }

    /** @test */
    public function a_customer_create_an_order_without_odd_even_toggle(): void
    {
        $this->testCreateAnOrderWithOddEvenToggle(false);
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
            ['id' => $order->id, 'state_id' => State::APPROVED]
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
            ['id' => $order->id, 'state_id' => State::REJECTED]
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

        $order = Order::factory()->create([
            'state_id' => State::APPROVED->value
        ]);
        $tn = TrackingNumber::factory()->raw(['order_id' => $order->id]);

        $this->postJson(route('orders.start', ['order' => $order]), $tn)
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::ON_THE_WAY->value]
        );
        $this->assertDatabaseHas('tracking_numbers', $tn);
    }

    /** @test */
    public function a_driver_input_cost(): void
    {
        $this->auth();

        $order = Order::factory()->create([
            'state_id' => State::ON_THE_WAY->value
        ]);
        $costs = Cost::factory()->count(3)->make(['order_id' => $order->id]);

        $this->postJson(
            route('orders.costs', ['order' => $order]),
            ['costs' => $costs]
        )
            ->assertCreated();

        $costs->each(
            fn($cost) => $this->assertDatabaseHas('costs', $cost->toArray())
        );
    }

    /** @test */
    public function a_driver_ends_the_trip(): void
    {
        $this->auth();

        $order = Order::factory()->create(
            ['state_id' => State::ON_THE_WAY->value]
        );
        $tn = TrackingNumber::factory()->raw(['order_id' => $order->id]);

        $this->postJson(route('orders.end', ['order' => $order]), $tn)
            ->assertCreated();
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::RETURNED->value]
        );
        $this->assertDatabaseHas('tracking_numbers', $tn);
    }
}
