<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PredefinedImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'imageurl' => '',
            'product_id' => null,
            'price' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 9999),
            'visible'=>1,
        ];
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
     * Image url
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withImageUrl($url)
    {
        return $this->state(function (array $attributes) use ($url) {
            return [
                'imageurl' => $url,
            ];
        });
    }
}
