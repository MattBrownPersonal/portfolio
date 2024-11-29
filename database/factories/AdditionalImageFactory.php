<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AdditionalImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'left' => 300,
            'top' => 200,
            'height' => 100,
            'width' => 60,
            'rotation' => 2,
            'type' => 'rectangle',
        ];
    }

    /**
     * set as Ellipse.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function asEllipse()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'ellipse',
            ];
        });
    }

    /**
     * Specifiy custom_image
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomImage($custom_image)
    {
        return $this->state(function (array $attributes) use ($custom_image) {
            return [
                'custom_image_id' => $custom_image->id,
            ];
        });
    }
}
