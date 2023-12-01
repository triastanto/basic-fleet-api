<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Tests\FeatureTestCase;

class VehicleTest extends FeatureTestCase
{
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
