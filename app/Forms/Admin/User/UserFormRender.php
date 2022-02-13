<?php

/**
 * Gestion du rendu du formulaire d'édition d'un compte
 */

namespace App\Forms\Admin\User;

use App\Forms\DefaultFormRender;

final class UserFormRender extends DefaultFormRender {

    /**
	 * Retourne les labels par nom de champs
	 * @return array
	 */
	protected function getLabelsByNames() : array
	{
		return [
            'nickname' => trans('form.user.fields.nickname'),
            'email' => trans('form.user.fields.email'),
            'first_name' => trans('form.user.fields.first_name'),
            'last_name' => trans('form.user.fields.last_name'),
            'current_password' => trans('form.user.fields.current_password'),
            'new_password' => trans('form.user.fields.new_password'),
            'new_password_confirmation' => trans('form.user.fields.new_password_confirmation'),
        ];
	}

    /**
	 * Retourne les paramètres du label dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function getLabelAttributesOfInput(string $name) : array
	{
        
        $attributes = parent::getLabelAttributesOfInput($name);

        $currentData = $this->getInputsData();
        $newPassword = $currentData['new_password'] ?? null;

        // Les champs de mot de passe ne sont requis que si un nouveau mot de passe est définie
        $passwordFields = [ 'current_password', 'new_password', 'new_password_confirmation', ];
        if(in_array($name, $passwordFields) and $newPassword === null)
        {
            $attributes['class'] ??= null;
            $attributes['class'] = strtr($attributes['class'], [
                'required' => '',
            ]);
        }

        return $attributes;
    }

}