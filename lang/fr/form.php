<?php

/**
 * Messages FR des formulaires
 */

return [
    'invalidated' => [
        'message' => 'Il y a des valeurs incorrectes dans le formulaire.',
    ],

    'input' => [
        'select' => [
            'no_option' => [
                'message' => 'Aucune option pour le champs :name.',
            ],
        ],
        'submit' => [
            'default' => [
                'label' => 'Enregistrer',
            ],
        ],
    ],

    'auth' => [
        // Formulaire de connexion
        'login' => [
            'button' => [
                'forgot_password' => [
                    'label' => 'Mot de passe oublié',
                    'alt_label' => 'Envoyer une demande de réinitialisation de mot de passe.',
                ],
            ],

            'fields' => [
                'nickname' => 'Nom d\'utilisateur',
                'password' => 'Mot de passe',
                'remember' => 'Enregistrer en cookie',
                'submit' => 'Se connecter',
            ],
        ],

        // Demande de réinitialisation de mot de passe
        'forgot_password' => [
            'button' => [
                'login' => [
                    'label' => 'Se connecter',
                    'alt_label' => 'Se connecter à votre compte.',
                ]
            ],
            'fields' => [
                'email' => 'Adresse email de votre compte',
                'submit' => 'Envoyer la demande',
            ],
        ],

        // Réinitialisation du mot de passe
        'reset_password' => [
            'fields' => [
                'email' => 'Adresse email de votre compte',
                'password' => 'Nouveau mot de passe',
                'password_confirmation' => 'Confirmation du mot de passe',
                'submit' => 'Réinitialiser le mot de passe',
            ],
        ],

    ],


    'user' => [
        'profile' => [
            // Edition du compte de l'utilisateur
            'edit' => [
                'fields' => [
                    'nickname' => 'Nom d\'utilisateur',
                    'email' => 'Adresse email',
                    'first_name' => 'Prénom',
                    'last_name' => 'Nom',
                    'current_password' => 'Mot de passe actuel',
                    'new_password' => 'Nouveau mot de passe',
                    'new_password_confirmation' => 'Confirmation du mot de passe',
                ],
                'flash' => [
                    'success' => 'Votre compte a été mis à jour.',
                    'failure' => 'Une erreur s\'est produite lors de la mise à jour de votre compte.',
                ],
            ],
        ],
    ],

    'admin' => [

        // Gestion d'une salle de cinéma
        'movie_room' => [
            'fields' => [
                'public_id' => 'Identifiant public',
                'name' => 'Nom de la salle',
                'floor' => 'Étage',
                'nb_places' => 'Nombre total de places',
                'nb_handicap_places' => 'Nombre de places adaptées à un handicap',
            ],
            'add' => [
                'flash' => [
                    'success' => 'La salle \':name\' a été créée.',
                    'failure' => 'Une erreur s\'est produite lors de la création de la salle \':name\'.',
                ],
            ],
            'edit' => [
                'flash' => [
                    'success' => 'La salle \':name\' a été mise à jour.',
                    'failure' => 'Une erreur s\'est produite lors de la modification de la salle \':name\'.',
                ],
            ],
        ],

        // Gestion d'un film
        'movie' => [
            'fields' => [
                'public_id' => 'Identifiant public',
                'name' => 'Nom du film',
                'produced_at' => 'Date de production',
            ],
            'add' => [
                'flash' => [
                    'success' => 'Le film \':name\' a été créé.',
                    'failure' => 'Une erreur s\'est produite lors de la création du film\':name\'.',
                ],
            ],
            'edit' => [
                'flash' => [
                    'success' => 'Le film \':name\' a été mis à jour.',
                    'failure' => 'Une erreur s\'est produite lors de la modification du film \':name\'.',
                ],
            ],
        ],
    ],

];
