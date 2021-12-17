<?php

/**
 * Trait pour la génération du HTML des champs d'un formulaire
 */

namespace App\Forms;

use App\UI\HTML\FormHTML;

trait InputRenderTrait {

    /**
	 * Retourne un champs texte pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputText(string $name, ?string $value, array $attributes = []) : string
	{
        $attributes['id'] ??= $name;
        $attributes['autocomplete'] = 'off';
        
		return FormHTML::text($name, $value, $attributes);
	}
	
	/**
	 * Retourne un champs email pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputEmail(string $name, ?string $value, array $attributes = []) : string
	{
        $attributes['id'] ??= $name;
        $attributes['autocomplete'] = 'off';
		
		return FormHTML::input('email', $name, $value, $attributes);
	}
	
	/**
	 * Retourne un champs nombre pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputNumber(string $name, ?string $value, array $attributes) : string
	{
        $attributes['id'] ??= $name;
        $attributes['autocomplete'] = 'off';

		return FormHTML::number($name, $value, $attributes);
	}

    /**
	 * Retourne un champs mot de passe pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputPassword(string $name, array $attributes = []) : string
	{
        $attributes['id'] ??= $name;

		return FormHTML::input('password', $name, attributes: $attributes);
	}
	
	/**
	 * Retourne un champs date pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes 
	 * @return string
	 */
	protected function getInputDate(string $name, ?string $value, array $attributes = []) : string
	{
        $attributes['id'] ??= $name;
        $attributes['autocomplete'] = 'off';

		return FormHTML::input('date', $name, $value, $attributes);
	}
	
	/**
	 * Retourne un champs d'heure pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputTime(string $name, ?string $value, array $attributes = []) : string
	{
		$attributes['id'] ??= $name;
        $attributes['autocomplete'] = 'off';

		return FormHTML::input('time', $name, $value, $attributes);
	}
	
	/**
	 * Retourne un champs textarea pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputTextarea(string $name, ?string $value, array $attributes = []) : string
	{
		$attributes['id'] ??= $name;

		return FormHTML::textarea($name, $value, $attributes);
	}

    /**
	 * Retourne un champs select pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
     * @param array $attributes
	 * @return string
	 */
	protected function getInputSelect(string $name, ?string $value, array $attributes = []) : string
	{
		$options = $this->getSelectOptions($name);
		
		if(count($options) === 0)
		{
            $error = trans('form.error.select.no_option', [
                'name' => $name,
            ]);
			abort(500, $error);
		}

        $attributes['id'] ??= $name;
		
		return FormHTML::select($name, $value, $options, $attributes);
	}
	
	/**
	 * Retourne les options du champs select dont le nom du champs est en paramètre
	 * @param string $name Nom du champs
	 * @return array
	 */
	protected function getSelectOptions(string $name) : array
	{
		// A surcharger dans les classes filles
		return [];
	}
	
	/**
	 * Retourne un champs de case à cocher pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param bool $checked
     * @param array $attributes
	 * @return string
	 */
	protected function getInputCheckbox(string $name, bool $checked, array $attributes = []) : string
	{
		$params = [
			'id' => $name,
		];
		
		if($checked)
		{
			$attributes['checked'] ??= $checked;
		}
		
		return FormHTML::input('checkbox', $name, 1, $attributes);
	}
	
	/**
	 * Retourne un champs de téléchargement de fichier pour le nom du champs en paramètre
	 * @param string $name Nom du champs
	 * @return string
	 */
	protected function getInputFile(string $name) : string
	{
		return FormHTML::file($name, [
			'id' => $name,
		]);
	}
	
	/**
	 * Retourne un champs caché pour le nom du champs en paramètre
	 * @param string $name Nom du champs
     * @param ?string $value
	 * @return string
	 */
	protected function getInputHidden(string $name, ?string $value) : string
	{
		return FormHTML::hidden($name, $value);
	}

}