<?php

namespace Tests\Browser\Admin;

use App\Models\Product;
use App\Models\User;
use Laravel\Dusk\Browser;

class vm2802Test extends AdminTestCase
{
    /**
     * A Dusk test example.
     *
     * @group testProductDeletion
     * @return void
     */
    public function testAProductCanBeDeleted()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::factory()->create();
            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/products/{$product->id}/{$product->site_id}"))
                ->waitForText('Product Details')
                ->assertSee('Product Details')
                ->click('.mdi-delete')
                ->waitForText('You are about to delete')
                ->assertSee('You are about to delete')
                ->press('DELETE');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
