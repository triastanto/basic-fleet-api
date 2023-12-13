<?php

namespace Tests\Feature\Auth;

use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DriverTest extends TestCase
{
    /** @test */
    public function a_driver_can_authenticate_using_valid_credential(): void
    {
        $driver = Driver::factory()->create();
        $attribute = [
            'email' => $driver->email,
            'password' => 'password',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('drivers.token'), $attribute)
            ->assertOk();
    }

    /** @test */
    public function a_driver_cant_authenticate_using_invalid_password(): void
    {
        $driver = Driver::factory()->create();
        $attribute = [
            'email' => $driver->email,
            'password' => 'invalidpassword',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('drivers.token'), $attribute)
            ->assertUnauthorized();
    }

    /** @test */
    public function a_driver_cant_authenticate_using_nonexistent_email(): void
    {
        $attribute = [
            'email' => 'driverinvalid@invalidemail.com',
            'password' => 'password',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('drivers.token'), $attribute)
            ->assertUnauthorized();
    }
}
