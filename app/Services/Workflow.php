<?php

namespace App\Services;

use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Workflow
{
    private static Workflow $instance;
    protected State $initialState;

    public static function getInstance(): Workflow
    {
        if (!isset(self::$instance)) {
            self::$instance = new static(State::waitingApproval());
        }
        return self::$instance;
    }

    public function __construct(State $initialState)
    {
        $this->initialState = $initialState;
    }

    public function getInitialState(): State
    {
        return $this->initialState;
    }

    public function isValidTransition(State $from, State $to): bool
    {
        return Transition::where('from_id', $from->id)
            ->where('to_id', $to->id)
            ->exists();
    }

    // TODO: need to implement applyTransition method
    public function performTransition(Model $entity, State $to): void
    {
        if ($this->isValidTransition($entity->state, $to)) {
            $entity->state()->associate($to);
            $entity->save();
        } else {
            // TODO: need to implement InvalidTransitionException
            throw new Exception("InvalidTransitionException");
        }

    }

    public function getCurrentState(Model $entity): State
    {
        return $entity->state;
    }
}
