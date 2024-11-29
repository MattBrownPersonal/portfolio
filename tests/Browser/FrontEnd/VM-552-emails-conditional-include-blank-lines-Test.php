<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

/**
 * VM-552 When sharing a memorial with family empty lines should be hidden
 * This should not affect sharing with crematoriums that need all lines including empty
 */
class VM552Test extends FrontEndTestCase
{
    /**
     * Blank lines should not be included when sharing a memorial with family
     *
     * @group vm552
     *
     * @return void
     **/
    public function testBlankLinesAreNotVisibleForFamilySharing()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPath = '/3/productpage/16'; // Book of rememberance: 2 line entry
            $browser->visit($this->customerUrl($link.$productPath))
                ->waitForText('Email')
                ->click('button.btn-share-family')
                ->whenAvailable('.v-dialog__content', function (Browser $modal) {
                    $modal->waitForText('Next')
                        ->click('button.btn-share-next')
                        ->waitForText('Personalisations')
                        ->assertDontSee('Custom:');
                });
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Blank lines should be included when sharing a memorial with crematorium
     *
     * @group vm552
     *
     * @return void
     **/
    public function testBlankLinesAreVisibleForCrematoriumSharing()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPath = '/3/productpage/16'; // Book of rememberance: 2 line entry
            $browser->visit($this->customerUrl($link.$productPath))
                ->waitForText('Share with crematorium')
                ->click('button.btn-share-crem')
                ->waitForText('Next')
                ->click('button.btn-share-next')
                ->assertSee('Custom');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
