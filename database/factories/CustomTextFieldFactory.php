<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomTextFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => 'fullname',
            'custom_image_id' => 1,
            'custom_product_text_id' => 1,
            'lineType' => 'straight',
            'left' => 350,
            'top' => 174,
            'fontSize' => 20,
            'angle' => 0,
            'radius' => 1000,
            'centerRotation' => 0,
            'arc' => 2,
            'rectangleX' => 215,
            'rectangleY' => 50,
            'rectangleWidth' => 230,
            'rectangleHeight' => 30,
            'rectangleTextScale' => 1,
        ];
    }

    /**
     * Specifiy custom image reference
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

    /**
     * Specifiy custom product text reference
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomProductText($custom_product_text)
    {
        return $this->state(function (array $attributes) use ($custom_product_text) {
            return [
                'custom_product_text_id' => $custom_product_text,
            ];
        });
    }

    /**
     * Specifiy line character count based on deceased name
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLineLengthFromDeceased($deceased)
    {
        $letterCount = strlen($deceased->firstname) + strlen($deceased->middlename) + strlen($deceased->lastname) + 5;

        return $this->state(function () use ($letterCount) {
            return [
                'letterCount' => $letterCount,
            ];
        });
    }

    /**
     * Specifiy line character count
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLineLength($letterCount)
    {
        return $this->state(function () use ($letterCount) {
            return [
                'letterCount' => $letterCount,
            ];
        });
    }

    /**
     * Specifiy line type
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLineType($type)
    {
        return $this->state(function () use ($type) {
            return [
                'type' => $type,
            ];
        });
    }
}
