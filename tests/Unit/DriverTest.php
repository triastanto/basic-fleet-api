<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\DriverReview;
use App\Models\Vehicle;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_driver_has_many_driver_reviews(): void
    {
        $driver = Driver::factory()->create();

        $this->assertInstanceOf(Collection::class, $driver->reviews);
    }

    /** @test */
    public function a_driver_belongs_to_vehicle(): void
    {
        $driver = Driver::factory()->create();

        $this->assertInstanceOf(Vehicle::class, $driver->vehicle);
    }

    /** @test */
    public function it_returns_odd_driver(): void
    {
        $vehicle = Vehicle::factory()->odd()->create();
        $driver = Driver::factory()->create();
        $vehicle->driver()->save($driver);

        $oddDriver = Driver::oddOrEven('odd')->first();

        $this->assertTrue($oddDriver->hasOdd());
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'meta->odd' => true,
        ]);
    }

    /** @test */
    public function it_returns_even_driver(): void
    {
        $vehicle = Vehicle::factory()->even()->create();
        $driver = Driver::factory()->create();
        $vehicle->driver()->save($driver);

        $evenDriver = Driver::oddOrEven('even')->first();

        $this->assertTrue($evenDriver->hasEven());
        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'meta->even' => true,
        ]);
    }

    /** @test */
    public function it_returns_even_drivers_when_even_date(): void
    {
        Driver::factory()
            ->count(2)
            ->state(new Sequence(
                ['vehicle_id' => Vehicle::factory()->even()->create()->id],
                ['vehicle_id' => Vehicle::factory()->odd()->create()->id]
            ))
            ->create();
        $evenDate = new DateTime('2023-11-10');

        $evenDrivers = Driver::oddOrEvenByDate($evenDate);

        $this->assertTrue($evenDrivers->every(
            fn (Driver $driver) => $driver->hasEven()
        ));
    }

    /** @test */
    public function it_returns_odd_drivers_when_odd_date(): void
    {
        Driver::factory()
            ->count(2)
            ->state(new Sequence(
                ['vehicle_id' => Vehicle::factory()->even()->create()->id],
                ['vehicle_id' => Vehicle::factory()->odd()->create()->id]
            ))
            ->create();
        $oddDate = new DateTime('2023-11-11');

        $oddDrivers = Driver::oddOrEvenByDate($oddDate);

        $this->assertTrue($oddDrivers->every(
            fn (Driver $driver) => $driver->hasOdd()
        ));
    }

    /** @test */
    public function it_returns_odd_or_even_when_available_with_oe_toggle(): void
    {
        Driver::factory()
            ->count(2)
            ->state(new Sequence(
                ['vehicle_id' => Vehicle::factory()->even()->create()->id],
                ['vehicle_id' => Vehicle::factory()->odd()->create()->id]
            ))
            ->create();
        $today = intval((new DateTime())->format('d'));
        $toggle = ($today) % 2 == 0 ? 'even' : 'odd';

        $oddOrEvenDrivers = Driver::available(true);

        switch ($toggle) {
            case 'even':
                $this->assertTrue($oddOrEvenDrivers->every(
                    fn (Driver $driver) => $driver->hasEven()
                ));
                break;

            case 'odd':
                $this->assertTrue($oddOrEvenDrivers->every(
                    fn (Driver $driver) => $driver->hasOdd()
                ));
                break;
        }
    }
}
