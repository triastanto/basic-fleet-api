<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Services\EOSAPI;
use Mockery\MockInterface;
use Tests\FeatureTestCase;

class CustomerTest extends FeatureTestCase
{
    protected function approver($customer): Customer
    {
        // intended customer's approver
        Customer::factory()->create(['meta->personnel_no' => 99999]);
        $this->mock(EOSAPI::class, function (MockInterface $mock) {
            $mock->shouldReceive('minManagerBoss')
                ->andReturn(["personnel_no" => 99999]);
        });
        return Customer::getApprover($customer->meta['personnel_no']);
    }

    /** @test */
    public function it_successfully_retrieves_customer_as_approver(): void
    {
        $customer = $this->customerAuth();
        $approver = $this->approver($customer);

        // shouldn't be retrieve as approver
        Customer::factory()->create(['meta->personnel_no' => 12345]);

        $this->assertInstanceOf(Customer::class, $approver);
        $this->assertEquals($approver->meta['personnel_no'], 99999);
        $this->assertDatabaseHas('customers', [
            'meta->personnel_no' => $approver->meta['personnel_no']
        ]);
    }
}
