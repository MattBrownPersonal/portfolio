<?php

namespace Tests\Browser\Admin;

use App\Models\Site;
use App\Models\User;
use Laravel\Dusk\Browser;

class VM558Test extends AdminTestCase
{
    /**
     * Tests that searchable dropdown filters correctly
     * After searching for "lea"
     * All entries whould include "lea"
     *
     * @return void
     */
    public function testSearchableDropdown()
    {
        Site::factory()->create([
            'name'=>'Hello World',
            'uses_categories'=>1,
        ]);

        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/users/{$adminUser->id}"))
                    ->assertSee('Site(s)')
                    ->click('.multiselect__select')
                    ->typeSlowly('input[placeholder="Search or add a tag"]', 'Hell', 500)
                    ->assertSee('Hello World');
        });
    }
}
