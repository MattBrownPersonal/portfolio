<?php

namespace Database\Factories;

use App\Models\Site;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'name' => 'Fake Product',
            'description' => $this->faker->text($maxNbChars = 200),
            'price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99999),
            'site_id' => Site::factory(),
            'memorialisation_id' => null,
            'saved' => 0,
            'featured' => 0,
            'draft' => 0,
            'sku' => 'SKU-FAKE',
            'thumbnail_image' => '/images/heart.png',
            'short_description' => $this->faker->text($maxNbChars = 20),
            'POA' => 0,
        ];
    }

    /**
     * Specifiy featured product
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withFeaturedProduct()
    {
        return $this->state(function () {
            return [
                'featured' => 1,
            ];
        });
    }

    /**
     * Specifiy site
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSite($site)
    {
        return $this->state(function () use ($site) {
            return [
                'site_id' => $site->id,
            ];
        });
    }

    /**
     * Specifiy main description
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withDescription($text)
    {
        return $this->state(function () use ($text) {
            return [
                'description' => $text,
            ];
        });
    }

    /**
     * Specifiy short description
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withShortDescription($text)
    {
        return $this->state(function () use ($text) {
            return [
                'short_description' => $text,
            ];
        });
    }

    /**
     * Specifiy price
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPrice($price)
    {
        return $this->state(function (array $attributes) use ($price) {
            return [
                'price' => $price,
            ];
        });
    }

    /**
     * Specifiy date_layout
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withDateLayout()
    {
        return $this->state(function () {
            return [
                'date_layout' => 'MMM Do YY',
            ];
        });
    }
}
