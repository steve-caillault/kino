<?php

/**
 * Tests de l'ajout d'une salle de cinéma
 */

namespace Tests\Admin\MovieRoom;

use App\Models\MovieRoom;

final class AddTest extends AbstractMovieRoomTest
{

     /**
     * Retourne l'URI de l'application à appeler
     * @return string
     */
    protected function getUri() : string
    {
        return 'admin/movie-rooms/add';
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