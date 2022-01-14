<?php

/**
 * Gestion du rendu du formulaire d'édition d'une salle de cinéma
 */

namespace App\Forms\Admin\MovieRoom;

use App\Forms\DefaultFormRender;

final class MovieRoomFormRender extends DefaultFormRender {

    /**
	 * Retourne les labels par nom de champs
	 * @return array
	 */
	protected function getLabelsByNames() : array
	{
		return [
            'public_id' => trans('form.movie_room.fields.public_id'),
            'name' => trans('form.movie_room.fields.name'),
            'floor' => trans('form.movie_room.fields.floor'),
            'nb_places' => trans('form.movie_room.fields.nb_places'),
            'nb_handicap_places' => trans('form.movie_room.fields.nb_handicap_places'),
        ];
	}



}