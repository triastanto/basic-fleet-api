<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vehicle extends Model
{
    use HasFactory;

    protected $casts = [
        'meta' => 'array',
    ];

    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class);
    }

    public function hasOdd(): bool
    {
        $number = preg_replace('/[^0-9]/', '', $this->plate_number);

        return $number % 2 > 0;
    }

    public function hasEven(): bool
    {
        $number = preg_replace('/[^0-9]/', '', $this->plate_number);

        return $number % 2 === 0;
    }
}
