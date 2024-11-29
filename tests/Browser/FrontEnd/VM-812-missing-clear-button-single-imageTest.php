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

class VM812MissingClearButtonSingleImageTest extends FrontEndTestCase
{
    const BTNTEXTCHOOSE = 'Choose From Our Selection';

    const VIEWPRODUCT = 'View Product';

    /**
     * Clear button should appear even when there is only a single predefined image
     *
     * @group vm812
     *
     * @return void
     **/
    public function testClearButtonDisplayedEvenWhenOnlyASingleImageSetup()
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
            ->waitForText('Choose From Our Selection')
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
            ->waitForText('Clear selected image')
            ->assertSee('Clear selected image');

            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Clear button should disapear after being clicked, even when there is only a single predefined image
     *
     * @group vm812
     *
     * @return void
     **/
    public function testClearButtonRemoveOnClickEvenWhenOnlyASingleImageSetup()
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
            ->waitForText('Choose From Our Selection')
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
            ->waitForText('Clear selected image')
            ->press('Clear selected image')
            ->waitUntilMissingText('Clear selected image')
            ->assertDontSee('Clear selected image');

            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Clear button should appear after selecting the product via featured links
     * (that is the fail condition that QA identified)
     *
     * @group vm812
     * @group vm2-604
     *
     * @return void
     **/
    public function testClearButtonShowsWhenProductSelectedFromFeatured()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $description = 'FULLD0:: test 1, <br>, test 2, </br>, test 3, </ br>, test 4 <b>bold</b>';
            $shortDescription = 'SHORTD0:: t 1, <br>, t 2, </br>, t 3, </ br>, t 4 <b>bld</b>';
            $productPrice = 1000;
            $product = Product::factory()
                ->withSite($site)
                ->withPrice($productPrice)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->create();
            $predefinedImagePrice = 100;
            // add a featured product
            $featuredProductPrice = 2000;
            $featuredProduct = Product::factory()
                ->withSite($site)
                ->withPrice($featuredProductPrice)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->withFeaturedProduct()
                ->create();
            PredefinedImage::factory()->withProduct($product)->withPrice($predefinedImagePrice)->withImageUrl('not empty')->create();
            PredefinedImage::factory()->withProduct($featuredProduct)->withPrice($predefinedImagePrice)->withImageUrl('not empty')->create();
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

            // set up same for featured product
            $customImageFP = CustomImage::factory()
                ->doesNotUseMaterials()
                ->withProduct($featuredProduct)
                ->withLayoutPredefined()
                ->create();
            $customProductTextFP = CustomProductText::factory()
                ->withProduct($featuredProduct)
                ->create();
            CustomTextField::factory()
                ->withLineLengthFromDeceased($deceased)
                ->withCustomImage($customImageFP)
                ->withCustomProductText($customProductTextFP)
                ->create();

            // start by visting the standard product
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                // confirm initial price
                ->waitForText('Total: £')
                ->waitForText('Total: £'.sprintf('%0.2f', $productPrice));
            // find featured
            $browser->scrollIntoView('.featured-products-container')
                // click featured
                ->waitFor('.product-link')
                ->press('.product-link')
                // repeat basics
                ->waitForText('Total: £')
                ->waitForText('Total: £'.sprintf('%0.2f', $featuredProductPrice))
                ->waitForText('Choose From Our Selection')
                ->press('Choose From Our Selection')
                ->waitFor('.select-predefined-image.v-card')
                // select the first predfined image
                ->with('.select-predefined-image.v-card', function ($modal) {
                    $modal->waitForText('Select an image')
                    ->waitFor('.predefined-box:nth-child(1) div button')
                    ->press('.predefined-box:nth-child(1) div button');
                })
                ->waitForText('Clear selected image')
                ->assertSee('Clear selected image');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
