<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Site::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'Fake',
            'slug' => 'test-slug',
            'obitus_site_id' => 'ABC123',
            'account_type' => 'obitus',
            'site_type' => 'Crematorium',
            'operator_type' => 'Council',
            'uses_categories' => 1,
            'has_orphaned_products' => 0,
        ];
    }

    /**
     * Set site to not use categories.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function doesNotUseCategories()
    {
        return $this->state(function (array $attributes) {
            return [
                'uses_categories' => 0,
            ];
        });
    }

    /**
     * set email to use test email.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withTestEmailAddress()
    {
        return $this->state(function () {
            return [
                'primary_contact_email' => 'is_test@example.com',
            ];
        });
    }
}
