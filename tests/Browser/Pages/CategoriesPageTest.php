<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

/**
 * Tests for the categories apge
 */
class CategoriesPageTest extends FrontEndTestCase
{
    /**
     * VM-790 visiting the categories url should not create console errors
     *
     * @group vm790
     *
     * @return void
     **/
    public function testNoConsoleErrorsOnCategoriesPage()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $path = '/categories'; // Book of rememberance: 2 line entry
            $browser->visit($this->customerUrl($link.$path));
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
