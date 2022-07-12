<?php

/**
 * Requête de l'édition d'une salle de cinéma
 */

namespace App\Http\Requests\MovieRoom;

use App\Models\MovieRoom;

final class EditMovieRoomRequest extends AbstractMovieRoomRequest
{

    /**
     * Retourne la salle de cinéma gérée
     * @return MovieRoom
     */
    public function getMovieRoom() : MovieRoom
    {
        return $this->movieRoom ??= MovieRoom::findByPublicId($this->route('movieRoomPublicId'));
    }

}
