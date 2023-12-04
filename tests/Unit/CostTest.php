<?php

namespace Tests\Unit;

use App\Models\Cost;
use App\Models\Order;
use Tests\TestCase;

class CostTest extends TestCase
{
    /** @test */
    public function a_cost_belongs_to_order(): void
    {
        $cost = Cost::factory()->create();

        $this->assertInstanceOf(Order::class, $cost->order);
    }
}
