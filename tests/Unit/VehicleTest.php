<?php

namespace Tests\Unit;

use App\Models\Vehicle;
use DateTime;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    /** @test */
    public function vehicle_has_odd_plate_number(): void
    {
        $vehicle = Vehicle::factory()->odd()->create();

        $this->assertTrue($vehicle->hasOdd());
    }

    /** @test */
    public function vehicle_has_even_plate_number(): void
    {
        $vehicle = Vehicle::factory()->even()->create();

        $this->assertTrue($vehicle->hasEven());
    }
}
