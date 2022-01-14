<?php

/**
 * Traitement du formulaire d'édition d'une salle de cinéma
 */

namespace App\Forms\Admin\MovieRoom;

use Illuminate\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
/***/
use App\Forms\{
    AbstractForm,
    InputTypeEnum
};
use App\Models\MovieRoom;

final class MovieRoomForm extends AbstractForm {

    /**
     * Salle de cinéma à éditer
     * @var MovieRoom
     */
    private ?MovieRoom $movie_room = null;

    /**
     * Modifie la salle de cinéma à éditer
     * @param MovieRoom $movieRoom
     * @return self
     */
    public function setMovieRoom(MovieRoom $movieRoom) : self
    {
        $this->movie_room = $movieRoom;

        // Fusionne les données de la salle et celle du formulaire
        $newData = array(...[
            'public_id' => $movieRoom->public_id,
            'name' => $movieRoom->name,
            'floor' => $movieRoom->floor,
            'nb_places' => $movieRoom->nb_places,
            'nb_handicap_places' => $movieRoom->nb_handicap_places,
        ], ...$this->getInputsData());

        $this->setInputsData($newData);
        return $this;
    }

    /**
	 * Retourne le nom du formulaire
	 * @return string
	 */
	public function getName() : string
    {
        return 'admin-movie-room-edit';
    }

    /**
	 * Retourne un tableau associant pour chaque nom, le type de champs
	 * @return array
	 */
	public function getInputTypesByNames() : array
    {
        return [
            'public_id' => InputTypeEnum::TEXT->name,
            'name' => InputTypeEnum::TEXT->name,
            'floor' => InputTypeEnum::NUMBER->name,
            'nb_places' => InputTypeEnum::NUMBER->name,
            'nb_handicap_places' => InputTypeEnum::NUMBER->name,
        ];
    }

    /**
	 * Retourne l'objet Validator initialisé avec les règles de validation
	 * @return Validator
	 */
	protected function initializedValidator() : Validator
    {
        $data = $this->getInputsData();

        $uniquePublicIdRule = Rule::unique(MovieRoom::class, 'public_id')->ignore($this->movie_room);
        $uniqueNameRule = Rule::unique(MovieRoom::class, 'name')->ignore($this->movie_room);

        return ValidatorFactory::make($data, [
            'public_id' => [ 'bail', 'required', 'string', 'min:5', 'max:25', $uniquePublicIdRule, ],
            'name' => [ 'bail', 'required', 'string', 'min:5', 'max:25', $uniqueNameRule, ],
            'floor' => [ 'bail', 'required', 'numeric', 'min:-10', 'max:10', ],
            'nb_places' => [ 'bail', 'required', 'numeric', 'min:20', 'max:1000', ],
            'nb_handicap_places' => [ 'bail', 'required', 'numeric', 'min:20', 'max:1000', 'lte:nb_places', ],
        ]);
    }

    /**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	protected function onValid() : bool
    {
        $movieRoom = $this->movie_room ?: new MovieRoom();
        $data = $this->getInputsData();

        $movieRoom->public_id = Arr::get($data, 'public_id');
        $movieRoom->name = Arr::get($data, 'name');
        $movieRoom->floor = Arr::get($data, 'floor');
        $movieRoom->nb_places = Arr::get($data, 'nb_places');
        $movieRoom->nb_handicap_places = Arr::get($data, 'nb_handicap_places');
        
        return $movieRoom->save();
    }
}