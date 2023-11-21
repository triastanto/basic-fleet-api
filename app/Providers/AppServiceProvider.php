<?php

namespace App\Providers;

use App\Services\EOSAPI;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EOSAPI::class, function () {
            return new EOSAPI();
        });
    }
}
