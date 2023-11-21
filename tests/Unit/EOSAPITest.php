<?php

namespace Tests\Unit;

use App\Services\EOSAPI;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EOSAPITest extends TestCase
{
    /** @test */
    public function it_can_fetch_min_manager_boss(): void
    {
        // the fake base uri to be set in config & to be used in http fake
        $base_uri = 'http://example.com/api/';
        Config::set('services.eosapi.base_uri', $base_uri);

        // the fake response array returned
        $expected = ["personnel_no" => 99999];

        // whenever request is made, return fake response
        Http::fake([
            $base_uri . "*" => Http::response($expected)
        ]);

        // get the minManagerBoss for dummy personnel_no
        $result = (new EOSAPI())->minManagerBoss(12345);

        $this->assertEquals($expected, $result);
    }
}
