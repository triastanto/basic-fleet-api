<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_order_belongs_to_customer(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(User::class, $order->customer);
    }

    /** @test */
    public function an_order_belongs_to_pickup(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Place::class, $order->pickup);
    }

    /** @test */
    public function an_order_belongs_to_dropoff(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Place::class, $order->dropoff);
    }

    /** @test */
    public function an_order_belongs_to_driver(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(Driver::class, $order->driver);
    }

    /** @test */
    public function an_order_has_approver(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(User::class, $order->approver);
    }
}
