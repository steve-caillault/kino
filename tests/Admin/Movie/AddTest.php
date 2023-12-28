<?php

/**
 * Tests de l'ajout d'un film
 */

namespace Tests\Admin\Movie;

use App\Models\Movie;

final class AddTest extends AbstractMovieTestCase
{

    /**
     * Retourne l'URI depuis laquelle la requête est appelé
     * @return string
     */
    protected function getFromUri() : string
    {
        return 'admin/movies/create';
    }

    /**
     * Retourne l'URI du traitement du formulaire
     * @return string
     */
    protected function getFormUri() : string
    {
        return 'admin/movies';
    }

    /**
     * Retourne la méthode de la requête à appeler
     * @return string
     */
    protected function getFormMethod() : string
    {
        return 'post';
    }

    /**
     * Retourne le modèle à vérifier après l'appel
     * @return ?MovieRoom
     */
    protected function getModel() : ?MovieRoom
    {
        return Movie::latest('id')->first();
    }

    /**
     * Retourne le message en cas de succès
     * @param array $formData
     * @return string
     */
    protected function getSuccessMessage(array $formData) : string
    {
        return sprintf('Le film \'%s\' a été créé.', $formData['name']);
    }

    /**
     * Test d'échec de la validation des données
     * @param array $formData
     * @param array $errors
     * @dataProvider validationFailureProvider
     * @return void
     */
    public function testValidationFailure(array $formData, array $errors) : void
    {
        $countModels = Movie::count();

        parent::testValidationFailure($formData, $errors);

        $newCountModels = Movie::count();

        // On vérifie que les données n'ont pas été insérées
        $this->assertEquals($countModels, $newCountModels);
    }

}
