<?php

/**
 * Stockage des données après validation d'une FormRequest
 */

namespace App\Store;

abstract class AbstractStore {

    /**
     * Enregistre les données en base de données
     * @return bool
     */
    abstract public function save() : bool;

}
