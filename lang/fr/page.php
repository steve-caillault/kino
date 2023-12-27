<?php

/**
 * Messages FR pour les titres des pages
 */

return [
    'home' => [
        'title' => 'Kino, réservation de places de cinéma',
    ],

    'error' => [
        'title' => 'Une erreur :code s\'est produite',
    ],

    'login' => [
        'title' => 'Connexion au site Kino',
    ],

    'forgot_password' => [
        'title' => 'Demande de réinitialisation de mot de passe',
    ],

    'reset_password' => [
        'title' => 'Réinitialisation de votre mot de passe',
    ],

    // Panneau d'administration
    'admin' => [
        'home' => [
            'title' => 'Panneau d\'administration du site Kino',
            'sectionChoiceText' => 'Choisissez un lien dans le menu.',
        ],
        'user' => [
            'title' => 'Gestion des paramètres de votre compte Kino',
        ],

        // Salles de cinéma
        'movie_rooms' => [
            'button' => [
                'add' => [
                    'label' => 'Ajouter une salle',
                    'alt_label' => 'Ajouter une nouvelle salle de cinéma.',
                ],
                'edit' => [
                    'label' => 'Gérer la salle',
                    'alt_label' => 'Gérer la salle \':name\'.',
                ],
            ],

            'list' => [
                'title' => 'Salles de cinéma',
                'empty' => 'Aucune salle de cinéma n\'a été trouvé.',
                'item' => [
                    'fields' => [
                        'floor' => 'Étage :',
                        'nb_places' => 'Nombre total de places :',
                        'nb_handicap_places' => 'Nombre de places adaptées à un handicap :',
                    ],
                ],
            ],
            'add' => [
                'title' => 'Ajout d\'une salle de cinéma',
            ],
            'edit' => [
                'title' => 'Édition de salle de cinéma \':name\'',
            ],
        ],

        // Films
        'movies' => [
            'button' => [
                'add' => [
                    'label' => 'Ajouter un film',
                    'alt_label' => 'Ajouter un nouveau film.',
                ],
                'edit' => [
                    'label' => 'Gérer le film',
                    'alt_label' => 'Gérer le film \':name\'.',
                ],
            ],
            'list' => [
                'title' => 'Films',
                'empty' => 'Aucun film n\'a été trouvé.',
                'item' => [
                    'fields' => [
                        'produced_at' => 'Année de production',
                    ],
                ],
            ],
            'add' => [
                'title' => 'Ajout d\'un film',
            ],
            'edit' => [
                'title' => 'Édition du film \':name\'',
            ],
        ],
    ],
];
