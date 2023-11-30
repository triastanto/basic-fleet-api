<?php

namespace App\Traits;

use App\Models\Workflow\State;
use App\Services\Workflow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasWorkflow
{
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function getInitialState(): State
    {
        $workflow = Workflow::getInstance();
        return $workflow->getInitialState();
    }

    public function isValidTransition(State $to): bool
    {
        $workflow = Workflow::getInstance();
        return $workflow->isValidTransition($this->state, $to);
    }

    public function performTransition(State $to): void
    {
        $workflow = Workflow::getInstance();
        $workflow->performTransition($this, $to);
    }

    public function getCurrentState(): State
    {
        $workflow = Workflow::getInstance();
        return $workflow->getCurrentState($this);
    }
}
