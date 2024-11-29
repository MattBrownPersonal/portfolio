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

class VM8809DoNotShowBrsTest extends FrontEndTestCase
{
    const BTNTEXTCHOOSE = 'Choose From Our Selection';

    const VIEWPRODUCT = 'View Product';

    /**
     * Html characters should not be double encoded.
     * Product page with featured products
     *
     * @group vm809
     *
     * @return void
     **/
    public function testProductDescriptionsAreNotDoubleEncoded()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $description = 'FULLD0:: test 1, <br>, test 2, </br>, test 3, </ br>, test 4 <b>bold</b>';
            $shortDescription = 'SHORTD0:: t 1, <br>, t 2, </br>, t 3, </ br>, t 4 <b>bld</b>';
            $product = Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->create();
            // add a featured product
            Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->withFeaturedProduct()
                ->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
                // wait for description loaded
                ->waitForText('FULLD0')
                // assert no <br> tags visible
                ->assertDontSee('<br>')
                ->assertDontSee('</br>')
                ->assertDontSee('</ br>');
            // scroll down to featured products
            $browser->scrollIntoView('.featured-products-container')
                // wait for descriptions loaded
                ->waitForText('SHORTD0')
                // assert no <br> tags visible
                ->assertDontSee('<br>')
                ->assertDontSee('</br>')
                ->assertDontSee('</ br>');
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Html characters should not be double encoded.
     * Home page
     *
     * @group vm809
     *
     * @return void
     **/
    public function testHomePageDescriptionsAreNotDoubleEncoded()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $description = 'FULLD0:: test 1, <br>, test 2, </br>, test 3, </ br>, test 4 <b>bold</b>';
            $shortDescription = 'SHORTD0:: t 1, <br>, t 2, </br>, t 3, </ br>, t 4 <b>bld</b>';
            $product = Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->create();
            // add a featured product
            Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->withFeaturedProduct()
                ->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
            $browser->visit($this->customerUrl($link.$this->getHomePath()))
                // wait for descriptions loaded
                ->waitForText('SHORTD0')
                // assert no <br> tags visible
                ->assertDontSee('<br>')
                ->assertDontSee('</br>')
                ->assertDontSee('</ br>');
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Html characters should not be double encoded.
     * Products page
     *
     * @group vm809
     *
     * @return void
     **/
    public function testMemorialDescriptionsAreNotDoubleEncoded()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $description = 'FULLD0:: test 1, <br>, test 2, </br>, test 3, </ br>, test 4 <b>bold</b>';
            $shortDescription = 'SHORTD0:: t 1, <br>, t 2, </br>, t 3, </ br>, t 4 <b>bld</b>';
            $product = Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->create();
            // add a featured product
            Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->withFeaturedProduct()
                ->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
            $browser->visit($this->customerUrl($link.$this->getMemorialsPath()))
                // wait for descriptions loaded
                ->waitForText('SHORTD0')
                // assert no <br> tags visible
                ->assertDontSee('<br>')
                ->assertDontSee('</br>')
                ->assertDontSee('</ br>');
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Html characters should not be double encoded.
     * Categories page
     *
     * @group vm809
     *
     * @return void
     **/
    public function testCategoriesDescriptionsAreNotDoubleEncoded()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $description = 'FULLD0:: test 1, <br>, test 2, </br>, test 3, </ br>, test 4 <b>bold</b>';
            $shortDescription = 'SHORTD0:: t 1, <br>, t 2, </br>, t 3, </ br>, t 4 <b>bld</b>';
            $product = Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->create();
            // add a featured product
            Product::factory()
                ->withSite($site)
                ->withDescription($description)
                ->withShortDescription($shortDescription)
                ->withFeaturedProduct()
                ->create();
            PredefinedImage::factory()->withProduct($product)->create();
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
            $browser->visit($this->customerUrl($link.$this->getMemorialsPath()))
                // wait for descriptions loaded
                ->waitForText('SHORTD0')
                // assert no <br> tags visible
                ->assertDontSee('<br>')
                ->assertDontSee('</br>')
                ->assertDontSee('</ br>');
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
