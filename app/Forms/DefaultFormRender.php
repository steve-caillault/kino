<?php

/**
 * Gestion du rendu d'un formulaire par défaut pour ce projet seulement
 * On ne gére ici que des classes pour certains champs communs
 */

namespace App\Forms;

abstract class DefaultFormRender extends FormRender {

    /**
	 * Retourne les paramètres du champs dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function getInputAttributes(string $name) : array
	{
        $attributes = [];

        if($name === FormDataEnum::SUBMIT->name)
        {
            $attributes['class'] = 'button';
        }

		return $attributes;
	}

}