<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QueueEmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'to_email' => $this->faker->email(),
            'email_body' => '',
            'email_subject' => '',
            'email_json' => '',
            'email_type' => '',
            'sent'  => false,
        ];
    }

    /**
     * Specifiy emailVerification
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withEmailVerification($emailVerification)
    {
        return $this->state(function () use ($emailVerification) {
            return [
                'email_verification_id' => $emailVerification->id,
            ];
        });
    }

    /**
     * Specifiy deceased
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withDeceased($deceased)
    {
        return $this->state(function () use ($deceased) {
            return [
                'deceased_id' => $deceased->id,
            ];
        });
    }
}
