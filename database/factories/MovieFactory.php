<?php

/**
 * Factory pour la création d'un film
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        $productionDateTime = $this->faker->dateTimeBetween(startDate: '1895-12-28');

        return [
            'public_id' => substr($this->faker->slug(3), 0, 100),
            'name' => substr($this->faker->name(), 0, 100),
            'produced_at' => $productionDateTime,
        ];
    }
}
