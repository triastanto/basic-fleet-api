<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

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
