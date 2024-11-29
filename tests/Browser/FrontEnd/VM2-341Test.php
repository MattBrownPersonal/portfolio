<?php

namespace Tests\Browser\FrontEnd;

use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\PredefinedImage;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

class VM2341Test extends FrontEndTestCase
{
    const RESPONSETEXT = 'Your message has been sent';

    const EMAIL = 'fake@example.com';

    const MESSAGE = 'Please see this design';

    /**
     * Check that response text is seen when sending an email to the crem
     *
     * @group testThatConfirmationTextAppearsWhenSendingEmail
     * @return void
     */
    public function testThatConfirmationTextAppearsWhenSendingEmail()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->withTestEmailAddress()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            PredefinedImage::factory()->withProduct($product)->withImageUrl('not empty')->create();
            $customImage = CustomImage::factory()
                ->doesNotUseMaterials()
                ->withProduct($product)
                ->withLayoutPredefined()
                ->create();

            $customProductText = CustomProductText::factory()
                ->withProduct($product)
                ->create();
            CustomTextField::factory()
                ->withLineLengthFromDeceased($deceased)
                ->withCustomImage($customImage)
                ->withCustomProductText($customProductText)
                ->create();
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
            ->waitForText('Details')
            ->press('Share with crematorium')
            ->waitForText('Do you wish to share this design with the crematorium?')
            ->press('Next')
            ->type('input[name="customer-email"]', self::EMAIL)
            ->press('Send')
            ->waitForText(self::RESPONSETEXT);
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
