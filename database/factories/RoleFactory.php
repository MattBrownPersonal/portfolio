<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            ['id' => 1, 'name' => 'superuser'],
            ['id' => 2, 'name' => 'staff'],
            ['id' => 3, 'name' => 'siteadmin'],
            ['id' => 4, 'name' => 'sitestaff'],
        ];
    }
}
