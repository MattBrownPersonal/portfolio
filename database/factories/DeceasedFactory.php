<?php

namespace Database\Factories;

use App\Models\Deceased;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeceasedFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Deceased::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lastName = 'Lasten'; // faker removed due to long names

        return [
            'firstname' => 'Firstus',
            'middlename' => 'midi',
            'lastname' => $lastName,
            'date_of_birth' => $this->faker->date(),
            'date_of_death' => $this->faker->date(),
            'code' => $this->faker->ean8(),
            'link_emailed' => 0,
            'link_printed' => 0,
            'times_qr_used' => 0,
            'landing_code' => Deceased::getUniqueCode(),
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
