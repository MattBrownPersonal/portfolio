<?php

namespace Tests\Feature\Models;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testDefaultUserPermissions()
    {
        $user = User::factory()->create();

        // Perform tests on the $user
        $this->assertFalse($user->canImpersonate());
        $this->assertFalse($user->canBeImpersonated());

        // Now login as the user and perform Auth checks
        $this->actingAs($user);
        $this->assertFalse($user->isVivedia());
        $this->assertFalse($user->isVivediaAdmin());
        $this->assertFalse($user->isSiteAdmin());
    }

    public function testCanImpersonate()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->canImpersonate());

        $venueAdmin = Role::firstOrCreate(['id' => Role::VIVEDIA_ADMIN_ID, 'name' => Role::VIVEDIA_ADMIN]);
        $user->roles()->attach($venueAdmin);
        $this->assertTrue($user->canImpersonate());
    }

    public function testCanBeImpersonatedVenueAdmin()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->canBeImpersonated());

        $venueAdmin = Role::firstOrCreate(['id' => Role::SITE_ADMIN_ID, 'name' => Role::VENUE_ADMIN]);
        $user->roles()->attach($venueAdmin);
        $this->assertTrue($user->canBeImpersonated());
    }

    public function testCanBeImpersonatedVenueStaff()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->canBeImpersonated());

        $venueStaff = Role::firstOrCreate(['id' => Role::SITE_STAFF_ID, 'name' => Role::VENUE_STAFF]);
        $user->roles()->attach($venueStaff);
        $this->assertTrue($user->canBeImpersonated());
    }

    public function testCanBeImpersonatedVenueAdminAndStaff()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->canBeImpersonated());

        $venueStaff = Role::firstOrCreate(['id' => Role::SITE_STAFF_ID, 'name' => Role::VENUE_STAFF]);
        $user->roles()->attach($venueStaff);
        $venueAdmin = Role::firstOrCreate(['id' => Role::SITE_ADMIN_ID, 'name' => Role::VENUE_ADMIN]);
        $user->roles()->attach($venueAdmin);
        $this->assertTrue($user->canBeImpersonated());
    }
}
