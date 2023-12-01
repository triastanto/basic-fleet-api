<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\EOSAPI;
use Mockery\MockInterface;
use Tests\FeatureTestCase;

class UserTest extends FeatureTestCase
{
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
}
