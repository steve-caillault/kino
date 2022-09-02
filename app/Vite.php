<?php

/**
 * Classe utilitaire pour l'utilisation de Vite
 * @see https://vitejs.dev
 * @see https://laravel.com/docs/9.x/vite
 */

namespace App;

use Illuminate\Support\Facades\Log;
use Innocenzi\Vite\Vite as LaravelVite;

final class Vite {

    /**
     * Retourne l'URL générée par Vite pour le fichier dont le chemin est en paramètre
     * @param string $path Chemin du fichier (doit correspondre à une clé du fichier /public/build/manifest.json)
     * @return ?string URL de fichier si l'entrée existe
     */
    public function getAssetUrl(string $path) : ?string
    {
        $url = null;

        try {
            $entry = app()->make(LaravelVite::class)->getEntry($path);
            return $entry->asset($entry->file);
        } catch(\Throwable) {
            $logMessageTemplate = 'Une erreur s\'est produite lors de la récupération du fichier %s.';
            $logMessage = sprintf($logMessageTemplate, $path);
            Log::debug($logMessage);
        }
    
        return $url;
    }

}