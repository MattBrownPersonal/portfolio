<?php

namespace Tests\Browser\FrontEnd;

use App\Models\CustomImage;
use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

/**
 * VM-785
 */
class VM785Test extends FrontEndTestCase
{
    /**
     * Tests if the carousel filters the images correctly by material
     *
     * @group vm785
     * @group problematic
     *
     * @return void
     **/
    public function testMaterialSelect()
    {
        CustomImage::factory()
        ->create([
            'product_id' => 16,
            'image_id' => 1,
            'material_id' => 1,
            'layout' => 'none',
        ])
        ->create([
            'product_id' => 16,
            'image_id' => 1,
            'material_id' => 1,
            'layout' => 'none',
        ]);

        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPath = '/3/productpage/16'; // Book of rememberance: 2 line entry

            $browser->visit($this->customerUrl($link.$productPath))
                ->waitForText('Material')
                ->assertPresent('button[aria-label="Next Image"]');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
