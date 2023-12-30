<?php

/**
 * Type de contributeur à un film
 */

namespace App\Models;

enum MovieContributorTypeEnum {

    // Réalisateur
    const Director = 'DIRECTOR';

    // Producteur
    const Producer = 'PRODUCER';

    // Scénariste
    const Screenwriter = 'SCREENWRITER';

}
