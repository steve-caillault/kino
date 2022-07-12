<?php

/**
 * Tests de la gestion d'une salle de cinéma
 */

namespace Tests\Admin\MovieRoom;

use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use App\Models\{
    AdminUser,
    MovieRoom
};

abstract class AbstractMovieRoomTest extends TestCase
{

    /**
     * Utilisateur connecté
     * @var AdminUser
     */
    private AdminUser $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        // Création de l'utilisateur
        $this->user = AdminUser::factory()->create([
            'nickname' => 'admin-user',
            'password' => 'admin-user-password',
            'email' => 'admin-user@kino.me',
            'permissions' => [ 'ADMIN', ],
        ]);

        MovieRoom::factory()->create([
            'public_id' => 'salle-1',
            'name' => 'Salle 1',
        ]);
    }

    /**
     * Retourne l'URI depuis laquelle la requête est appelé
     * @return string
     */
    abstract protected function getFromUri() : string;

    /**
     * Retourne l'URI du traitement du formulaire
     * @return string
     */
    abstract protected function getFormUri() : string;

    /**
     * Retourne la méthode de la requête à appeler
     * @return string
     */
    abstract protected function getFormMethod() : string;

    /**
     * Retourne le modèle à vérifier après l'appel
     * @return ?MovieRoom
     */
    protected function getModelByPublicId(string $publicId) : ?MovieRoom
    {
        return MovieRoom::where('public_id', '=', $publicId)->first();
    }

    /**
     * Retourne le message en cas de succès
     * @param array $formData
     * @return string
     */
    abstract protected function getSuccessMessage(array $formData) : string;

    /**
     * Retourne la réponse de l'appel au contrôleur
     * @param array $formParams Valeur du formulaire
     * @return \Illuminate\Testing\TestResponse
     */
    private function getAttemptCallingResponse(array $formParams = []) : \Illuminate\Testing\TestResponse
    {
        $formMethod = $this->getFormMethod();

        return $this->actingAs($this->user, 'admin')
            ->followingRedirects()
            ->from($this->getFromUri())
            ->{ $formMethod }($this->getFormUri(), $formParams)
            ->assertStatus(200);
    }

    /**
     * Test de l'enregistrement des données
     * @param array $formData
     * @dataProvider successProvider
     * @return void
     */
    public function testSuccess(array $formData) : void
    {
        $response = $this->getAttemptCallingResponse($formData);

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le message Flash
        $expectedMessage = $this->getSuccessMessage($formData);
        $flashMessage = $crawler->filter('p.with-color.with-color-green')->text();
        $this->assertEquals($expectedMessage, $flashMessage);

        // Vérification des données
        $movieRoom = $this->getModelByPublicId($formData['public_id']);
        $realData = [
            'public_id' => $movieRoom->public_id,
            'name' => $movieRoom->name,
            'floor' => $movieRoom->floor,
            'nb_places' => $movieRoom->nb_places,
            'nb_handicap_places' => $movieRoom->nb_handicap_places,
        ];
        $this->assertEquals($formData, $realData);
    }

    /**
     * Provider pour les tests de succès
     * @return array
     */
    public function successProvider() : array
    {
        $faker = $this->getFaker();

        $totalPlaces = $faker->numberBetween(20, 1000);

        return [
            [
                [
                    'public_id' => substr($faker->slug(), 0, 25),
                    'name' => substr($faker->name(), 0, 25),
                    'floor' => $faker->numberBetween(-10, 10),
                    'nb_places' => $totalPlaces,
                    'nb_handicap_places' => $faker->numberBetween(20, $totalPlaces),
                ],
            ],
        ];
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
        $response = $this->getAttemptCallingResponse($formData);

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le message Flash
        $expectedMessage = 'Il y a des valeurs incorrectes dans le formulaire.';
        $flashMessage = $crawler->filter('p.with-color.with-color-red')->text();
        $this->assertEquals($expectedMessage, $flashMessage);

        // On vérifie les messages d'erreur
        foreach($errors as $fieldName => $errorExpected)
        {
            $fieldSelector = '[name="' . $fieldName . '"]';
            $error = $crawler->filter($fieldSelector)->closest('div.form-input')->filter('p.error')->text();
            $this->assertEquals($errorExpected, $error);
        }
    }

    /**
     * Provider pour les tests de validation
     * @return array
     */
    public function validationFailureProvider() : array
    {
        $faker = $this->getFaker();

        return [
            // Sans données
            'without-data' => [
                [], [
                    'public_id' => 'L\'identifiant public est nécessaire.',
                    'name' => 'Le nom est nécessaire.',
                    'floor' => 'L\'étage est nécessaire.',
                    'nb_places' => 'Le nombre de places est nécessaire.',
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap est nécessaire.',
                ],
            ],
            // Données vides
            'empty-data' => [
                [
                    'public_id' => '',
                    'name' => '',
                    'floor' => '',
                    'nb_places' => '',
                    'nb_handicap_places' => '',
                ], [
                    'public_id' => 'L\'identifiant public est nécessaire.',
                    'name' => 'Le nom est nécessaire.',
                    'floor' => 'L\'étage est nécessaire.',
                    'nb_places' => 'Le nombre de places est nécessaire.',
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap est nécessaire.',
                ],
            ],
            // Identifiant public trop court
            'public-id-too-short' => [
                [
                    'public_id' => '2',
                    'name' => 'Salle 2',
                    'floor' => 0,
                    'nb_places' => 25,
                    'nb_handicap_places' => 25,
                ], [
                    'public_id' => 'L\'identifiant public doit avoir au moins 5 caractères.',
                ],
            ],
            // Identifiant public trop long
            'public-id-too-long' => [
                [
                    'public_id' => $faker->realTextBetween(50),
                    'name' => 'Salle 2',
                    'floor' => -3,
                    'nb_places' => 100,
                    'nb_handicap_places' => 80,
                ], [
                    'public_id' => 'L\'identifiant public doit avoir au plus 25 caractères.',
                ],
            ],
            // L'identifiant public existe déjà
            'public-id-already-exists' => [
                [
                    'public_id' => 'salle-1',
                    'name' => 'Salle 2',
                    'floor' => 1,
                    'nb_places' => 300,
                    'nb_handicap_places' => 150,
                ], [
                    'public_id' => 'Cet identifiant est déjà utilisé.',
                ],
            ],
            // Nom trop court
            'name-too-short' => [
                [
                    'public_id' => 'salle-2',
                    'name' => '2',
                    'floor' => 5,
                    'nb_places' => 480,
                    'nb_handicap_places' => 400,
                ], [
                    'name' => 'Le nom doit avoir au moins 5 caractères.',
                ],
            ],
            // Nom trop long
            'name-too-long' => [
                [
                    'public_id' => 'salle-2',
                    'name' => $faker->realTextBetween(50),
                    'floor' => 7,
                    'nb_places' => 284,
                    'nb_handicap_places' => 142,
                ], [
                    'name' => 'Le nom doit avoir au plus 25 caractères.',
                ],
            ],
            // Le nom existe déjà
            'name-already-exists' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 1',
                    'floor' => 10,
                    'nb_places' => 1000,
                    'nb_handicap_places' => 1000,
                ], [
                    'name' => 'Ce nom est déjà utilisé.',
                ],
            ],
            // L'étage n'est pas un nombre
            'floor-not-number' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => 'A',
                    'nb_places' => 156,
                    'nb_handicap_places' => 75,
                ], [
                    'floor' => 'L\'étage doit être un nombre.',
                ],
            ],

            // L'étage est trop petit
            'floor-too-small' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -11,
                    'nb_places' => 50,
                    'nb_handicap_places' => 10,
                ], [
                    'floor' => 'L\'étage ne peut pas être inférieur à -10.',
                ],
            ],
            // L'étage est trop grand
            'floor-too-big' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => 11,
                    'nb_places' => 32,
                    'nb_handicap_places' => 32,
                ], [
                    'floor' => 'L\'étage ne peut pas être supérieur à 10.',
                ],
            ],
            // Le nombre de places n'est pas un entier
            'nb-places-not-integer' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 'a',
                    'nb_handicap_places' => 32,
                ], [
                    'nb_places' => 'Le nombre de places doit être un nombre.',
                ],
            ],
            // Le nombre de places est trop petit
            'nb-places-too-small' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 10,
                    'nb_handicap_places' => 5,
                ], [
                    'nb_places' => 'Le nombre de places ne peut pas être inférieur à 20.',
                ],
            ],
            // Le nombre de places est trop grand
            'nb-places-too-big' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 1200,
                    'nb_handicap_places' => 500,
                ], [
                    'nb_places' => 'Le nombre de places ne peut pas être supérieur à 1000.',
                ],
            ],
            // Le nombre de places adaptées à un handicap n'est pas un entier
            'nb-handicap-places-not-integer' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 252,
                    'nb_handicap_places' => 'a',
                ], [
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap doit être un nombre.',
                ],
            ],
            // Le nombre de places adaptées à un handicap est trop petit
            'nb-handicap-places-too-small' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 252,
                    'nb_handicap_places' => 5,
                ], [
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap ne peut pas être inférieur à 20.',
                ],
            ],
            // Le nombre de places adaptées à un handicap est trop grand
            'nb-handicap-places-too-big' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 1001,
                    'nb_handicap_places' => 1001,
                ], [
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap ne peut pas être supérieur à 1000.',
                ],
            ],
            // Le nombre de places adaptées à un handicap est plus grand que le nombre de places
            'nb-handicap-places-greater-than-nb-places' => [
                [
                    'public_id' => 'salle-2',
                    'name' => 'Salle 2',
                    'floor' => -2,
                    'nb_places' => 250,
                    'nb_handicap_places' => 300,
                ], [
                    'nb_handicap_places' => 'Le nombre de places adaptées à un handicap ne peut pas être supérieur à 250.',
                ],
            ],
        ];
    }

}