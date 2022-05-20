<?php

namespace Modules\Otp\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Otp\Entities\Otp;

class OtpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Otp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'phone_number' => $this->faker->numerify('+989#########'),
            'code' => $this->faker->randomNumber(6, true),
        ];
    }

    /**
     * Append specified phone number to factory.
     *
     * @param string $phone_number
     * @return $this
     */
    public function setPhoneNumber(string $phone_number): static
    {
        return $this->state(['phone_number' => $phone_number]);
    }
}

