<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text($maxNbChars = 20),
            'url' => null,
            'description' => $this->faker->text($maxNbChars = 200),
            'article' => $this->faker->text($maxNbChars = 100),
            'image' => '/images/heart.png',
        ];
    }
}
