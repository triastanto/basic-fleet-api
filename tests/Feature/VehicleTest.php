<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

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
