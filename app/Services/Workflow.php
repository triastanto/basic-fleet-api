<?php

namespace App\Services;

use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use Illuminate\Database\Eloquent\Model;

class Workflow
{
    public function isValidTransition(State $from, State $to): bool
    {
        return Transition::where('from_id', $from->id)
            ->where('to_id', $to->id)
            ->exists();
    }

    public function performTransition(Model $entity, State $to): bool
    {
        // TODO: need to implement InvalidTransitionException
        if ($this->isValidTransition($entity->state, $to)) {
            $entity->state()->associate($to);
            return $entity->save();
        }
        return false;
    }

    public function getCurrentState(Model $entity): State
    {
        return $entity->state;
    }
}
