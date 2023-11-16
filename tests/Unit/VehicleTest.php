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

    /** @test */
    public function verify_odd_plate_number(): void
    {
        $vehicle = Vehicle::factory()->odd()->create();

        $this->assertTrue($vehicle->hasOdd());
        $this->assertDatabaseHas('vehicles', ['meta->odd' => true]);
    }

    /** @test */
    public function verify_even_plate_number(): void
    {
        $vehicle = Vehicle::factory()->even()->create();

        $this->assertTrue($vehicle->hasEven());
        $this->assertDatabaseHas('vehicles', ['meta->even' => true]);
    }
}
