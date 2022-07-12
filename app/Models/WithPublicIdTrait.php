<?php 

/**
 * Gestion d'un modèle utilisant un identifiant public
 */

namespace App\Models;

/**
 * @var string $public_id Identifiant public
 */
trait WithPublicIdTrait {

    /**
     * Chargement d'un modèle en fonction de son identifiant public
     * @param string $publicId
     * @return self
     */
    public static function findByPublicId(string $publicId) : ?self
    {
        return (new self())->newQuery()->where('public_id', $publicId)->first();
    }

}