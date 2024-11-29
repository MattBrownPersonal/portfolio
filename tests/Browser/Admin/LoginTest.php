<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * Login with invalid credentials returns an error message
     *
     * @group login
     * @return void
     */
    public function testLoginWithInvalidDetails()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->adminUrl('/logout'))
                    ->assertSee('Login');
            $browser->type('input[name=email]', 'no-existant-user@example.com');
            $browser->type('input[name=password]', 'P@ssw0rd!');
            $browser->click('button[type=submit]');
            $browser->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * Login with valid username but invalid passwords returns an error message
     *
     * @group login
     * @return void
     */
    public function testLoginWithValidUsernameButInvalidPassword()
    {
        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();

            $browser->visit($this->adminUrl('/logout'))
                    ->waitForText('Login');
            $browser->type('input[name=email]', $adminUser->email);
            $browser->type('input[name=password]', '--invalid-password--');
            $browser->click('button[type=submit]');

            $browser->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * Login with valid credentials displays admin dashboard
     *
     * @group login
     * @return void
     */
    public function testLoginWithValidDetails()
    {
        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();

            $browser->visit($this->adminUrl('/logout'))
                    ->assertSee('Login');
            $browser->type('input[name=email]', $adminUser->email);
            $browser->type('input[name=password]', 'password');
            $browser->click('button[type=submit]');

            // User is logged in and can see navigation bar
            $browser->waitForText('Users');
            $browser->assertSee('Suppliers');
            $browser->assertSee('Sites');
        });
    }
}
