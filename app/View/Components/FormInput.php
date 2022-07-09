<?php

/**
 * Componsant du conteneur d'un champ de formulaire
 */

namespace App\View\Components;

use Illuminate\View\Component;

final class FormInput extends Component
{
    /**
     * Create a new component instance.
     * 
     * @param string $inputId Identifiant du champ
     * @param string $inputName Nom du champs
     * @param bool $required Vrai si le champ est requis
     * @param ?string $label Si renseigné, l'intitulé du chams ; si non présent il n'y aura pas de balise label
     */
    public function __construct(
        private string $inputId,
        private string $inputName,
        private bool $required = false, 
        private ?string $label = null,
    )
    {

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-input', [
            'inputId' => $this->inputId,
            'inputName' => $this->inputName,
            'required' => $this->required,
            'label' => $this->label,
        ]);
    }
}
