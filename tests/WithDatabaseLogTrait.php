<?php

/**
 * Trait pour les tests vérifiant les logs
 */

namespace Tests;

use App\Models\Log;

trait WithDatabaseLogTrait {

    /**
     * Retourne le dernier log enregistré 
     * @return ?Log
     */
    private function getLastDatabaseLog() : ?Log
    {
        return Log::orderBy('id', 'desc')->first();
    }

    /**
     * Vérifie que le dernier log en base de données correspont aux paramètres
     * @param string $level
     * @param string $message
     * @param \DateTimeImmutable $afterDateExpected Date après laquelle le log doit être créé
     * @return void
     */
    private function checkLogAfter(string $level, string $message, \DateTimeImmutable $afterDateExpected) : void
    {
        // Modifie l'heure à cause des microsecondes
        $afterDateExpected = $afterDateExpected->setTime(
            (int) $afterDateExpected->format('G'), 
            (int) $afterDateExpected->format('i'),
            (int) $afterDateExpected->format('s'),
            0
        );

        // Récupération du log du log
        $lastLog = $this->getLastDatabaseLog();
        $lastLogDate = ($lastLog !== null) ? new \DateTimeImmutable($lastLog->created_at) : null;

        // Vérification de la date
        $this->assertTrue($lastLogDate >= $afterDateExpected);

        // Vérification du niveau et du message
        $this->assertEquals([
            'level' => strtoupper($level),
            'message' => $message,
        ], [
            'level' => $lastLog->level,
            'message' => $lastLog->message,
        ]);
    }

}