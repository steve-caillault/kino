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
            'public_id' => $this->faker->slug(20),
            'name' => $this->faker->name(),
            'produced_at' => $productionDateTime,
        ];
    }
}
