<?php

/**
 * Classe pour récupérer une instance de Faker
 */

namespace Tests;

use Faker\{
    Factory as FakerFactory,
    Generator
};

final class Faker {

    /**
     * Instance de Faker
     * @var ?Generator
     */
    private static ?Generator $faker = null;

    /**
     * Retourne l'instance
     * @return Generator
     */
    public function get() : Generator
    {
        if(self::$faker === null)
        {
            self::$faker = FakerFactory::create();
        }
        return self::$faker;
    }
}
