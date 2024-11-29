<?php

namespace Tests\Browser;

use App\Models\ItemOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Admin\AdminTestCase;

class OrderTest extends AdminTestCase
{
    // use RefreshDatabase;
    /**
     * Amend Contact Type
     *
     * @return void
     * @group amendContactType
     */
    public function testThatanOrderContactTypeCanBeUpdated()
    {
        ItemOrder::factory()->create();

        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();
            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl('/orders'))
                ->waitForText('Orders')
                ->assertSee('Orders')
                ->waitFor('.viewOrderButton')
                ->press('.viewOrderButton')
                ->waitForText('Order Details')
                ->waitFor('.order-dropdown')
                ->click('.order-dropdown')
                ->whenAvailable('.v-menu__content', function (Browser $modal) {
                    $modal->waitForText('Update Order Type')
                        ->click('.orderTypes')
                        ->press('.v-list-item:nth-child(2) .v-list-item__title')
                        ->press('SAVE');
                });
        });
    }
}
