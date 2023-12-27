<?php

/**
 * Gestion de l'erreur générique pour les FormRequest
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use \Illuminate\Contracts\Validation\Validator;

abstract class FormRequest extends Request
{

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) : void
    {
        if($this->ajax())
        {
            // On retourne une seule erreur par champs
            throw new HttpResponseException(response()->json([
                'errors' => collect($validator->errors())->map(function($item) {
                    return current($item);
                })->toArray(),
            ], 422));
        }
        else
        {
            $this->session()->flash('error', trans('form.invalidated.message'));
        }
        parent::failedValidation($validator);
    }
}
