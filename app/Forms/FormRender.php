<?php

/**
 * Gestion du rendu d'un formulaire
 */

namespace App\Forms;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Renderable;
/***/

use App\UI\HTML\{
	FormHTML,
	HTML
};

abstract class FormRender {

	use InputRenderTrait;

	/**
	 * Constructeur
	 * @param AbstractForm $form Formulaire
	 */
	public function __construct(private AbstractForm $form)
	{

	}

	/**
	 * Retourne si le formulaire doit être affiché sur une ligne
	 * @return bool
	 */
	protected function isInline() : bool
	{
		return false;
	}

	/**
	 * Retourne le titre du formulaire
	 * @return ?string
	 */
	protected function getTitle() : ?string
	{
		return null;
	}

	/**
	 * Retourne si le titre du formulaire peut être affiché
	 * @return bool
	 */
	protected function canDisplayTitle() : bool
	{
		return ($this->getTitle() !== null);
	}

    /**
	 * Retourne les classes du formulaire
	 * @return array
	 */
	protected function getClasses() : array
	{
		$classes = [];
		
        $isInline = $this->isInline();
		if($isInline)
		{
			$classes[] = 'inline';
		}
		
        $errors = $this->form->getErrors();
		if(count($errors) > 0)
		{
			$classes[] = 'errors';
		}
		
		return $classes;
	}

    /**
	 * Retourne les attributs du formulaire
	 * @return array
	 */
	protected function getAttributes() : array
	{
		$attributes = [
			'method' => strtolower($this->form->getMethod()->name),
		];
		
		if($this->form->withUpload())
		{
			$attributes['enctype'] = 'multipart/form-data';
		}
		
		$actionUrl = $this->form->getActionUrl();
		if($actionUrl != NULL)
		{
			$attributes['action'] = $actionUrl;
		}
		
		$classes = $this->getClasses();
		
		if(count($classes) > 0)
		{
			$attributes['class'] = implode(' ', $classes);
		}
		
		return $attributes;		
	}

    /**
	 * Retourne les paramètres du label dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function getLabelAttributesOfInput(string $name) : array
	{
		$attributes = [];
		$requiredFieldNames = $this->form->getRequiredInputNames();

		if(in_array($name, $requiredFieldNames))
		{
			$attributes['class'] = 'required';
		}

		return $attributes;
	}

	/**
	 * Retourne les labels par nom de champs
	 * @return array
	 */
	protected function getLabelsByNames() : array
	{
		return []; // A gérer dans les classes filles
	}

	/**
	 * Retourne les paramètres du champs dont le nom est en paramètre
	 * @param string $name
	 * @return array
	 */
	protected function getInputAttributes(string $name) : array
	{
		return []; // A gérer dans les classes filles
	}

    /**
	 * Retourne les labels des champs
	 * @return array
	 */
	protected function getLabels() : array
	{
		$labels = [];
		
        $labels = $this->getLabelsByNames();
		foreach($labels as $key => $value)
		{
			$attributes = $this->getLabelAttributesOfInput($key);
			$labels[$key] = FormHTML::label($key, $value, $attributes);
		}
		
		return $labels;
	}

	/**
	 * Retourne les données des champs
	 * @return array
	 */
	protected function getInputsData() : array
	{
		return $this->form->getInputsData();
	}

	/**
	 * Retourne le tableau des champs du formulaire
	 * @return array
	 */
	protected function getInputs() : array
	{
		$formInputsData = $this->form->getInputsData();
		$formName = $this->form->getName();
		$inputFormName = ($formName === null) ? null : FormHTML::hidden(FormDataEnum::FORM_NAME->value, $formName);

		$submitLabel = $this->getSubmitLabel();

		$inputs = [
			'fields' 	=> [],
			'hidden'	=> [],
			'files'	 	=> [],	
			'name' 		=> $inputFormName,
			'submit' 	=> FormHTML::submit($submitLabel, $this->getInputAttributes(FormDataEnum::SUBMIT->name)),
		];
		
		$formInputsNames = $this->form->getInputTypesByNames();

		foreach($formInputsNames as $name => $fieldType)
		{
			$key = match($fieldType) {
				InputTypeEnum::FILE->name => 'files',
				InputTypeEnum::HIDDEN->name => 'hidden',
				default => 'fields'
			};

			$inputMethod = 'getInput' . ucfirst($fieldType);
			$inputAttributes = $this->getInputAttributes($name);

			$inputValue = match($fieldType) {
				InputTypeEnum::CHECKBOX->name => (bool) Arr::get($formInputsData, $name, false),
				InputTypeEnum::PASSWORD->name => null,
				default => Arr::get($formInputsData, $name, false)
			};

			$inputs[$key][$name] = match($fieldType) {
				InputTypeEnum::PASSWORD->name => $this->getInputPassword($name, $inputAttributes),
				InputTypeEnum::HIDDEN->name => $this->getInputHidden($name, $inputValue),
				InputTypeEnum::FILE->name => $this->getInputFile($name),
				default => $this->{ $inputMethod }($name, $inputValue, $inputAttributes)
			};
		}
		
		return $inputs;
	}

	/**
	 * Retourne le texte de soumission
	 * @return string
	 */
	protected function getSubmitLabel() : string
	{
		return trans('form.submit.label');
	}

	/**
	 * Retourne la vue du formulaire à utiliser
	 * @return string
	 */
	protected function getViewName() : string
	{
		return 'misc.form';
	}

	/**
	 * Retourne la réponse du traitement du formulaire (utilisé lors d'un appel Ajax notamment)
	 * @return array
	 *		'success': <boolean>,
	 *		'errors': <array>
	 */
	public function getAjaxResponse() : array
	{
		return [
			'success' => $this->form->getSuccess(),
			'errors' => $this->form->getErrors(),
		];
	}

    /**
	 * Méthode de rendu du formulaire
	 * @return Renderable 
	 */
	public function render() : Renderable
	{
		$attributes = HTML::attributes($this->getAttributes());
		$inputs = $this->getInputs();
		$labels = $this->getLabels();
		$inputPreviews = []; // $this->getInputPreviews();
		$inputGroupKeys = [ 'fields', 'files', ];
		$inputAttributes = [];
		
		$errors = $this->form->getErrors();
		
		
		foreach($inputGroupKeys as $inputGroupKey)
		{
			foreach($inputs[$inputGroupKey] as $inputKey => $input)
			{
				$classes = [ 'form-input', ];
				
				$label = Arr::get($labels, $inputKey);
				if($label !== null)
				{
					$classes[] = 'with-label';
				}
				
				$withError = (Arr::get($errors, $inputKey) !== null);
				if($withError)
				{
					$classes[] = 'error';
				}
				
				$inputAttributes[$inputKey] = HTML::attributes([
					'class' => implode(' ', $classes),
				]);
				
				
				$preview = Arr::get($inputPreviews, $inputKey);
				$inputString = ($label . $preview . $input);
				$inputs[$inputGroupKey][$inputKey] = $inputString;
			}
		}
		
		return view($this->getViewName(), [
			'attributes' => $attributes,
			'errors' => $errors,
			'title'	=> $this->getTitle(),
			'withTitle'	=> $this->canDisplayTitle(),
			'inputs' => $inputs,
			'inputGroupKeys' => $inputGroupKeys,
			'inputAttributes' => $inputAttributes,
		]);
	}

}