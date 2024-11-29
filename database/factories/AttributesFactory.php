<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttributesFactory extends Factory
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
            'price' => 150,
            'name' => $this->faker->word(),
        ];
    }

    /**
     * Specifiy product
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withAttributeType($attributeType)
    {
        return $this->state(function () use ($attributeType) {
            return [
                'attribute_types_id' => $attributeType->id,
            ];
        });
    }
}
