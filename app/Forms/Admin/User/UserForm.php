<?php

/**
 * Traitement du formulaire d'édition du compte d'un utilisateur
 */

namespace App\Forms\Admin\User;

use Illuminate\Validation\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFactory;
use Illuminate\Validation\Rule;
/***/
use App\Forms\{
    AbstractForm,
    InputTypeEnum
};
use App\Models\User;

final class UserForm extends AbstractForm {

    /**
     * Utilisateur à éditer
     * @var User
     */
    private User $user;

    /**
     * Modifie l'utilisateur à éditer
     * @param User $user
     * @return self
     */
    public function setUser(User $user) : self
    {
        $this->user = $user;

        // Fusionne les données de l'utilisateur et celle du formulaire
        $newData = array(...[
            'nickname' => $user->nickname,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
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
        return 'admin-user-settings';
    }

    /**
	 * Retourne si le nom du champs en paramètre doit être exclu du filtre strip_tags
	 * @param string $fieldName
	 * @return bool
	 */
	protected function fieldMustBeStripTagged(string $fieldName) : bool
	{
		return ! in_array($fieldName, [
            'new_password', 'new_password_confirmation',
        ]);
	}

    /**
	 * Retourne un tableau associant pour chaque nom, le type de champs
	 * @return array
	 */
	public function getInputTypesByNames() : array
    {
        return [
            'nickname' => InputTypeEnum::TEXT->name,
            'email' => InputTypeEnum::EMAIL->name,
            'first_name' => InputTypeEnum::TEXT->name,
            'last_name' => InputTypeEnum::TEXT->name,
            'new_password' => InputTypeEnum::PASSWORD->name,
            'new_password_confirmation' => InputTypeEnum::PASSWORD->name,
        ];
    }

    /**
	 * Retourne l'objet Validator initialisé avec les règles de validation
	 * @return Validator
	 */
	protected function initializedValidator() : Validator
    {
        $data = $this->getInputsData();

        $uniqueNicknameRule = Rule::unique(User::class, 'nickname')->ignore($this->user);
        $uniqueEmailRule = Rule::unique(User::class, 'email')->ignore($this->user);

        return ValidatorFactory::make($data, [
            'nickname' => [ 'bail', 'required', 'min:5', 'max:50', $uniqueNicknameRule, ],
            'email' => [ 'bail', 'required', 'min:5', 'max:100', 'email', $uniqueEmailRule, ],
            'first_name' => [ 'bail', 'required', 'min:5', 'max:100', ],
            'last_name' => [ 'bail', 'required', 'min:5', 'max:100', ],
            'new_password' => [ 'bail', 'nullable', 'string', 'min:8', 'max:100', 'confirmed', ],
        ]);
    }

    /**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	protected function onValid() : bool
    {
        $user = $this->user;
        $data = $this->getInputsData();

        $user->nickname = Arr::get($data, 'nickname');
        $user->email = Arr::get($data, 'email');
        $user->first_name = Arr::get($data, 'first_name');
        $user->last_name = Arr::get($data, 'last_name');
        
        $newPassword = Arr::get($data, 'new_password') ?: null;
        if($newPassword !== null)
        {
            $user->password = $newPassword;
        }

        return $user->save();
    }
}