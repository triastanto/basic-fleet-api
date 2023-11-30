<?php

namespace Tests\Unit;

use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use App\Services\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_validate_transition(): void
    {
        // define transitions from 1 => new to 2 => in progress
        $transition = Transition::factory()->create();
        $from = $transition->from;
        $to = $transition->to;
        $workflow = new Workflow(State::first());

        $this->assertTrue($workflow->isValidTransition($from, $to));
        $this->assertFalse($workflow->isValidTransition($to, $from));
    }

    /** @test */
    public function a_from_belongs_to_state(): void
    {
        $transition = Transition::factory()->create();

        $this->assertInstanceOf(State::class, $transition->from);
    }

    /** @test */
    public function a_to_belongs_to_state(): void
    {
        $transition = Transition::factory()->create();

        $this->assertInstanceOf(State::class, $transition->to);
    }
}
