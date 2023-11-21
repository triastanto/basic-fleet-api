<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\EOSAPI;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_successfully_retrieves_user_as_approver(): void
    {
        $user = $this->auth();

        // intended user's approver
        User::factory()->create(['meta->personnel_no' => 99999]);
        $this->mock(EOSAPI::class, function (MockInterface $mock) {
            $mock->shouldReceive('minManagerBoss')
            ->andReturn(["personnel_no" => 99999]);
        });

        // shouldn't be retrieve as approver
        User::factory()->create(['meta->personnel_no' => 12345]);

        $approver = User::getApprover($user->meta['personnel_no']);

        $this->assertInstanceOf(User::class, $approver);
        $this->assertDatabaseHas('users', [
            'meta->personnel_no' => $approver->meta['personnel_no']
        ]);
    }
}
