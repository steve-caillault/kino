<?php

/**
 * Requête de la gestion d'un film
 */

namespace App\Http\Requests\Movie;

use Illuminate\Validation\Rule;
/***/
use App\Http\Requests\FormRequest;
use App\Models\Movie;

abstract class AbstractMovieRequest extends FormRequest
{

    /**
     * Film géré
     * @var Movie
     */
    protected Movie $movie;

    /**
     * Retourne le film géré
     * @return Movie
     */
    abstract public function getMovie() : Movie;

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
        $movie = $this->getMovie();

        $uniquePublicIdRule = Rule::unique(Movie::class, 'public_id')->ignore($movie);
        $uniqueNameRule = Rule::unique(Movie::class, 'name')->ignore($movie);

        return [
            'public_id' => [ 'bail', 'required', 'string', 'min:5', 'max:100', $uniquePublicIdRule, ],
            'name' => [ 'bail', 'required', 'string', 'min:5', 'max:100', $uniqueNameRule, ],
            'produced_at' => [ 'bail', 'required', 'date_format:Y-m-d', 'after_or_equal:1895-03-19', 'before:9999-12-31', ],
        ];
    }

}
