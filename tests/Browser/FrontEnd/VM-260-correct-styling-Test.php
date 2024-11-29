<?php

namespace Tests\Browser\FrontEnd;

use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

class VM260redsignsTest extends FrontEndTestCase
{
    /**
     * Test that default styles are available at login.
     *
     * @group vm260
     * @return void
     */
    public function testLoginHasDefaultStyles()
    {
        // to ensure the browser instance is not affected by previous tests where it may have picked up non-default styles, runs in an isolated browser.
        $this->browse(function ($currentBrowser, $newBrowser) {
            $primary_colour = 'rgb(0, 76, 59)'; // new designs
            $secondary_colour = 'rgb(245, 245, 235)'; // new designs
            $script = "const el = document.createElement('div');el.classList.add('primary-colour');document.body.append(el);";
            $newBrowser->visit($this->customerUrl('/'));
            $newBrowser->script($script);
            $newBrowser->assertScript("getComputedStyle(document.querySelector('.primary-colour')).color", $primary_colour);
            $script = "const el = document.createElement('div');el.classList.add('secondary-colour');document.body.append(el);";
            $newBrowser->script($script);
            $newBrowser->assertScript("getComputedStyle(document.querySelector('.secondary-colour')).color", $secondary_colour);
            $newBrowser->quit();
        });
    }

    /**
     * Test that site styles are available after login.
     *
     * @group vm260
     * @return void
     */
    public function testAfterLoginHasNonDefaultStyles()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $browser->visit($this->customerUrl($link));
            $primary_colour = 'rgb(49, 63, 84)';    // value from siteStylesFactory #313f54
            $secondary_colour = 'rgb(217, 161, 55)';      // value from siteStylesFactory #d9a137
            $script = "const el = document.createElement('div');el.classList.add('primary-colour');document.body.append(el);";
            $browser->script($script);
            $browser->assertScript("getComputedStyle(document.querySelector('.primary-colour')).color", $primary_colour);
            $script = "const el = document.createElement('div');el.classList.add('secondary-colour');document.body.append(el);";
            $browser->script($script);
            $browser->assertScript("getComputedStyle(document.querySelector('.secondary-colour')).color", $secondary_colour);
        });
    }
}
