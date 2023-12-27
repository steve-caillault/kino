<?php

/**
 * Requête de la création d'un film
 */

namespace App\Http\Requests\Movie;

use App\Http\Requests\Movie\AbstractMovieRequest;
use App\Models\Movie;

final class CreateMovieRequest extends AbstractMovieRequest
{

    /**
     * Retourne la salle de cinéma gérée
     * @return Movie
     */
    public function getMovie() : Movie
    {
        return ($this->movie ??= new Movie());
    }

}
