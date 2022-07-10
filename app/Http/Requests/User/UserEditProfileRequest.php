<?php

/**
 * Requête de l'édition du profil de l'utilisateur connecté
 */

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
/***/
use App\Http\Requests\FormRequest;
use App\Models\User;

final class UserEditProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return ($this->user() !== null);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        $user = $this->user();

        $uniqueNicknameRule = Rule::unique(User::class, 'nickname')->ignore($user);
        $uniqueEmailRule = Rule::unique(User::class, 'email')->ignore($user);

        return [
            'nickname' => [ 'bail', 'required', 'min:5', 'max:50', $uniqueNicknameRule, ],
            'email' => [ 'bail', 'required', 'min:5', 'max:100', 'email', $uniqueEmailRule, ],
            'first_name' => [ 'bail', 'required', 'min:5', 'max:100', ],
            'last_name' => [ 'bail', 'required', 'min:5', 'max:100', ],
            'current_password' => [ 'bail', 'nullable', 'string', 'required_with:new_password', 'current_password:admin', ],
            'new_password' => [ 'bail', 'nullable', 'string', 'min:8', 'max:100', 'confirmed', 'different:current_password', ],
        ];
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        /**
         * @var User
         */
        $user = $this->user();

        $this->merge([
            'nickname' => $this->get('nickname', $user->nickname),
            'email' => $this->get('email', $user->email),
            'first_name' => $this->get('first_name', $user->first_name),
            'last_name' => $this->get('last_name', $user->last_name),
        ]);
    }

}
