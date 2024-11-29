<?php

namespace Tests\Browser\FrontEnd;

use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteStyles;
use Laravel\Dusk\Browser;

/**
 * Test product with varried character counts across lines and product images
 */
class ProductCharacterCountTest extends FrontEndTestCase
{
    const FIRSTLINE = 'Line 1';

    const THIRDLINE = 'Line 3';

    const COUNTEXCEED = 'This line must be less than';

    /**
     * Exceeding character count shows error
     *
     * @group skip_charactercount
     *
     * @return void
     **/
    public function testCharacterCountExceededResultsInError()
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
                    ->withLineType('fullname')
                    ->create();
                CustomTextField::factory()
                    ->withLineLength(2)
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->create();
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText(self::THIRDLINE)
                ->assertSeeIn('.v-messages.theme--light.error--text', self::COUNTEXCEED);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Not exceeding character count shows no error
     *
     * @group skip_charactercount
     *
     * @return void
     **/
    public function testCharacterCountNotExceededResultsInNoError()
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
                    ->withLineType('fullname')
                    ->create();
                CustomTextField::factory()
                    ->withLineLength(2000)   // unlikey to be exceeded
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->create();
            }
            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText(self::THIRDLINE)
                ->assertDontSee(self::COUNTEXCEED);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Changing slides also changes character count (1st slide)
     *
     * @group skip_charactercount
     *
     * @return void
     **/
    public function testCharacterCountUpdatedWith1stSlide()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $message = 'abcdefghijklmnopqrstuvwxyz'; // 26 characters long
            // create a single custom product text
            $customProductText = CustomProductText::factory()
                ->withProduct($product)
                ->withCustomMessageText($message)
                ->withCustomText()
                ->create();
            $lengths = [10, 5, 50];
            // create three product images and assign the custom product text and a custom text field
            for ($i = 0; $i < 3; $i++) {
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
                CustomTextField::factory()
                    ->withLineLength($lengths[$i])
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->create();
            }

            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText(self::FIRSTLINE)
                ->assertSeeIn('.v-messages.theme--light.error--text', self::COUNTEXCEED);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Changing slides also changes character count (2nd slide)
     *
     * @group charactercount
     *
     * @return void
     **/
    public function testCharacterCountUpdatedWith2ndSlide()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $message = 'abcdefghijklmnopqrstuvwxyz'; // 26 characters long
            // create a single custom product text
            $customProductText = CustomProductText::factory()
                ->withProduct($product)
                ->withCustomMessageText($message)
                ->withCustomText()
                ->create();
            CustomProductText::factory()
                ->withProduct($product)
                ->create();
            $lengths = [10, 5, 50];
            // create three product images and assign the custom product text and a custom text field
            for ($i = 0; $i < 3; $i++) {
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
                CustomTextField::factory()
                    ->withLineLength($lengths[$i])
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->withLineType('custom line 1')
                    ->create();
            }

            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText(self::FIRSTLINE)
                ->press("button[aria-label='Next Image']")
                ->waitForText(strlen($message).' / '.$lengths[1])
                ->assertSeeIn('.v-messages.theme--light.error--text', self::COUNTEXCEED);
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Changing slides also changes character count (3rd slide)
     *
     * @group charactercount
     *
     * @return void
     **/
    public function testCharacterCountUpdatedWith3rdSlide()
    {
        $this->browse(function (Browser $browser) {
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $siteStyle = SiteStyles::factory()->withSiteId($site)->create();
            $link = $this->getDeceasedLink($deceased);
            $product = Product::factory()->create();
            $message = 'abcdefghijklmnopqrstuvwxyz'; // 26 characters long
            $customProductText = CustomProductText::factory()
                ->withProduct($product)
                ->withCustomMessageText($message)
                ->withCustomText()
                ->create();
            $customProductText2 = CustomProductText::factory()
                ->withProduct($product)
                ->create();
            $lengths = [10, 5, 50];
            // create three product images and assign the custom product text and a custom text field
            for ($i = 0; $i < 3; $i++) {
                $customImage = CustomImage::factory()
                    ->doesNotUseMaterials()
                    ->withProduct($product)
                    ->create();
                CustomTextField::factory()
                    ->withLineLength($lengths[$i])
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText)
                    ->withLineType('custom line 1')
                    ->create();
                CustomTextField::factory()
                    ->withLineLength(100)
                    ->withCustomImage($customImage)
                    ->withCustomProductText($customProductText2)
                    ->withLineType('custom line 2')
                    ->create();
            }

            $browser->visit($this->customerUrl($link.$this->getProductPath($product)))
                ->waitForText(self::FIRSTLINE)
                ->press("button[aria-label='Next Image']")
                ->waitForText(strlen($message).' / '.$lengths[1])
                ->press("button[aria-label='Next Image']")
                ->waitForText(strlen($message).' / '.$lengths[2])
                ->assertDontSee(self::COUNTEXCEED);
            $this->assertNoErrorsInConsole($browser);
        });
    }
}
