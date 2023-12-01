<?php

namespace Tests\Unit;

use App\Models\DriverReview;
use App\Models\Order;
use App\Models\Place;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
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
    public function an_order_belongs_to_driver_review(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(DriverReview::class, $order->driver_review);
    }

    /** @test */
    public function an_order_has_approver(): void
    {
        $order = Order::factory()->create();

        $this->assertInstanceOf(User::class, $order->approver);
    }
}
