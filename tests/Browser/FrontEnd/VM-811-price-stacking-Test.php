<?php

namespace Tests\Browser\FrontEnd;

use App\Models\AdditionalImage;
use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\PredefinedImage;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

class VM811pricestackingTest extends FrontEndTestCase
{
    const BTNTEXTCHOOSE = 'Choose From Our Selection';

    const VIEWPRODUCT = 'View Product';

    /**
     * Check price when adding multiple predfined images and not clearing previous
     *
     * @group price
     *
     * @return void
     **/
    public function testProductPriceCalculatedWithSecondPredefinedImageNoClear()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPrice = 1000;
            $product = Product::factory()->withPrice($productPrice)->create();
            $predefinedImagePrice = 100;
            $predefinedImage1 = PredefinedImage::factory()->withProduct($product)->withPrice($predefinedImagePrice)->create();
            $predefinedImage2 = PredefinedImage::factory()->withProduct($product)->withPrice($predefinedImagePrice)->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutPredefined()
                        ->create();
                    $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                } else {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutPredefined()
                        ->create();
                }

                $customProductText = CustomProductText::factory()
                    ->withProduct($product)
                    ->create();
                CustomTextField::factory()
                    ->withLineLengthFromDeceased($deceased)
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->create();
            }
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
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)))
            ->press('Choose From Our Selection')
            ->waitFor('.select-predefined-image.v-card')
            // select second predefined image
            ->with('.select-predefined-image.v-card', function ($modal) {
                $modal->waitForText('Select an image')
                ->waitFor('.predefined-box:nth-child(2) div button')
                ->press('.predefined-box:nth-child(2) div button');
            })
            // confirm new price
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)));

            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Check price when adding multiple predfined images and clearing inbetween
     *
     * @group price
     * @group price2
     *
     * @return void
     **/
    public function testProductPriceCalculatedWithSecondPredefinedImageWithClear()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $productPrice = 1000;
            $product = Product::factory()->withPrice($productPrice)->create();
            $predefinedImagePrice = 100;
            $predefinedImage1 = PredefinedImage::factory()
                ->withProduct($product)
                ->withPrice($predefinedImagePrice)
                ->withImageUrl('not empty')
                ->create();
            $predefinedImage2 = PredefinedImage::factory()
                ->withProduct($product)
                ->withPrice($predefinedImagePrice)
                ->withImageUrl('not empty')
                ->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutPredefined()
                        ->withCustomImageId(1)
                        ->create();
                    $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                } else {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutPredefined()
                        ->withCustomImageId(1)
                        ->create();
                }

                $customProductText = CustomProductText::factory()
                    ->withProduct($product)
                    ->create();
                CustomTextField::factory()
                    ->withLineLengthFromDeceased($deceased)
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->create();
            }
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
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)))
            // clear previous image
            ->waitForText('Clear selected image')
            ->press('Clear selected image')
            // confirm price correctly reset
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice)))
            ->press('Choose From Our Selection')
            ->waitFor('.select-predefined-image.v-card')
            // select second predefined image
            ->with('.select-predefined-image.v-card', function ($modal) {
                $modal->waitForText('Select an image')
                ->waitFor('.predefined-box:nth-child(2) div button')
                ->press('.predefined-box:nth-child(2) div button');
            })
            // confirm new price
            ->waitForText('Total: £'.sprintf('%0.2f', ($productPrice + $predefinedImagePrice)));

            $this->assertNoErrorsInConsole($browser);
        });
    }
}
