<?php

namespace Tests\Browser\Admin;

use App\Models\Product;
use App\Models\Site;
use App\Models\User;
use Laravel\Dusk\Browser;

class vm2801Test extends AdminTestCase
{
    /**
     * Save Product
     *
     * @return void
     * @group testSaveProduct
     */
    public function testThatAProductCanBeSaved()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/allsiteproducts/{$site->id}"))
                ->waitForText('Site Info Links')
                ->assertSee('Site Info Links')
                ->press('.v-btn--fab')
                ->waitForText('Add New Product')
                ->assertSee('Add New Product')
                ->type('product', 'Test Product')
                ->type('price', 100)
                ->press('SAVE');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
