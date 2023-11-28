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

    public function isValidTransition(State $to): bool
    {
        $workflow = new Workflow();

        return $workflow->isValidTransition($this->state, $to);
    }

    public function performTransition(State $to): void
    {
        $workflow = new Workflow();
        $workflow->performTransition($this, $to);
    }

    public function getCurrentState(): State
    {
        $workflow = new Workflow();

        return $workflow->getCurrentState($this);
    }
}
