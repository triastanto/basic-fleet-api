<?php

namespace Database\Factories\Workflow;

use App\Models\Workflow\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workflow\Transition>
 */
class TransitionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'start',
            'from_id' => State::factory()->create(['name' => 'New (Transition Factory)'])->id,
            'to_id' => State::factory()->create(['name' => 'In Progress (Transition Factory)'])->id,
        ];
    }
}
