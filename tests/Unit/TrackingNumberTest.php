<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\TrackingNumber;
use Tests\TestCase;

class TrackingNumberTest extends TestCase
{
    /** @test */
    public function a_tracking_number_belongs_to_order(): void
    {
        $cost = TrackingNumber::factory()->create();

        $this->assertInstanceOf(Order::class, $cost->order);
    }
}
