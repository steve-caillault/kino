<?php

/**
 * Tests de l'édition d'une salle de cinéma
 */

namespace Tests\Admin\MovieRoom;

use App\Models\MovieRoom;

final class EditTest extends AbstractMovieRoomTest
{
     /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        MovieRoom::factory()->create([
            'public_id' => 'salle-2',
            'name' => 'Salle 2',
        ]);
    }

     /**
     * Retourne l'URI de l'application à appeler
     * @return string
     */
    protected function getUri() : string
    {
        return 'admin/movie-rooms/salle-2/edit';
    }

    /**
     * Retourne le message en cas de succès
     * @param array $formData
     * @return string
     */
    protected function getSuccessMessage(array $formData) : string
    {
        return sprintf('La salle \'%s\' a été mis à jour.', $formData['name']);
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
        $modelBefore = $this->getModelByPublicId('salle-2');

        parent::testValidationFailure($formData, $errors);

        $modelAfter = $this->getModelByPublicId('salle-2');

        $this->assertEquals($modelBefore, $modelAfter);
    }

    /**
     * Provider pour les tests de validation
     * @return array
     */
    public function validationFailureProvider() : array
    {
        $data = parent::validationFailureProvider();

        unset($data['without-data']); // Suppression car le formulaire utilisera par défaut les valeurs du modèle édité

        return $data;
    }

}