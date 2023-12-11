<?php

namespace Tests;

use App\Models\Customer;
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

    public function auth(): Customer
    {
        return Sanctum::actingAs(Customer::factory()->create());
    }
}
