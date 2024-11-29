<?php

namespace Database\Factories;

use App\Models\Memorialisations;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemorialisationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Memorialisations::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}
