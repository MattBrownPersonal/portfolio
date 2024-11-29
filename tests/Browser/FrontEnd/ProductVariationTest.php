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

/**
 * Open the product page with product variations and assert no errors in the console
 */
class ProductVariationTest extends FrontEndTestCase
{
    const THIRDLINE = 'Line 3';

    const FIRSTLINE = 'Line 1';

    const ADDITIONALIMAGE = 'Additional image';

    const BTNTEXTUPLOAD = 'Upload Own Image';

    const BTNTEXTCHOOSE = 'Choose From Our Selection';

    const VIEWPRODUCT = 'View Product';

    /**
     * No errors in console for basic product for site with categories
     *
     * @group product
     *
     * @return void
     **/
    public function testBasicProduct()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText($product->name);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for basic product for site without categories
     *
     * @group product
     *
     * @return void
     **/
    public function testBasicProductNoCategories()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText($product->name);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with categories and basic setup, single image, single line
     *
     * @group product
     *
     * @return void
     **/
    public function testProductSingleImageSingleLine()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $customImage = CustomImage::factory()
                ->doesNotUseMaterials()
                ->withProduct($product)
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
                ->waitForText(self::FIRSTLINE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site without categories and basic setup, single image, single line
     *
     * @group product
     *
     * @return void
     **/
    public function testProductNoCategoriesSingleImageSingleLine()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $customImage = CustomImage::factory()
                ->doesNotUseMaterials()
                ->withProduct($product)
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
                ->waitForText(self::FIRSTLINE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with categories 3 x images, 3 x lines
     *
     * @group product
     *
     * @return void
     **/
    public function testProduct3xImage3xLine()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            for ($i = 0; $i < 3; $i++) {
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
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
                ->waitForText(self::THIRDLINE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site without categories and 3 x images, 3 x lines
     *
     * @group product
     *
     * @return void
     **/
    public function testProductNoCategories3xImage3xLine()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            for ($i = 0; $i < 3; $i++) {
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
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
                ->waitForText(self::THIRDLINE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with categories 3 x images, 3 x lines, 1 x Custom
     *
     * @group product
     *
     * @return void
     **/
    public function testProduct3xImage3xLine1xCustom()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
                        ->create();
                    $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                } else {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site without categories and 3 x images, 3 x lines, 1 x Custom
     *
     * @group product
     *
     * @return void
     **/
    public function testProductNoCategories3xImage3xLine1xCustom()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
                        ->create();
                    $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                } else {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
                        ->create();
                }
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with categories 3 x images, 3 x lines, 1 x Predefined
     *
     * @group product
     *
     * @return void
     **/
    public function testProduct3xImage3xLine1xPredefined()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site without categories and 3 x images, 3 x lines, 1 x Predefined
     *
     * @group product
     *
     * @return void
     **/
    public function testProductNoCategories3xImage3xLine1xPredefined()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with categories 3 x images, 3 x lines, 1 x Custom, 1x Predefined
     *
     * @group product
     *
     * @return void
     **/
    public function testProduct3xImage3xLine1xCustom1xPredefined()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            PredefinedImage::factory()->withProduct($product)->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
                        ->create();
                } elseif ($i === 1) {
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site without categories and 3 x images, 3 x lines, 1 x Custom, 1 x Predefined
     *
     * @group product
     *
     * @return void
     **/
    public function testProductNoCategories3xImage3xLine1xCustom1xPredefined()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            PredefinedImage::factory()->withProduct($product)->create();
            for ($i = 0; $i < 3; $i++) {
                if ($i === 0) {
                    $customImage = CustomImage::factory()
                        ->doesNotUseMaterials()
                        ->withProduct($product)
                        ->withLayoutCustom()
                        ->create();
                } elseif ($i === 1) {
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
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
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
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site wthout materials but with categories 3 x images, 3 x lines, 1 x Predefined, 1 x Custom but shows both buttons
     *
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testFeaturedProductLinkWorksAndShowPredefinedAndCustomButtonsWithoutMaterials()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->count(3)->withFeaturedProduct()->create();
            foreach ($products as $product) {
                PredefinedImage::factory()->withProduct($product)->create();
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
                            ->withLayoutCustom()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
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
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($products[0])))
            ->waitForText(self::ADDITIONALIMAGE)
            ->waitForText(self::THIRDLINE);
            $browser->waitFor('.text-begin');
            $browser->scrollIntoView('.text-begin')
            ->clickLink(self::VIEWPRODUCT)
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with materials but with categories 3 x images, 3 x lines,both button types set, should show both buttons
     *
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testFeaturedProductLinkWorksAndShowPredefinedAndCustomButtonsMaterials()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->count(3)->withFeaturedProduct()->create();
            foreach ($products as $product) {
                PredefinedImage::factory()->withProduct($product)->create();
                for ($i = 0; $i < 3; $i++) {
                    if ($i === 0) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutPredefined()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                    } else {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutCustom()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
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
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($products[0])))
            ->waitForText(self::ADDITIONALIMAGE)
            ->waitForText(self::THIRDLINE);
            $browser->waitFor('.text-begin');
            $browser->scrollIntoView('.text-begin')
            ->clickLink(self::VIEWPRODUCT)
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('button.btn-choose>span', self::BTNTEXTCHOOSE)
                ->assertSeeIn('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with materials but with categories no images, 3 x lines and hides both buttons
     *
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testFeaturedProductLinkWorksAndDoesntShowButtonsMaterials()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->count(3)->withFeaturedProduct()->create();
            foreach ($products as $product) {
                PredefinedImage::factory()->withProduct($product)->create();
                for ($i = 0; $i < 3; $i++) {
                    $customProductText = CustomProductText::factory()
                        ->withProduct($product)
                        ->create();
                    CustomTextField::factory()
                        ->withLineLengthFromDeceased($deceased)
                        ->withCustomProductText($customProductText)
                        ->create();
                }
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($products[0])))
            ->waitForText(self::THIRDLINE);
            $browser->waitFor('.text-begin');
            $browser->scrollIntoView('.text-begin')
            ->clickLink(self::VIEWPRODUCT)
                ->waitForText(self::THIRDLINE)
                ->assertDontSee('button.btn-choose>span', self::BTNTEXTCHOOSE)
                ->assertDontSee('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with materials but with categories 3 x images, 3 x lines, if a predefined image is not set on one image, but it set on others then the button for predefined should not show
     *
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testFeaturedProductLinkWorksAndDoesntShowPredefinedButtonsMaterialsAndNotAllImagesSet()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->count(3)->withFeaturedProduct()->create();
            foreach ($products as $product) {
                PredefinedImage::factory()->withProduct($product)->create();
                for ($i = 0; $i < 3; $i++) {
                    if ($i === 0) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutPredefined()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                    } elseif ($i === 1) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutPredefined()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                    } elseif ($i === 2) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
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
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($products[0])))
            ->waitForText(self::ADDITIONALIMAGE)
            ->waitForText(self::THIRDLINE);
            $browser->waitFor('.text-begin');
            $browser->scrollIntoView('.text-begin')
            ->clickLink(self::VIEWPRODUCT)
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertDontSee('button.btn-choose>span', self::BTNTEXTCHOOSE);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * No errors in console for product for site with materials but with categories 3 x images, 3 x lines, if a custom image is not set on one image, but it set on others then the button for custom should not show
     *
     * @group haspauseremoved
     *
     * @return void
     **/
    public function testFeaturedProductLinkWorksAndDoesntShowCustomButtonsMaterialsAndNotAllImagesSet()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $products = Product::factory()->withSite($site)->count(3)->withFeaturedProduct()->create();
            foreach ($products as $product) {
                PredefinedImage::factory()->withProduct($product)->create();
                for ($i = 0; $i < 3; $i++) {
                    if ($i === 0) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutCustom()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                    } elseif ($i === 1) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
                            ->withLayoutCustom()
                            ->create();
                        $additional_image = AdditionalImage::factory()->withCustomImage($customImage)->create();
                    } elseif ($i === 2) {
                        $customImage = CustomImage::factory()
                            ->withProduct($product)
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
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($products[0])))
            ->waitForText(self::ADDITIONALIMAGE)
            ->waitForText(self::THIRDLINE);
            $browser->waitFor('.text-begin');
            $browser->scrollIntoView('.text-begin')
            ->clickLink(self::VIEWPRODUCT)
                ->waitForText(self::ADDITIONALIMAGE)
                ->waitForText(self::THIRDLINE)
                ->assertDontSee('button.btn-upload>span', self::BTNTEXTUPLOAD);
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
