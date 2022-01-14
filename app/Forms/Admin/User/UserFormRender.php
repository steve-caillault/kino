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
            'new_password' => trans('form.user.fields.new_password'),
            'new_password_confirmation' => trans('form.user.fields.new_password_confirmation'),
        ];
	}



}