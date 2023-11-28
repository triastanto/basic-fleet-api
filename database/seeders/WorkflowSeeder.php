<?php

namespace Database\Seeders;

use App\Enums\State as EnumsState;
use App\Enums\Transition as EnumsTransition;
use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create(['id' => EnumsState::WAITING_APPROVAL, 'name' => 'Waiting Approval']);
        State::create(['id' => EnumsState::APPROVED, 'name' => 'Approved']);
        State::create(['id' => EnumsState::ON_THE_WAY, 'name' => 'On The Way']);
        State::create(['id' => EnumsState::RETURNED, 'name' => 'Returned']);
        State::create(['id' => EnumsState::REJECTED, 'name' => 'Rejected']);

        Transition::create([
            'id' => EnumsTransition::APPROVE,
            'name' => "Approve",
            'from_id' => EnumsState::WAITING_APPROVAL,
            'to_id' => EnumsState::APPROVED
        ]);

        Transition::create([
            'id' => EnumsTransition::DRIVE_TO_DEST,
            'name' => "Drive to Destination",
            'from_id' => EnumsState::APPROVED,
            'to_id' => EnumsState::ON_THE_WAY
        ]);

        Transition::create([
            'id' => EnumsTransition::END_TRIP,
            'name' => "End Trip",
            'from_id' => EnumsState::ON_THE_WAY,
            'to_id' => EnumsState::RETURNED
        ]);

        Transition::create([
            'id' => EnumsTransition::REJECT,
            'name' => "Reject",
            'from_id' => EnumsState::WAITING_APPROVAL,
            'to_id' => EnumsState::REJECTED
        ]);
    }
}
