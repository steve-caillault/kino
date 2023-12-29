<?php

/**
 * Factory pour la création d'une salle de cinéma
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class MovieRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() : array
    {
        $totalPlaces = $this->faker->numberBetween(20, 1000);

        return [
            'public_id' => substr($this->faker->slug(3), 0, 25),
            'name' => substr($this->faker->name(), 0, 25),
            'floor' => $this->faker->numberBetween(-10, 10),
            'nb_places' => $totalPlaces,
            'nb_handicap_places' => $this->faker->numberBetween(20, $totalPlaces),
        ];
    }
}
