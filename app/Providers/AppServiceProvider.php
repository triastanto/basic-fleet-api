<?php

namespace App\Providers;

use App\Enums\State;
use App\Services\EOSAPI;
use App\Services\Workflow;
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

        $this->app->singleton(Workflow::class, function () {
            return Workflow::getInstance(State::WAITING_APPROVAL);
        });
    }
}
