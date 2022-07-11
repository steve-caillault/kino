<?php

/**
 * Requête de la création d'une salle de cinéma
 */

namespace App\Http\Requests\MovieRoom;

use App\Models\MovieRoom;

final class CreateMovieRoomRequest extends AbstractMovieRoomRequest
{

    /**
     * Retourne la salle de cinéma gérée
     * @return MovieRoom
     */
    public function getMovieRoom() : MovieRoom
    {
        return $this->movieRoom ??= new MovieRoom();
    }

}
