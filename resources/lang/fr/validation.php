<?php

return [
    'custom' => [
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
        'new_password' => [
            'min' => 'Le mot de passe doit avoir au moins :min caractères.',
            'max' => 'Le mot de passe doit avoir au plus :max caractères.',
            'confirmed' => 'Les deux mots de passe doivent être identiques.',
        ],
        'password' => [
            'required' => 'Le mot de passe est nécessaire.',
        ],

    ],
];