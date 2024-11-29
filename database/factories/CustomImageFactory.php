<?php

namespace Database\Factories;

use App\Models\CustomImage;
use App\Models\Image;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomImageFactory extends Factory
{
    protected $model = CustomImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'image_id' => Image::factory(),
            'custom_image_id' => null,
            'layout' => 'none',
        ];
    }

    /**
     * remove material.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function doesNotUseMaterials()
    {
        return $this->state(function (array $attributes) {
            return [
                'material_id' => null,
            ];
        });
    }

    /**
     * layout custom.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLayoutCustom()
    {
        return $this->state(function (array $attributes) {
            return [
                'layout' => 'custom',
            ];
        });
    }

    /**
     * layout predfined.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLayoutPredefined()
    {
        return $this->state(function (array $attributes) {
            return [
                'layout' => 'predefined',
            ];
        });
    }

    /**
     * Specifiy product
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withProduct($product)
    {
        return $this->state(function (array $attributes) use ($product) {
            return [
                'product_id' => $product->id,
            ];
        });
    }

    /**
     * Specifiy custom image id
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomImageId($id)
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'custom_image_id' => $id,
            ];
        });
    }

    /**
     * Specifiy material id
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withMaterialId($id)
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'material_id' => $id,
            ];
        });
    }
}
