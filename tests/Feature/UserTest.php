<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Workflow\State;
use App\Models\Workflow\Transition;
use App\Services\EOSAPI;
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
        $user = $this->auth();

        // define transitions from 1 => waiting_approval
        // to 2 => approved
        $from = State::factory()->create(['name' => 'waiting_approval']);
        $to = State::factory()->create(['name' => 'approved']);
        $transition = Transition::factory()->create([
            'name' => 'approve',
            'from_id' => $from->id,
            'to_id' => $to->id,
        ]);
        $order = Order::factory()->create([
            'approver_id' => $user->id,
            'state_id' => $from->id,
        ]);

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => $transition->from->id]
        );

        $this->postJson(route('orders.approve', ['order' => $order]))
            ->assertCreated();

        $this->assertDatabaseHas(
            'orders',
            ['id' => $order->id, 'state_id' => $transition->to->id]
        );
    }
}
