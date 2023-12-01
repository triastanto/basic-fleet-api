<?php

namespace Tests\Feature;

use App\Enums\State;
use App\Enums\Transition;
use App\Models\Order;
use App\Services\Workflow;
use Exception;
use Tests\FeatureTestCase;
use Tests\TestCase;

class WorkflowTest extends FeatureTestCase
{
    protected Workflow $workflow;

    public function setUp(): void
    {
        parent::setUp();
        $this->workflow = $this->app->make(Workflow::class);
    }

    /** @test */
    public function it_cant_apply_invalid_transition(): void
    {
        /** @var \App\Models\Order $order */
        $order = Order::factory()->initialState()->create();

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::WAITING_APPROVAL->value]
        );
        $this->assertSame($order->getInitialState(), $this->workflow->getInitialState());
        $this->assertSame($order->getCurrentState(), State::from($order->state->id));

        $this->expectException(Exception::class);
        $order->applyTransition(Transition::DRIVE_TO_DEST);
    }

    /** @test */
    public function it_can_apply_transition(): void
    {
        /** @var \App\Models\Order $order */
        $order = Order::factory()->initialState()->create();

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::WAITING_APPROVAL->value]
        );
        $this->assertSame($order->getInitialState(), $this->workflow->getInitialState());
        $this->assertSame($order->getCurrentState(), State::from($order->state->id));

        $this->workflow->applyTransition($order, Transition::APPROVE);
        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::APPROVED->value]
        );
    }
}
