<?php

/**
 * Tests de la gestion d'un film
 */

namespace Tests\Admin\Movie;

use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use Tests\Admin\WithAdminUser;
use App\Models\Movie;

abstract class AbstractMovieTestCase extends TestCase
{
    use WithAdminUser;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Création de l'utilisateur
        $this->initAdminUser();

        Movie::factory()->create([
            'public_id' => 'film-1',
            'name' => 'Film 1',
            'produced_at' => self::getFaker()->dateTimeBetween('-100 years', '-2 days'),
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
     * @return ?Movie
     */
    protected function getModelByPublicId(string $publicId) : ?Movie
    {
        return Movie::where('public_id', '=', $publicId)->first();
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

        return $this->actingAs($this->mainAdminUser, 'admin')
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
        $movie = $this->getModelByPublicId($formData['public_id']);
        $realData = [
            'public_id' => $movie->public_id,
            'name' => $movie->name,
            'produced_at' => $movie->produced_at->format('Y-m-d'),
        ];
        $this->assertEquals($formData, $realData);
    }

    /**
     * Provider pour les tests de succès
     * @return array
     */
    public static function successProvider() : array
    {
        $faker = self::getFaker();

        $totalPlaces = $faker->numberBetween(20, 1000);

        return [
            [
                [
                    'public_id' => substr($faker->slug(), 0, 25),
                    'name' => substr($faker->name(), 0, 25),
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
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
    public static function validationFailureProvider() : array
    {
        $faker = self::getFaker();

        return [
            // Sans donnée
            'without-data' => [
                [], [
                    'public_id' => 'L\'identifiant public est nécessaire.',
                    'name' => 'Le nom est nécessaire.',
                    'produced_at' => 'La date de production est nécessaire.',
                ],
            ],
            // Données vides
            'empty-data' => [
                [
                    'public_id' => '',
                    'name' => '',
                    'produced_at' => '',
                ], [
                    'public_id' => 'L\'identifiant public est nécessaire.',
                    'name' => 'Le nom est nécessaire.',
                    'produced_at' => 'La date de production est nécessaire.',
                ],
            ],
            // Identifiant public trop court
            'public-id-too-short' => [
                [
                    'public_id' => '2',
                    'name' => $faker->text(75),
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'public_id' => 'L\'identifiant public doit avoir au moins 5 caractères.',
                ],
            ],
            // Identifiant public trop long
            'public-id-too-long' => [
                [
                    'public_id' => $faker->realTextBetween(50),
                    'name' => $faker->text(75),
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'public_id' => 'L\'identifiant public doit avoir au plus 100 caractères.',
                ],
            ],
            // L'identifiant public existe déjà
            'public-id-already-exists' => [
                [
                    'public_id' => 'film-1',
                    'name' => $faker->text(75),
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'public_id' => 'Cet identifiant est déjà utilisé.',
                ],
            ],
            // Nom trop court
            'name-too-short' => [
                [
                    'public_id' => 'film-2',
                    'name' => '2',
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'name' => 'Le nom doit avoir au moins 5 caractères.',
                ],
            ],
            // Nom trop long
            'name-too-long' => [
                [
                    'public_id' => 'film-2',
                    'name' => $faker->realTextBetween(50),
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'name' => 'Le nom doit avoir au plus 100 caractères.',
                ],
            ],
            // Le nom existe déjà
            'name-already-exists' => [
                [
                    'public_id' => 'film-2',
                    'name' => 'Film 1',
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d'),
                ], [
                    'name' => 'Ce nom est déjà utilisé.',
                ],
            ],
            // La date de production n'est pas une date
            'produced-at-not-date' => [
                [
                    'public_id' => 'film-2',
                    'name' => 'Film 2',
                    'produced_at' => 'A',
                ], [
                    'produced_at' => 'La date de production est incorrecte.',
                ],
            ],
            // Le format de la date de production est incorrect
            'produced-at-bad-format' => [
                [
                    'public_id' => 'film-2',
                    'name' => 'Film 2',
                    'produced_at' => $faker->dateTimeBetween('-100 years', '-2 days')->format('Y-m-d H:i:s'),
                ], [
                    'produced_at' => 'La date de production est incorrecte.',
                ],
            ],
            // La date de production est trop ancienne
            'produced-at-early' => [
                [
                    'public_id' => 'film-2',
                    'name' => 'Film 2',
                    'produced_at' => '1789-07-14',
                ], [
                    'produced_at' => 'La date de production ne peut pas être antérieure au 1895-03-19.',
                ],
            ],

            // La date de production est trop tardive
            'produced-at-late' => [
                [
                    'public_id' => 'film-2',
                    'name' => 'Film 2',
                    'produced_at' => '9999-12-31',
                ], [
                    'produced_at' => 'La date de production ne peut pas être postérieure au 9999-12-31.',
                ],
            ],
        ];
    }

}
