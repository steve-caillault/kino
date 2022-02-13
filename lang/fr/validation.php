<?php

return [

    'exists' => 'La valeur :attribute est incorrecte.',

    'custom' => [
        'token' => [
            'required' => 'Le jeton est manquant.',
        ],

        'nickname' => [
            'required' => 'Le nom d\'utilisateur est nécessaire.',
            'min' => 'Le nom d\'utilisateur doit avoir au moins :min caractères.',
            'max' => 'Le nom d\'utilisateur doit avoir au plus :max caractères.',
            'unique' => 'Ce nom d\'utilisateur est déjà utilisé.'
        ],
        'email' => [
            'required' => 'L\'adresse email est nécessaire.',
            'min' => 'L\'adresse email doit avoir au moins :min caractères.',
            'max' => 'L\'adresse email doit avoir au plus :max caractères.',
            'email' => 'L\'adresse email n\'est pas valide.',
            'unique' => 'Cette adresse email est déjà utilisée.',
        ],
        'first_name' => [
            'required' => 'Le prénom est nécessaire.',
            'min' => 'Le prénom doit avoir au moins :min caractères.',
            'max' => 'Le prénom doit avoir au plus :max caractères.',
        ],
        'last_name' => [
            'required' => 'Le nom est nécessaire.',
            'min' => 'Le nom doit avoir au moins :min caractères.',
            'max' => 'Le nom doit avoir au plus :max caractères.',
        ],
        'current_password' => [
            'required' => 'Votre mot de passe actuel est requis.',
            'required_with' => 'Votre mot de passe actuel est requis.',
            'current_password' => 'Le mot de passe est incorrect.',
        ],
        'new_password' => [
            'min' => 'Le mot de passe doit avoir au moins :min caractères.',
            'max' => 'Le mot de passe doit avoir au plus :max caractères.',
            'confirmed' => 'Les deux mots de passe doivent être identiques.',
            'different' => 'Votre nouveau mot de passe doit être diffèrent de l\'actuel.',
        ],
        'password' => [
            'required' => 'Le mot de passe est nécessaire.',
            'min' => 'Le mot de passe doit avoir au moins :min caractères.',
            'max' => 'Le mot de passe doit avoir au plus :max caractères.',
            'confirmed' => 'Les deux mots de passe doivent être identiques.',
        ],
        'public_id' => [
            'required' => 'L\'identifiant public est nécessaire.',
            'min' => 'L\'identifiant public doit avoir au moins :min caractères.',
            'max' => 'L\'identifiant public doit avoir au plus :max caractères.',
            'unique' => 'Cet identifiant est déjà utilisé.',
        ],
        'name' => [
            'required' => 'Le nom est nécessaire.',
            'min' => 'Le nom doit avoir au moins :min caractères.',
            'max' => 'Le nom doit avoir au plus :max caractères.',
            'unique' => 'Ce nom est déjà utilisé.',
        ],
        'floor' => [
            'required' => 'L\'étage est nécessaire.',
            'numeric' => 'L\'étage doit être un nombre.',
            'min' => 'L\'étage ne peut pas être inférieur à :min.',
            'max' => 'L\'étage ne peut pas être supérieur à :max.',
        ],
        'nb_places' => [
            'required' => 'Le nombre de places est nécessaire.',
            'numeric' => 'Le nombre de places doit être un nombre.',
            'min' => 'Le nombre de places ne peut pas être inférieur à :min.',
            'max' => 'Le nombre de places ne peut pas être supérieur à :max.',
        ],
        'nb_handicap_places' => [
            'required' => 'Le nombre de places adaptées à un handicap est nécessaire.',
            'numeric' => 'Le nombre de places adaptées à un handicap doit être un nombre.',
            'min' => 'Le nombre de places adaptées à un handicap ne peut pas être inférieur à :min.',
            'max' => 'Le nombre de places adaptées à un handicap ne peut pas être supérieur à :max.',
            'lte' => 'Le nombre de places adaptées à un handicap ne peut pas être supérieur à :value.',
        ],

    ],
];