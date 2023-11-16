<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    use HasFactory;

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeOddEvenPlateNumber(Builder $query, $key): void
    {
        $query->whereHas(
            'vehicle',
            fn (Builder $query) => $query->where("meta->{$key}", true)
        );
    }

    public function hasOdd(): bool
    {
        return $this->vehicle->hasOdd();
    }

    public function hasEven(): bool
    {
        return $this->vehicle->hasEven();
    }

    public static function available(): Driver
    {
        // TODO: Return a driver with a odd/even license number
        return Driver::factory()->create();
    }
}
