<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EOSAPI
{
    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.eosapi.base_uri');
    }

    public function minManagerBoss(string $personnel_no): array
    {
        $uri = "structdisp/{$personnel_no}/minManagerBoss";

        $response = Http::get("{$this->baseUri}/$uri");

        return $response->json();
    }
}
