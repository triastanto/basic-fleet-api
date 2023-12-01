<?php

namespace App\Services;

use App\Enums\State;
use App\Enums\Transition;
use App\Models\Workflow\State as ModelState;
use App\Models\Workflow\Transition as ModelTransition;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Workflow
{
    private static Workflow $instance;
    protected State $initialState;

    public static function getInstance(State $initialState): Workflow
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($initialState);
        }
        return self::$instance;
    }

    public function __construct(State $initialState)
    {
        $this->setinitialState($initialState);
    }

    public function getInitialState(): State
    {
        return $this->initialState;
    }

    public function setinitialState(State $initialState): void
    {
        $this->initialState = $initialState;
    }

    public function getCurrentState(Model $model): State
    {
        return State::from($model->state->id);
    }

    public function applyTransition(Model $model, Transition $transition): void
    {
        $to = State::from($this->findTransition($transition)->to->id);
        $from = $this->getCurrentState($model);

        // TODO: need to implement InvalidTransitionException
        if ($this->isValidTransition($from, $to)) {
            $this->setCurrentState($model, $to);
        } else {
            throw new Exception("InvalidTransitionExeption");
        }
    }

    protected function isValidTransition(State $from, State $to): bool
    {
        return ModelTransition::where('from_id', $from->value)
            ->where('to_id', $to->value)
            ->exists();
    }

    protected function findTransition(Transition $transition): ModelTransition
    {
        return ModelTransition::find($transition->value);
    }

    protected function setCurrentState(Model $model, State $to)
    {
        $model->state()->associate(ModelState::find($to->value));
        $model->save();
    }
}
