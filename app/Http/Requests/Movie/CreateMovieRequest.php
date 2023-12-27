<?php

/**
 * Requête de la création d'un film
 */

namespace App\Http\Requests\Movie;

use App\Models\Movie;

final class CreateMovieRequest extends AbstractMovieRequest
{

    /**
     * Retourne le film géré
     * @return Movie
     */
    public function getMovie() : Movie
    {
        return ($this->movie ??= new Movie());
    }

}
