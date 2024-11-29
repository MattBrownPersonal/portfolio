<?php

namespace Tests\Feature\API\Users;

use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testThatANewMemberOfStaffCanBeAddedByAdminFailsValidation()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/users', $this->invalidData());
        $response->assertStatus(422);
    }

    /** @test */
    public function testThatCanReadUsersFromAdminTableWhoAreAlsoVivediaStaff()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $response = $this->getJson('/api/users');

        $response->assertOk();
    }

    /** @test */
    public function testThatANewMemberOfStaffCanBeUpdatedByAdmin()
    {
        Role::firstOrCreate(['id' => Role::VIVEDIA_ADMIN_ID, 'name' => Role::VIVEDIA_ADMIN]);
        Role::firstOrCreate(['id' => Role::VIVEDIA_STAFF_ID, 'name' => 'adminstaff']);
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $this->withoutExceptionHandling();

        $response = $this->putJson('/api/users/'.$user->id, $this->validData());
        $response->assertOk();
    }

    /** @test */
    public function testThatANewMemberOfStaffCanBeUpdatedByAdminFails()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        $response = $this->putJson('/api/users/'.$user->id, $this->invalidData());
        $response->assertStatus(422);
    }

    public function testThatAMemberOfStaffCanBeDeleted()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->deleteJson('/api/users/'.$user->id);

        $response->assertOk();
    }

    public function testCheckThatAFakeIdIsntProcessedWhenDeleteUserIsCalled()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        User::factory()->create();

        $response = $this->deleteJson('/api/users/'.'abc');

        $response->assertStatus(404);
    }

    private function validData()
    {
        $site = Site::factory()->create();

        return [
            'firstname' => 'Fake',
            'lastname' => 'Person',
            'email' => 'Fake@email.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'roles' => '1', '2',
            'sites' => $site,
        ];
    }

    private function invalidData()
    {
        $site = Site::factory()->create();

        return [
            'firstname' => 999,
            'lastname' => 'Person',
            'email' => 'Fake@email.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'roles' => '1', '2',
            'sites' => $site,
        ];
    }
}
