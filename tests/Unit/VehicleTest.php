<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\Vehicle;
use Tests\TestCase;

class VehicleTest extends TestCase
{

    /** @test */
    public function a_vehicle_has_driver(): void
    {
        $vehicle = Vehicle::factory()->odd()->create();
        $driver = Driver::factory()->create();
        $vehicle->driver()->save($driver);

        $this->assertInstanceOf(Driver::class, $vehicle->driver);
    }
}
