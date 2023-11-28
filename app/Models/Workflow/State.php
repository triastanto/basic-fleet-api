<?php

namespace App\Models\Workflow;

use App\Enums\State as EnumsState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'workflow_states';

    protected $guarded = [];

    public static function waitingApproval(): static
    {
        return static::find(EnumsState::WAITING_APPROVAL);
    }

    public static function approved(): static
    {
        return static::find(EnumsState::APPROVED);
    }

    public static function onTheWay(): static
    {
        return static::find(EnumsState::ON_THE_WAY);
    }

    public static function returned(): static
    {
        return static::find(EnumsState::RETURNED);
    }

    public static function rejected(): static
    {
        return static::find(EnumsState::REJECTED);
    }
}
