<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeTypesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'visible' => 1,
        ];
    }

    /**
     * Specifiy product ID
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

    /**
     * Specifiy Attribute Name
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAttributeName($name)
    {
        return $this->state(function () use ($name) {
            return [
                'name' => $name,
            ];
        });
    }
}
