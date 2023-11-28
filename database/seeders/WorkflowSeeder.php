<?php

namespace Database\Seeders;

use App\Enums\State;
use App\Models\Workflow\State as WorkflowState;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkflowState::create([
            'id' => State::WAITING_APPROVAL, 'name' => 'Waiting Approval'
        ]);
        WorkflowState::create([
            'id' => State::APPROVED, 'name' => 'Approved'
        ]);
        WorkflowState::create([
            'id' => State::ON_THE_WAY, 'name' => 'On The Way'
        ]);
        WorkflowState::create([
            'id' => State::RETURNED, 'name' => 'Returned'
        ]);
    }
}
