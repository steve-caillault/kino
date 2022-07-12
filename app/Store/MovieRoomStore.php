<?php

/**
 * Stockage d'une salle de cinéma
 */

namespace App\Store;

use App\Http\Requests\MovieRoom\AbstractMovieRoomRequest;

final class MovieRoomStore extends AbstractStore {

    /**
     * Constructeur
     * @param AbstractMovieRoomRequest $request
     */
    public function __construct(private AbstractMovieRoomRequest $request)
    {

    }

    /**
     * Enregistre les données en base de données
     * @return bool
     */
    public function save() : bool
    {
        $request = $this->request;

        $movieRoom = $request->getMovieRoom();
        $data = $request->validated();

        $movieRoom->fill($data);

        return $movieRoom->save();
    }

}
