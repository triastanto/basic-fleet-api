<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_driver_belongs_to_vehicle(): void
    {
        $driver = Driver::factory()->create();

        $this->assertInstanceOf(Vehicle::class, $driver->vehicle);
    }
}
