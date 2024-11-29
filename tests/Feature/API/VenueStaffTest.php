<?php

namespace Tests\Feature\API;

use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VenueStaffTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testThatAllSitesStaffCanBeRead()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->getJson('/api/users');
        $response->assertOk();
    }

    /** @test */
    public function testThatSitesStaffFailsToAddIfValidationFails()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/siteStaff', $this->invalidData());
        $response->assertStatus(422);
    }

    /** @test */
    public function testThatSiteStaffCanBeUpdated()
    {
        Role::firstOrCreate(['id' => Role::SITE_STAFF_ID, 'name' => Role::VENUE_STAFF]);
        Role::firstOrCreate(['id' => Role::SITE_ADMIN_ID, 'name' => Role::VENUE_ADMIN]);

        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->putJson('/api/siteStaff/'.$user->id, $this->validData());
        $response->assertOk();
    }

    /** @test */
    public function testThatSiteStaffCanBeUpdatedFailsValidation()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        $response = $this->putJson('/api/siteStaff/'.$user->id, $this->invalidData());
        $response->assertStatus(422);
    }

    public function testThatSiteStaffCanBeDeleted()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->deleteJson('/api/siteStaff/'.$user->id);
        $response->assertOk();
    }

    public function testCheckThatAFakeIdIsntProcessedWhenDeleteUserIsCalled()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        User::factory()->create();

        $response = $this->deleteJson('/api/siteStaff/'.'abc');
        $response->assertStatus(404);
    }

    private function validData()
    {
        $site = Site::factory()->create();

        return [
            'firstname' => 'Fake',
            'lastname' => 'Person',
            'email' => 'Fake@testemail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'sites' => $site->id,
        ];
    }

    private function invalidData()
    {
        $site = Site::factory()->create();

        return [
            'firstname' => '',
            'lastname' => 'Person',
            'email' => 'Fake@email.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'sites' => $site->id,
        ];
    }
}
