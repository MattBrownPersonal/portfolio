<?php

namespace Database\Factories;

use App\Models\SiteStyles;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteStylesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SiteStyles::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return
            [
                'primary_colour' => '#313f54',
                'secondary_colour' => '#d9a137',
                'primary_font_colour' => $this->faker->hexcolor(),
                'secondary_font_colour' => $this->faker->hexcolor(),
                'image_id' => null,
            ];
    }

    /**
     * Specifiy site ID
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withSiteId($site)
    {
        return $this->state(function () use ($site) {
            return [
                'site_id' => $site->id,
            ];
        });
    }
}
