<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DriverReview extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public static function createWithAvailable(): static
    {
        return DriverReview::create([
            'driver_id' => Driver::available()->first()?->id,
        ]);
    }

}
