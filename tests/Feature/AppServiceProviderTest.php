<?php

namespace Tests\Feature;

use App\Services\EOSAPI;
use App\Services\Workflow;
use Illuminate\Support\Facades\Config;
use Tests\FeatureTestCase;

class AppServiceProviderTest extends FeatureTestCase
{
    /** @test */
    public function eosapi_is_registered_as_singleton(): void
    {
        $base_uri = 'http://example.com/api/';
        Config::set('services.eosapi.base_uri', $base_uri);

        $eosapi = $this->app->make(EOSAPI::class);

        $this->assertInstanceOf(EOSAPI::class, $eosapi);
    }

    /** @test */
    public function workflow_is_registered_as_singleton(): void
    {
        $workflow = $this->app->make(Workflow::class);

        $this->assertInstanceOf(Workflow::class, $workflow);
    }
}
