<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

/**
 * VM-782 When viewing product pages
 * All "&nbsp;" characters should be removed
 */
class VM782Test extends FrontEndTestCase
{
    /**
     * All nbsp should be removed from product descriptions
     *
     * @group VM-782
     *
     * @return void
     **/
    public function testNBSPisNotVisible()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $products = Product::factory()->withSite($site)->withFeaturedProduct()->create();
            // Product description string for Memorial Bench exclusive from db
            $products->short_description = '<div style="text-align: left;">Commemorate your loved one with a memorial bench which will exclusively&nbsp;display &nbsp; your chosen plaque in our Garden of Remembrance</div>';
            $products->save();

            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPath = '/3/productpage/16'; // Book of rememberance: 2 line entry

            $browser->visit($this->customerUrl($link))
            ->waitForText('Featured products')
            ->assertDontSee('&nbsp;');
        });
    }
}
