<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class AdminUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nickname' => $this->faker->userName(),
            'email' => $this->faker->email(),
            'password' => $this->faker->password(),
        ];
    }
}
