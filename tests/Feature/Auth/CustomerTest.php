<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    /** @test */
    public function a_customer_can_authenticate_using_valid_credential(): void
    {
        $customer = Customer::factory()->create();
        $attribute = [
            'email' => $customer->email,
            'password' => 'password',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('customers.token'), $attribute)
            ->assertOk();
    }

    /** @test */
    public function a_customer_cant_authenticate_using_invalid_password(): void
    {
        $customer = Customer::factory()->create();
        $attribute = [
            'email' => $customer->email,
            'password' => 'invalidpassword',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('customers.token'), $attribute)
            ->assertUnauthorized();
    }

    /** @test */
    public function a_customer_cant_authenticate_using_nonexistent_email(): void
    {
        $attribute = [
            'email' => 'customerinvalid@invalidemail.com',
            'password' => 'password',
            'device_name' => 'My Device',
        ];

        $this->postJson(route('customers.token'), $attribute)
            ->assertUnauthorized();
    }
}
