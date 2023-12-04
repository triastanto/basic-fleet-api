<?php

namespace App\Models;

use App\Traits\HasWorkflow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    use HasWorkflow;

    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'immutable_datetime',
        'meta' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function dropoff(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    public function driver_review(): BelongsTo
    {
        return $this->belongsTo(DriverReview::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class);
    }

    public function tracking_numbers(): HasMany
    {
        return $this->hasMany(TrackingNumber::class);
    }
}
