<?php

namespace Tests\Feature;

use App\Enums\State;
use App\Enums\Transition;
use App\Models\Order;
use App\Services\Workflow;
use Tests\FeatureTestCase;
use Tests\TestCase;

class WorkflowTest extends FeatureTestCase
{
    protected Workflow $workflow;

    /** @test */
    public function it_can_apply_transition(): void
    {
        $this->workflow = $this->app->make(Workflow::class);
        $order = Order::factory()->initialState()->create();

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::WAITING_APPROVAL->value]
        );

        $this->workflow->applyTransition($order, Transition::APPROVE);

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::APPROVED->value]
        );
    }
}
