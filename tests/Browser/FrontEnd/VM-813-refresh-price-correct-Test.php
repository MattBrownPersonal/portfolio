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

class VM813RefreshPriceCorrectTest extends FrontEndTestCase
{
    const BTNTEXTCHOOSE = 'Choose From Our Selection';

    const VIEWPRODUCT = 'View Product';

    /**
     * Price should be correct after refreshing browser
     *
     * @group vm813
     *
     * @return void
     **/
    public function testPriceIsCorrectAfterBrowserRefresh()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPrice = 1000;
            $product = Product::factory()->withPrice($productPrice)->create();
            $predefinedImagePrice = 100;
            PredefinedImage::factory()->withProduct($product)->withPrice($predefinedImagePrice)->withImageUrl('not empty')->create();
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
            // confirm initial price
            ->waitForText('Total: £')
            ->waitForText('Total: £'.sprintf('%0.2f', $productPrice))
            ->press('Choose From Our Selection')
            ->waitFor('.select-predefined-image.v-card')
            // select the first predfined image
            ->with('.select-predefined-image.v-card', function ($modal) {
                $modal->waitForText('Select an image')
                ->waitFor('.predefined-box:nth-child(1) div button')
                ->press('.predefined-box:nth-child(1) div button');
            })
            // confirm new price
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)));
            $browser->refresh()
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)))
            ->assertSee('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)));
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
