<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingNumber extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (TrackingNumber $tracking_number) {
            $tracking_number->created_at = $tracking_number->freshTimestamp();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
