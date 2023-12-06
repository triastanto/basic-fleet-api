<?php

namespace App\Models;

use DateTime;
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

    public static function createWithDriver(
        bool $odd_even = false,
        DateTime $date = null
    ): static {
        $driver_id = ($odd_even) ? Driver::oddOrEvenByDate($date)->first()?->id
            : Driver::available()->first()?->id;

        return DriverReview::create(['driver_id' => $driver_id]);
    }
}
