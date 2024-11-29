<?php

namespace Tests\Feature;

use App\Models\Attributes;
use App\Models\AttributeTypes;
use App\Models\CustomProductText;
use App\Models\Material;
use App\Models\Memorialisations;
use App\Models\PredefinedImage;
use App\Models\Product;
use App\Models\Site;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use stdClass;
use Tests\TestCase;

class ProductTemplateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * a basic product can be saved.
     *
     * @return void
     */
    public function testThatABasicProductCanBeCopied()
    {
        $testData = self::testData();

        $this->assertDatabaseHas('products', [
            'supplier_id' => $testData->oldProduct->supplier_id,
            'name' => $testData->oldProduct->name,
            'description' => $testData->oldProduct->description,
            'price' => $testData->oldProduct->price,
            'POA' => $testData->oldProduct->POA,
            'site_id' => $testData->request->site_id,
            'memorialisation_id' => $testData->oldProduct->memorialisation_id,
            'featured' => $testData->oldProduct->featured,
            'saved' => $testData->oldProduct->saved,
            'draft' => $testData->oldProduct->draft,
            'sku' => $testData->oldProduct->sku,
            'date_layout' => $testData->oldProduct->date_layout,
            'thumbnail_image' => $testData->oldProduct->thumbnail_image,
            'short_description' => $testData->oldProduct->short_description,
        ]);
    }

    /**
     * a product materials can be saved against site
     *
     * @return void
     */
    public function testThatAProductMaterialsCanBeSavedAgainstSite()
    {
        $testData = self::testData();

        Material::factory()->withProduct($testData->oldProduct)->create();

        $productMaterials = Product::saveProductMaterials($testData->oldProduct, $testData->newProduct);

        $this->assertDatabaseHas('materials', [
            'type' => $productMaterials->type,
            'imageurl' => $productMaterials->imageurl,
            'product_id' => $testData->newProduct->id,
            'price' => $productMaterials->price,
            'visible' => $productMaterials['visible'],
        ]);
    }

    /**
     * a product attributes can be saved
     *
     * @return void
     */
    public function testThatAProductAttributesCanBeSaved()
    {
        $testData = self::testData();

        $attributeTypes = AttributeTypes::factory()->withAttributeName('Type 1')->count(1)->withProduct($testData->oldProduct)->create();

        foreach ($attributeTypes as $attributeType) {
            Attributes::factory()->withAttributeType($attributeType)->count(3)->create();
        }

        $attributes = Product::saveProductAttributes($testData->oldProduct, $testData->newProduct);

        $this->assertDatabaseHas('attribute_types', [
            'name' => $attributes[0]->name,
            'product_id' => $testData->newProduct->id,
            'visible' => $attributes[0]['visible'],
        ]);

        $this->assertDatabaseHas('attributes', [
            'attribute_types_id' => $attributeTypes[0]->id,
            'name' => $attributes[1]->name,
            'price' => $attributes[1]->price,
            'visible' => $attributes[1]['visible'],
        ]);
    }

    /**
     * a product custom product texts can be saved
     *
     * @return void
     */
    public function testThatAProductCustomProductTextsCanBeSaved()
    {
        $testData = self::testData();

        $customTexts = CustomProductText::factory()->withProduct($testData->oldProduct)->withLineType('Firstname')->withCustomMessageText('FakeName')->count(3)->create();

        Product::saveProductTexts($testData->oldProduct, $testData->newProduct);

        $this->assertDatabaseHas('custom_product_texts', [
            'product_id' => $testData->newProduct->id,
            'line_types' => $customTexts[0]->line_types,
            'custom_index' => $customTexts[0]->custom_index,
            'is_custom' => $customTexts[0]->is_custom,
            'custom_message_text' => $customTexts[0]->custom_message_text,
            'removed' => $customTexts[0]->removed,
            'order_index' => $customTexts[0]->order_index,
        ]);
    }

    /**
     * a product predefined image can be saved
     *
     * @return void
     */
    public function testThatAProductPredefinedCanBeSaved()
    {
        $testData = self::testData();

        $predefinedImages = PredefinedImage::factory()->withProduct($testData->oldProduct)->withPrice(100)->withImageUrl('fakeurl')->count(3)->create();

        Product::savePredefinedImages($testData->oldProduct, $testData->newProduct);

        $this->assertDatabaseHas('predefined_images', [
            'name' => $predefinedImages[0]->name,
            'imageurl' => $predefinedImages[0]->imageurl,
            'product_id' => $testData->newProduct->id,
            'visible' => $predefinedImages[0]['visible'],
            'price' => $predefinedImages[0]->price,
        ]);
    }

    private function testData()
    {
        $site = Site::factory()->create();
        $oldProduct = Product::factory()
            ->withSite($site)
            ->withDateLayout()
            ->withShortDescription('Short Description')
            ->create();
        $category = Memorialisations::factory()->create();

        $request = new stdClass();
        $request->product_id = $oldProduct->id;
        $request->site_id = $site->id;
        $request->category_id = $category->id;

        $newProduct = Product::saveProductCopy($request, $oldProduct);

        $data = new stdClass();
        $data->site = $site;
        $data->oldProduct = $oldProduct;
        $data->category = $category;
        $data->request = $request;
        $data->newProduct = $newProduct;

        return $data;
    }
}
