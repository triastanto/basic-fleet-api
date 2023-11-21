<?php

namespace Tests\Unit;

use App\Services\EOSAPI;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    /** @test */
    public function eosapi_is_registered_as_singleton(): void
    {
        $base_uri = 'http://example.com/api/';
        Config::set('services.eosapi.base_uri', $base_uri);

        $eosapi = $this->app->make(EOSAPI::class);

        $this->assertInstanceOf(EOSAPI::class, $eosapi);
    }
}
