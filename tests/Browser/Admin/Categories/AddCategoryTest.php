<?php

namespace Tests\Browser\Admin\Categories;

use App\Models\Memorialisations;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Admin\AdminTestCase;

class AddCategoryTest extends AdminTestCase
{
    /**
     * Create a new category
     *
     * @return void
     * @group problematic
     */
    public function testAddNewCategory()
    {
        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();
            $this->loginAs($browser, $adminUser->email, 'password');

            $catName = 'TestCategory';
            $browser->visit($this->adminUrl('/memorialisations'))
                ->waitForText('Categories')
                ->assertSee('Categories')
                ->waitFor("button[aria-label='Add New Category']")
                ->click("button[aria-label='Add New Category']")
                ->waitForText('Add New Category')
                ->waitFor('input[name=name]', $catName)
                ->type('input[name=name]', $catName)
                ->waitFor('button[type=submit]')
                ->click('button[type=submit]')
                ->waitForText($catName)
                ->assertSee($catName);
        });
    }

    /**
     * Category must have unique name
     *
     * @return void
     * @group problematic
     * @group veryproblematic
     */
    public function testCategoryMustBeUnique()
    {
        $this->browse(function (Browser $browser) {
            $adminUser = User::factory()->create();
            $this->loginAs($browser, $adminUser->email, 'password');

            $category = Memorialisations::factory()->create();

            $browser->visit($this->adminUrl('/memorialisations'))
                    ->waitForText('Categories')
                    ->assertSee('Categories')
                    ->waitFor("button[aria-label='Add New Category']")
                    ->click("button[aria-label='Add New Category']")
                    ->waitForText('Add New Category')
                    ->waitFor('input[name=name]')
                    ->type('input[name=name]', $category->name)
                    ->waitFor('button[type=submit]')
                    ->click('button[type=submit]')
                    ->waitForText('A Category called '.$category->name.' already exists');
        });
    }
}
