<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailVerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->email(),
            'email_hash' => 'FakeEmailHash',
            'verified' => false,
        ];
    }

    /**
     * Specifiy email (it is your job to also set the email_hash)
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withEmail($email)
    {
        return $this->state(function () use ($email) {
            return [
                'email' => $email,
            ];
        });
    }

    /**
     * Specifiy email_hash
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withEmailHash($hash)
    {
        return $this->state(function () use ($hash) {
            return [
                'email_hash' => $hash,
            ];
        });
    }

    /**
     * Indicate that the model's email address should be verified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function asVerified()
    {
        return $this->state(function (array $attributes) {
            return [
                'verified' => true,
            ];
        });
    }
}
