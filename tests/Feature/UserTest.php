<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Workflow\State;
use App\Services\EOSAPI;
use Database\Seeders\WorkflowSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function approver($user): User
    {
        // intended user's approver
        User::factory()->create(['meta->personnel_no' => 99999]);
        $this->mock(EOSAPI::class, function (MockInterface $mock) {
            $mock->shouldReceive('minManagerBoss')
                ->andReturn(["personnel_no" => 99999]);
        });
        return User::getApprover($user->meta['personnel_no']);
    }

    /** @test */
    public function it_successfully_retrieves_user_as_approver(): void
    {
        $user = $this->auth();
        $approver = $this->approver($user);

        // shouldn't be retrieve as approver
        User::factory()->create(['meta->personnel_no' => 12345]);

        $this->assertInstanceOf(User::class, $approver);
        $this->assertEquals($approver->meta['personnel_no'], 99999);
        $this->assertDatabaseHas('users', [
            'meta->personnel_no' => $approver->meta['personnel_no']
        ]);
    }

    /** @test */
    public function an_approver_approve_order(): void
    {
        $approver = $this->auth();
        $this->seed(WorkflowSeeder::class);
        $order = Order::factory()->create(['approver_id' => $approver->id]);

        $this->assertEquals($order->state, State::waitingApproval());

        $this->postJson(route('orders.approve', ['order' => $order]))
            ->assertCreated();

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => State::approved()->id]
        );
    }
}
