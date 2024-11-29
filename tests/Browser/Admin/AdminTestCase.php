<?php

namespace Tests\Browser\Admin;

use Tests\DuskTestCase;

class AdminTestCase extends DuskTestCase
{
    protected function loginAs($browser, $username, $password)
    {
        $browser->visit($this->adminUrl('/login'));
        if (str_ends_with($browser->driver->getCurrentURL(), '/login')) {
            $browser->assertSee('Login')
            ->type('input[name=email]', $username)
            ->type('input[name=password]', $password)
            ->click('button[type=submit]');
        }
    }
}
