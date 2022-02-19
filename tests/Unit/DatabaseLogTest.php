<?php

/**
 * Tests d'enregistrement de log en base de données avec Monolog
 */

namespace Tests\Command\Unit;

use Illuminate\Support\Facades\Log;
/***/
use Tests\TestCase;
use App\Models\Log as LogModel;

final class DatabaseLogTest extends TestCase
{

    /**
     * Retourne le dernier log enregistré 
     * @return ?LogModel
     */
    private function getLastDatabaseLog() : ?LogModel
    {
        return LogModel::orderBy('id', 'desc')->first();
    }

    /**
     * Création d'un utilisateur avec succès
     * @param string $level Niveau d'urgence
     * @param string $message Message du log
     * @dataProvider provider
     * @return void
     */
    public function test(string $level, string $message) : void
    {
        $currentDate = (new \DateTimeImmutable());
        // Modifie l'heure à cause des microsecondes
        $currentDate = $currentDate->setTime(
            (int) $currentDate->format('G'), 
            (int) $currentDate->format('i'),
            (int) $currentDate->format('s'),
            0
        );

        with(Log::channel('database'))->{ $level }($message);

        // Création du log
        $lastLog = $this->getLastDatabaseLog();
        $lastLogDate = ($lastLog !== null) ? new \DateTimeImmutable($lastLog->created_at) : null;

        // Vérification de la date
        $this->assertTrue($lastLogDate >= $currentDate);

        // Vérification du niveau et du message
        $this->assertEquals([
            'level' => strtoupper($level),
            'message' => $message,
        ], [
            'level' => $lastLog->level,
            'message' => $lastLog->message,
        ]);
    }

    /**
     * Provider pour les tests
     * @return array
     */
    public function provider() : array
    {
        $faker = $this->getFaker();

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