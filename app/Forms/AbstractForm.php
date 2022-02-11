<?php

/**
 * Gestion du traitement d'un formulaire
 * @author Stève Caillault
 */

namespace App\Forms;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

abstract class AbstractForm
{
	
	/**
	 * Objet validation 
	 * @var Validator
	 */
	private ?Validator $validator = null;
	
	/**
	 * Vrai si le formulaire a pu être traité avec succès (reste à null si le formulaire n'a pas été posté)
	 * @var bool 
	 */
	private ?bool $success = null;
	
	/**
	 * Erreurs de validation du formulaire
	 * @var array 
	 */
	private array $errors = [];
	
	/**********************************************************************************************************/
	
	/* VALIDATION */
	
	/**
	 * Test si le nom en paramètre est un nom de champs valide
	 * @param string $name
	 * @return bool
	 */
	private function isInputNameAllowed(string $name) : bool
	{
		if($name === FormDataEnum::FORM_NAME->value)
		{
			return true;
		}
		return array_key_exists($name, $this->getInputTypesByNames());
	}
	
	/**
	 * Retourne l'objet validation
	 * @return Validator 
	 */
	protected function getValidator() : Validator
	{
		$this->validator ??= $this->initializedValidator();
		return $this->validator;
	}
	
	/**
	 * Retourne l'objet Validator initialisé avec les règles de validation
	 * @return Validator
	 */
	abstract protected function initializedValidator() : Validator;
	
	/**********************************************************************************************************/
	
	/* CONSTRUCTEUR / INSTANCIATION */
	
	/**
	 * Constructeur 
	 * @param array $data Données des champs des formulaires
	 * @return array
	 */
	public function __construct(private array $data = [])
	{
		
	}
	
	/**********************************************************************************************************/

	/* TRAITEMENT DU FORMULAIRE */
	
	/**
	 * Processus de traitement du formulaire
	 * @return void
	 */
	public function process() : void
	{
		$this->setInputsData($this->data);

		// On vérifit que le nom du formulaire correspont à celui qui a été soumis
		$nameSubmitted = Arr::get($this->data, FormDataEnum::FORM_NAME->value);
		$formName = $this->getName();
		if($nameSubmitted !== $formName)
		{
			return;
		}

		
		$validator = $this->getValidator();

		if(! $validator->fails())
		{
			$this->setInputsData($validator->validated());
			$this->success = $this->onValid();
			if($this->success)
			{
				$this->onSuccess();
			}
		}
		else
		{
			$this->onErrors();
		}
	
	}
	
	/**
	 * Méthode à exécuter si le formulaire est valide
	 * @return bool
	 */
	abstract protected function onValid() : bool;
	
	/**
	 * Méthode à exécuter si le formulaire à des erreurs
	 * @return void
	 */
	protected function onErrors() : void
	{
		$this->success = false;
		$this->fillErrorsWithValidatorErrors();
	}

	/**
	 * 
	 */
	private function fillErrorsWithValidatorErrors() : void
	{
		$validatorErrors = $this->getValidator()->errors();
		$fields = $validatorErrors->keys();
		$errors = [];
		foreach($fields as $field)
		{
			$errors[$field] = $validatorErrors->first($field);
		}
		$this->errors = $errors;
	}
	
	/**
	 * Méthode à éxécuter si le traitement du formulaire est un succès
	 * @return void
	 */
	protected function onSuccess() : void
	{
		// Rien pour le moment 
	}
	
	/**********************************************************************************************************/
	
	/* GET / SET */

	/**
	 * Retourne la méthode d'envoi du formulaire
	 * @return FormMethodEnum
	 */
	public function getMethod() : FormMethodEnum
	{
		return FormMethodEnum::POST;
	}

	/**
	 * Retourne l'URL de l'action du formulaire
	 * @return ?string
	 */
	public function getActionUrl() : ?string
	{
		return null;
	}

	/**
	 * Retourne le nom du formulaire
	 * @return string
	 */
	abstract public function getName() : string;

	/**
	 * Retourne si le formulaire autorise le téléchargement des fichiers
	 * @return bool
	 */
	public function withUpload() : bool
	{
		return false;
	}

	/**
	 * Retourne un tableau associant pour chaque nom, le type de champs
	 * @return array
	 */
	abstract public function getInputTypesByNames() : array;
	
	/**
	 * Retourne les données du formulaire
	 * @return array
	 */
	public function getInputsData() : array
	{
		return $this->data;
	}

	/**
	 * Retourne la valeur du champs en paramètre
	 * @param string $fieldName
	 * @param ?mixed $default Valeur par défaut à retourner
	 * @return mixed
	 */
	public function getInputValue(string $fieldName, mixed $default = null) : mixed
	{
		return Arr::get($this->data, $fieldName, $default);
	}

	/**
	 * Modifie les données du formulaire
	 * @return array 
	 */
	protected function setInputsData(array $data) : array
	{
		$this->data = [];

		$inputTypesByNames = $this->getInputTypesByNames();

		foreach($data as $key => $value)
		{
			$allowed = $this->isInputNameAllowed($key);
			if(! $allowed)
			{
				continue;
			}
			
			$value = (is_string($value) and $this->fieldMustBeStripTagged($key)) ? trim(strip_tags($value)) : $value;

			$inputType = Arr::get($inputTypesByNames, $key);
			
			if($inputType === InputTypeEnum::CHECKBOX->name)
			{
				$value = (bool) $value;
			}
			
			$this->data[$key] = $value;
			
		}
		
		return $this->data;
	}

	/**
	 * Retourne si le nom du champs en paramètre doit être exclu du filtre strip_tags
	 * @param string $fieldName
	 * @return bool
	 */
	protected function fieldMustBeStripTagged(string $fieldName) : bool
	{
		return true;
	}
	
	/**
	 * Retourne si le formulaire a pu être traité avec succès (reste à NULL si le formulaire n'a pas été posté)
	 * @return bool
	 */
	public function getSuccess() : ?bool
	{
		return $this->success;
	}
	
	/**
	 * Retourne les erreurs de validation du formulaire
	 * @return array 
	 */
	public function getErrors() : array
	{
		return $this->errors;
	}

	/**********************************************************************************************************/
	
}
