<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => 'test',
            'imageurl' => '/someimage.png',
            'price' => 9999,
            'visible' => 1,
            'product_id' => Product::factory(),
        ];
    }

    /**
     * Specifiy product id
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withProduct($product)
    {
        return $this->state(function () use ($product) {
            return [
                'product_id' => $product->id,
            ];
        });
    }
}
