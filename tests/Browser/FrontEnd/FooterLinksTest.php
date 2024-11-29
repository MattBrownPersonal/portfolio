<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

class FooterLinksTest extends FrontEndTestCase
{
    /**
     * Test footer area is shown.
     * Uses default deceased
     *
     * @group footer
     * @return void
     */
    public function testFooterAreaIsShown()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $selector = '.footer-link';
            $browser->visit($this->customerUrl($link.$this->getHomePath()))
                    ->waitFor('.footer-link')
                    ->assertVisible($selector);
        });
    }

    /**
     * Test footer links are shown.
     * Uses default deceased
     *
     * @group footer
     * @return void
     */
    public function testFooterLinksAreShown()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $browser->visit($this->customerUrl($link.$this->getHomePath()))
                    ->waitFor('.footer-links')
                    ->assertSeeIn('.footer-links a', 'Privacy')
                    ->assertSeeIn('.footer-links a:nth-child(2)', 'T&Cs')
                    ->assertSeeIn('.footer-links a:nth-child(3)', 'Cookies');
        });
    }

    /**
     * Test footer links are targeted.
     * Uses default deceased
     *
     * @group footer
     * @return void
     */
    public function testFooterLinksAreTargeted()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $browser->visit($this->customerUrl($link.$this->getHomePath()))
                    ->waitFor('.footer-links')
                    ->assertAttributeContains('.footer-links a', 'href', 'privacy')
                    ->assertAttributeContains('.footer-links a:nth-child(2)', 'href', 'termsofservice')
                    ->assertAttributeContains('.footer-links a:nth-child(3)', 'href', 'cookies');
        });
    }
}
