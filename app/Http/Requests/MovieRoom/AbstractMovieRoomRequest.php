<?php

/**
 * Requête de la gestion d'une salle de cinéma
 */

namespace App\Http\Requests\MovieRoom;

use Illuminate\Validation\Rule;
/***/
use App\Http\Requests\FormRequest;
use App\Models\MovieRoom;

abstract class AbstractMovieRoomRequest extends FormRequest
{

    /**
     * Salle de cinéma gérée
     * @var MovieRoom
     */
    protected MovieRoom $movieRoom;

    /**
     * Retourne la salle de cinéma gérée
     * @return MovieRoom
     */
    abstract public function getMovieRoom() : MovieRoom;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return ($this->user()?->isAdministrator());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        /**
         * @var MovieRoom
         */
        $movieRoom = $this->getMovieRoom();

        $uniquePublicIdRule = Rule::unique(MovieRoom::class, 'public_id')->ignore($movieRoom);
        $uniqueNameRule = Rule::unique(MovieRoom::class, 'name')->ignore($movieRoom);

        return [
            'public_id' => [ 'bail', 'required', 'string', 'min:5', 'max:25', $uniquePublicIdRule, ],
            'name' => [ 'bail', 'required', 'string', 'min:5', 'max:25', $uniqueNameRule, ],
            'floor' => [ 'bail', 'required', 'numeric', 'min:-10', 'max:10', ],
            'nb_places' => [ 'bail', 'required', 'numeric', 'min:20', 'max:1000', ],
            'nb_handicap_places' => [ 'bail', 'required', 'numeric', 'min:20', 'max:1000', 'lte:nb_places', ],
        ];
    }

}
