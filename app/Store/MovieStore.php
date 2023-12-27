<?php

/**
 * Stockage d'un film
 */

namespace App\Store;

use App\Http\Requests\Movie\AbstractMovieRequest;

final class MovieStore extends AbstractStore {

    /**
     * Constructeur
     * @param AbstractMovieRequest $request
     */
    public function __construct(private AbstractMovieRequest $request)
    {

    }

    /**
     * Enregistre les données en base de données
     * @return bool
     */
    public function save() : bool
    {
        $request = $this->request;

        $movie = $request->getMovie();
        $data = collect($request->validated())->only([
            'public_id',
            'name',
            'produced_at',
        ])->all();

        $movie->fill($data);

        return $movie->save();
    }

}
