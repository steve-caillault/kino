<?php

/**
 * Tests d'enregistrement de log en base de données avec Monolog
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
/***/
use Tests\{
    TestCase,
    WithDatabaseLogTrait
};

final class DatabaseLogTest extends TestCase
{
    use WithDatabaseLogTrait;

    /**
     * Création d'un log en base de données avec succès
     * @param string $level Niveau d'urgence
     * @param string $message Message du log
     * @dataProvider provider
     * @return void
     */
    public function testAdd(string $level, string $message) : void
    {
        $currentDate = (new \DateTimeImmutable());
        with(Log::channel('database'))->{ $level }($message);
        $this->checkLogAfter(strtoupper($level), $message, $currentDate);
    }

    /**
     * Provider pour les tests
     * @return array
     */
    public static function provider() : array
    {
        $faker = self::getFaker();

        return [
            'emergency' => [
                'emergency', $faker->text(),
            ],
            'alert' => [
                'alert', $faker->text(),
            ],
            'critical' => [
                'critical', $faker->text(),
            ],
            'error' => [
                'error', $faker->text(),
            ],
            'warning' => [
                'warning', $faker->text(),
            ],
            'notice' => [
                'notice', $faker->text(),
            ],
            'info' => [
                'info', $faker->text(),
            ],
            'debug' => [
                'debug', $faker->text(),
            ],
        ];
    }
}
