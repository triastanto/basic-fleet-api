<?php

namespace App\Traits;

use App\Enums\State;
use App\Enums\Transition;
use App\Models\Workflow\State as ModelState;
use App\Services\Workflow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasWorkflow
{
    public function state(): BelongsTo
    {
        return $this->belongsTo(ModelState::class);
    }

    public function getInitialState(): State
    {
        $workflow = app()->make(Workflow::class);
        return $workflow->getInitialState();
    }

    public function applyTransition(Transition $transition): void
    {
        $workflow = app()->make(Workflow::class);
        $workflow->applyTransition($this, $transition);
    }

    public function getCurrentState(): State
    {
        $workflow = app()->make(Workflow::class);
        return $workflow->getCurrentState($this);
    }
}
