<?php

/**
 * Tests de l'ajout d'une salle de cinéma
 */

namespace Tests\Admin\MovieRoom;

use App\Models\MovieRoom;

final class AddTest extends AbstractMovieRoomTestCase
{

    /**
     * Retourne l'URI depuis laquelle la requête est appelé
     * @return string
     */
    protected function getFromUri() : string
    {
        return 'admin/movie-rooms/create';
    }

    /**
     * Retourne l'URI du traitement du formulaire
     * @return string
     */
    protected function getFormUri() : string
    {
        return 'admin/movie-rooms';
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
        return MovieRoom::latest('id')->first();
    }

    /**
     * Retourne le message en cas de succès
     * @param array $formData
     * @return string
     */
    protected function getSuccessMessage(array $formData) : string
    {
        return sprintf('La salle \'%s\' a été créée.', $formData['name']);
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
        $countModels = MovieRoom::count();

        parent::testValidationFailure($formData, $errors);

        $newCountModels = MovieRoom::count();

        // On vérifie que les données n'ont pas été insérées
        $this->assertEquals($countModels, $newCountModels);
    }

}
