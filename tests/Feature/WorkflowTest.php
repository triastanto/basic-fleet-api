<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_simple_workflow(): void
    {
        // define transitions from 1 => new to 2 => in progress
        Transition::factory()->create();

        // order entity with initial state 1 => new defined above
        // and then assert the initial state
        $order = Order::factory()->create(['state_id' => 1]);
        $this->assertEquals(State::first(), $order->getCurrentState());

        // test a valid state destination
        $this->assertTrue($order->isValidTransition(State::find(2)));

        // perform a valid transition and assert the current state
        $order->performTransition(State::find(2));
        $this->assertEquals(State::find(2), $order->getCurrentState());

        // test a invalid state destination
        $this->assertFalse($order->isValidTransition(State::find(2)));
    }
}
