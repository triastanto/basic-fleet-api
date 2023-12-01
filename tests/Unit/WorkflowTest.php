<?php

namespace Tests\Unit;

use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
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
