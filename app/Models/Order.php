<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'immutable_datetime',
        'meta' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'pickup_id');
    }

    public function dropoff(): BelongsTo
    {
        return $this->belongsTo(Place::class, 'dropoff_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
