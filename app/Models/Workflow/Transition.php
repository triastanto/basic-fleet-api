<?php

namespace App\Models\Workflow;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transition extends Model
{
    use HasFactory;

    protected $table = 'workflow_transitions';


    public function from(): BelongsTo
    {
        return $this->belongsTo(State::class, 'from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(State::class, 'to_id');
    }
}
