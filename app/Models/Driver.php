<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(DriverReview::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeOddOrEven(Builder $query, $key): void
    {
        $query->whereHas(
            'vehicle',
            fn(Builder $query) => $query->where("meta->{$key}", true)
        );
    }

    /**
     * Return drivers with a odd or even based on specific date
     */
    public static function oddOrEvenByDate(DateTime $date): Collection
    {
        $key = (intval($date->format('d')) % 2 == 0) ? 'even' : 'odd';

        return static::oddOrEven($key)->get();
    }

    /**
     * Return drivers with a odd or even based on today's date
     * or any driver available
     *
     * @param bool $is_odd_even flag whether to search by odd or even
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function available($is_odd_even = false): Collection
    {
        return ($is_odd_even)
            ? static::oddOrEvenByDate(new DateTime())
            : static::get();
    }


    public function hasOdd(): bool
    {
        return $this->vehicle->hasOdd();
    }

    public function hasEven(): bool
    {
        return $this->vehicle->hasEven();
    }
}
