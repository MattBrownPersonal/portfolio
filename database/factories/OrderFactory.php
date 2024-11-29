<?php

namespace Database\Factories;

use App\Models\Deceased;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'deceased_id' => Deceased::factory(),
            'order_date' => $this->faker->date(),
            'site_id' => Site::factory(),
            'order_number' => $this->faker->randomNumber(7, true),
            'status_id' => 1,
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
            'message' => $this->faker->text(),
            'type' => 'Enquiry - from contact us',
        ];
    }
}
