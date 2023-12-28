<?php

/**
 * Tests de l'édition d'un film
 */

namespace Tests\Admin\Movie;

use App\Models\Movie;

final class EditTest extends AbstractMovieTestCase
{
     /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        Movie::factory()->create([
            'public_id' => 'film-2',
            'name' => 'Film 2',
            'produced_at' => self::getFaker()->dateTimeBetween('-100 years', '-2 days'),
        ]);
    }

    /**
     * Retourne l'URI depuis laquelle la requête est appelée
     * @return string
     */
    protected function getFromUri() : string
    {
        return 'admin/movies/film-2';
    }

    /**
     * Retourne l'URI du traitement du formulaire
     * @return string
     */
    protected function getFormUri() : string
    {
        return 'admin/movies/film-2';
    }

    /**
     * Retourne la méthode de la requête à appeler
     * @return string
     */
    protected function getFormMethod() : string
    {
        return 'patch';
    }

    /**
     * Retourne le message en cas de succès
     * @param array $formData
     * @return string
     */
    protected function getSuccessMessage(array $formData) : string
    {
        return sprintf('Le film \'%s\' a été mis à jour.', $formData['name']);
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
        $modelBefore = $this->getModelByPublicId('film-2');

        parent::testValidationFailure($formData, $errors);

        $modelAfter = $this->getModelByPublicId('film-2');

        $this->assertEquals($modelBefore, $modelAfter);
    }

    /**
     * Provider pour les tests de validation
     * @return array
     */
    public static function validationFailureProvider() : array
    {
        $data = parent::validationFailureProvider();

        unset($data['without-data']); // Suppression car le formulaire utilisera par défaut les valeurs du modèle édité

        return $data;
    }

}
