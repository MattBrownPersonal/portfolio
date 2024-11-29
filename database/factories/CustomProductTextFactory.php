<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomProductTextFactory extends Factory
{
    private static $order = 1;

    private static $customIndex = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'line_types' => 'custom line 1',
            'custom_index' => self::$customIndex++,
            'is_custom' => 0,
            'removed' => 0,
            'order_index' => self::$order++,
            'product_id' => Product::factory(),
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
     * Specifiy line type
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withLineType($line_type)
    {
        return $this->state(function (array $attributes) use ($line_type) {
            return [
                'line_types' => $line_type,
            ];
        });
    }

    /**
     * Specifiy message
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomMessageText($message)
    {
        return $this->state(function () use ($message) {
            return [
                'custom_message_text' => $message,
            ];
        });
    }

    /**
     * Specifiy message
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomText()
    {
        return $this->state(function () {
            return [
                'is_custom' => 1,
            ];
        });
    }
}
