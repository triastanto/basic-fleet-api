<?php

namespace Tests;

use App\Models\Customer;
use App\Models\Driver;
use Database\Seeders\WorkflowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

abstract class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;
    protected $seeder = WorkflowSeeder::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function customerAuth(): Customer
    {
        return Sanctum::actingAs(Customer::factory()->create(), ['customer']);
    }

    public function driverAuth(): Driver
    {
        return Sanctum::actingAs(Driver::factory()->create(), ['driver']);
    }
}
