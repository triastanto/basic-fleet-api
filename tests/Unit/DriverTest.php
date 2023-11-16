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

    /** @test */
    public function it_returns_odd_plate_number_driver(): void
    {
        $vehicle = Vehicle::factory()->odd()->create();
        $driver = Driver::factory()->create();
        $vehicle->driver()->save($driver);

        $oddDriver = Driver::oddEvenPlateNumber('odd')->first();

        $this->assertTrue($oddDriver->hasOdd());
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'meta->odd' => true,
        ]);
    }

    /** @test */
    public function it_returns_even_plate_number_driver(): void
    {
        $vehicle = Vehicle::factory()->even()->create();
        $driver = Driver::factory()->create();
        $vehicle->driver()->save($driver);

        $evenDriver = Driver::oddEvenPlateNumber('even')->first();

        $this->assertTrue($evenDriver->hasEven());
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'meta->even' => true,
        ]);
    }
}
