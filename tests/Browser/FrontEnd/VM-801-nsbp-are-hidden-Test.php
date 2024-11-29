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
class VM801Test extends FrontEndTestCase
{
    /**
     * All tags except BRs should be removed from product descriptions when being displayed
     *
     * @group VM-801
     *
     * @return void
     **/
    public function testBRsPreservedInProductDescriptions()
    {
        $this->browse(function (Browser $browser) {
            $shortDescription = '<div style="text-align: left;">Commemorate your loved one this<br>remains <this br removed> with a memorial bench which will exclusively&nbsp;display &nbsp; your chosen plaque in our Garden of Remembrance</div>';
            $site = Site::factory()->create();
            $products = Product::factory()
                ->withSite($site)
                ->withFeaturedProduct()
                ->withShortDescription($shortDescription)
                ->create();

            $deceased = Deceased::factory()->withSiteId($site)->create();
            SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);

            $browser->visit($this->customerUrl($link))
            ->waitForText('Featured products')
            ->assertSourceHas('this<br>remains')
            ->assertDontSee('<this br removed>');
        });
    }
}
