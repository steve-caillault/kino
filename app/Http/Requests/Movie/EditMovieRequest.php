<?php

/**
 * Requête d'édition d'un film
 */

namespace App\Http\Requests\Movie;

use App\Models\Movie;

final class EditMovieRequest extends AbstractMovieRequest
{

    /**
     * Retourne la salle de cinéma gérée
     * @return Movie
     */
    public function getMovie() : Movie
    {
        return ($this->movie ??= $this->route('movie'));
    }

}
