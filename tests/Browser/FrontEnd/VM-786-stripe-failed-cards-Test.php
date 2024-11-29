<?php

namespace Tests\Browser\FrontEnd;

use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

/**
 * VM-786
 */
class VM786Test extends FrontEndTestCase
{
    /**
     * Tests if stripe deals with failed cards correctly
     *
     * @group vm786
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testStripeFailCards()
    {
        $cardsToTest = [
            (object) [
                'number' => '4000000000000002',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'declined',
            ],
            (object) [
                'number' => '4000000000009995',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'insufficient',
            ],
            (object) [
                'number' => '4000000000009987',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'declined',
            ],
            (object) [
                'number' => '4000000000009979',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'declined',
            ],
            (object) [
                'number' => '4000000000000069',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'expired',
            ],
            (object) [
                'number' => '4000000000000127',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'security code',
            ],
            (object) [
                'number' => '4000000000000119',
                'expiry' => '01/30',
                'cvc' => '123',
                'postalCode' => '12345',
                'expected' => 'error',
            ],
        ];
        foreach ($cardsToTest as $card) {
            $this->browse(function (Browser $browser) use ($card) {
                $site = Site::factory()->create();
                $deceased = Deceased::factory()->withSiteId($site)->create();
                $customTextField = CustomTextField::factory()->withLineLengthFromDeceased($deceased)->create();

                $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
                $link = $this->getDeceasedLink($deceased);
                $productPath = '/3/productpage/16'; // Book of rememberance: 2 line entry

                $browser
                ->visit($this->customerUrl($link.$productPath))
                ->waitForText('Buy now');

                for ($i = 0; $i < 10; $i++) {
                    $es = $browser->driver->findElements(WebDriverBy::cssSelector('input[name="text-'.$i.'"]'));
                    if (count($es) > 0) {
                        $browser->value('input[name="text-'.$i.'"]', 'test');
                    }
                }
                $browser
                    ->press('Buy now')
                    ->type('input[aria-label="name"]', 'Test User')
                    ->type('input[aria-label="email"]', 'email@email.com')
                    ->type('input[aria-label="phone"]', '01143334444')
                    ->waitFor('iframe[title="Secure payment input frame"]');
                $browser->withinFrame('iframe[title="Secure payment input frame"]', function ($browser) use ($card) {
                    $browser
                    ->waitForText('Card number')
                    ->clear('input[name="number"]')
                    ->clear('input[name="expiry"]')
                    ->clear('input[name="cvc"]')
                    ->clear('input[name="postalCode"]');
                });
                $browser->withinFrame('iframe[title="Secure payment input frame"]', function ($browser) use ($card) {
                    $browser
                    ->type('input[name="number"]', $card->number)
                    ->type('input[name="expiry"]', $card->expiry)
                    ->type('input[name="cvc"]', $card->cvc)
                    ->type('input[name="postalCode"]', $card->postalCode);
                })
                ->pressAndWaitFor('Pay now', 10);
                $browser
                ->waitForText($card->expected)
                ->assertSee($card->expected);
            });
        }
    }
}
