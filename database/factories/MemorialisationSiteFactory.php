<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MemorialisationSiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'memorialisation_id' => 2,
            'site_id' => 1,
            'image' => '/memorialisation/urn.jpg',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse elit elit, commodo a diam eu, rhoncus efficitur libero. Aliquam interdum bibendum posuere. Pellentesque vel felis quis neque tempus convallis in sit amet lectus. Phasellus eu arcu lobortis, viverra elit vel, porta metus. ',
        ];
    }
}
