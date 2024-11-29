<?php

namespace Tests\Browser;

use App\Models\AdditionalImage;
use App\Models\Attributes;
use App\Models\AttributeTypes;
use App\Models\CustomImage;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\Deceased;
use App\Models\PredefinedImage;
use App\Models\Product;
use App\Models\Site;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Admin\AdminTestCase;

class ProductTest extends AdminTestCase
{
    /**
     * Save Product As Template
     *
     * @return void
     * @group saveAsTemplate
     */
    public function testThatAPlainProductCanBeSavedAsTemplate()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::factory()->create();
            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/products/{$product->id}/{$product->site_id}"))
                ->waitForText('Product Details')
                ->assertSee('Product Details')
                ->press('SAVE AS TEMPLATE')
                ->waitForText('SAVED AS TEMPLATE')
                ->assertSee('SAVED AS TEMPLATE');
            $this->assertNoErrorsInConsole($browser);
        });
    }

    /**
     * Save Product As Template
     *
     * @return void
     * @group saveAsTemplateWithAttributes
     */
    public function testThatAProductWithAttributesCanBeSavedAsTemplate()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::factory()->create();
            $attributeTypes = AttributeTypes::factory()->withAttributeName('Type 1')->withProduct($product)->count(3)->create();

            foreach ($attributeTypes as $attributeType) {
                $attributes = Attributes::factory()->withAttributeType($attributeType)->create();
            }

            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/products/{$product->id}/{$product->site_id}"))
                ->waitForText('Product Details')
                ->assertSee('Product Details')
                ->press('SAVE AS TEMPLATE')
                ->waitForText('SAVED AS TEMPLATE')
                ->assertSee('SAVED AS TEMPLATE');
        });
    }

    /**
     * Save Product As Template
     *
     * @return void
     * @group saveAsTemplateWithAttributesAndImage
     */
    public function testThatAProductWithAttributesAndImageCanBeSavedAsTemplate()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::factory()->create();
            $site = Site::factory()->doesNotUseCategories()->create();
            $deceased = Deceased::factory()->withSiteId($site)->create();
            $attributeTypes = AttributeTypes::factory()->withAttributeName('Type 1')->withProduct($product)->count(3)->create();

            foreach ($attributeTypes as $attributeType) {
                $attributes = Attributes::factory()->withAttributeType($attributeType)->create();
            }

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

            $adminUser = User::factory()->create();
            $adminUser->roles()->attach(1);

            $this->loginAs($browser, $adminUser->email, 'password');

            $browser->visit($this->adminUrl("/products/{$product->id}/{$product->site_id}"))
                ->waitForText('Product Details')
                ->assertSee('Product Details')
                ->press('SAVE AS TEMPLATE')
                ->waitForText('SAVED AS TEMPLATE')
                ->assertSee('SAVED AS TEMPLATE');
        });
    }
}
