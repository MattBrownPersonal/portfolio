<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'price' => 100,
            'status_id' => 1,
            'image_url' => null,
            'item_type' => 'product',
            'attribute_type' => 'product',
            'attribute_name' => $this->faker->word(),
            'product_name' => $this->faker->word(),
            'supplier_name' => $this->faker->company(),
        ];
    }
}
