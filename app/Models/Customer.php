<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\EOSAPI;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'meta',
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
        'meta' => 'array',
    ];

    public function scopeWherePersonnelNo(
        Builder $query,
        int $personnel_no
    ): void {
        $query->where('meta->personnel_no', $personnel_no);
    }

    public static function getApprover(int $personnel_no): static
    {
        /** @var \App\Services\EOSAPI */
        $eosapi = app(EOSAPI::class);
        $approver = $eosapi->minManagerBoss($personnel_no);

        return static::wherePersonnelNo($approver['personnel_no'])->first();
    }
}
