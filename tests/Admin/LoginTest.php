<?php

/**
 * Tests de l'authentification au panneau d'administration
 */

namespace Tests\Admin;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use App\Models\AdminUser;

final class LoginTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        // Création des utilisateurs
        AdminUser::factory()->state(new Sequence(
            [
                'nickname' => 'admin-user',
                'password' => 'admin-user-password',
                'permissions' => [ 'ADMIN', ],
            ],
            [
                'nickname' => 'not-admin-user',
                'password' => 'not-admin-user-password',
                'permissions' => [ 'CUSTOMER', ],
            ]
        ))->count(2)->create();

    }

    /**
     * Test si l'utilisateur est redirigé vers la page d'authentification
     * @return void
     */
    public function testAdminRedirect() : void
    {
        $response = $this->followingRedirects()->get('/admin')->assertStatus(200);

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie juste le titre
        $h1 = $crawler->filter('h1')->first()->text();
        $this->assertEquals('Connexion au site Kino', $h1);
    }

    /**
     * Test pour un utilisateur qui n'a pas les droits d'accèder au panneau d'administration
     * @return void
     */
    public function testNotAdministrator() : void
    {
        // On vérifie juste le code HTTP
        $this->followingRedirects()
            ->from('/admin/auth/login')
            ->post('/admin/auth/login', [
                'nickname' => 'not-admin-user',
                'password' => 'not-admin-user-password',
            ])->assertStatus(403);
    }

    /**
     * Test pour un administrateur
     * @return void
     */
    public function testAdministrator() : void
    {
        $response = $this->followingRedirects()
            ->from('/admin/auth/login')
            ->post('/admin/auth/login', [
                'nickname' => 'admin-user',
                'password' => 'admin-user-password',
            ])->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le titre qui doit correspondre à la page d'accueil du panneau d'administration
        $expectedTitle = 'Panneau d\'administration du site Kino';
        $title = $crawler->filter('title')->first()->text();
        $this->assertEquals($expectedTitle, $title);
    }

    /**
     * Test d'erreur de validation
     * @param array $formParams Paramètres du formulaire
     * @param array $errors Erreurs du formulaire attendu
     * @dataProvider failureValidationProvider
     * @return void
     */
    public function testValidationFailure(array $formParams, array $errors) : void
    {
        $response = $this->followingRedirects()
            ->from('/admin/auth/login')
            ->post('/admin/auth/login', $formParams)->assertStatus(200)
        ;

        // Vérification des messages d'erreurs
        if(count($errors) > 0)
        {
            $responseString = $response->getContent();
            $crawler = new Crawler($responseString);
            foreach($errors as $fieldName => $errorExpected)
            {
                $fieldSelector = '[name="' . $fieldName . '"]';
                $error = $crawler->filter($fieldSelector)->closest('div.form-input')->filter('p.error')->text();
                $this->assertEquals($errorExpected, $error);
            }
        }
    }

    /**
     * Provider pour les erreurs de validation
     * @return array
     */
    public static function failureValidationProvider() : array
    {
        $faker = self::getFaker();

        return [
            // Formulaire vide
            [
                [], [
                    'nickname' => 'Le nom d\'utilisateur est nécessaire.',
                    'password' => 'Le mot de passe est nécessaire.',
                ],
            ],
            // Valeurs vides
            [
                [
                    'nickname' => '',
                    'password' => '',
                ], [
                    'nickname' => 'Le nom d\'utilisateur est nécessaire.',
                    'password' => 'Le mot de passe est nécessaire.',
                ],
            ],
            // Nom d'utilisateur incorrect
            [
                [
                    'nickname' => $faker->userName(),
                    'password' => 'admin-user-password',
                ], [
                    'nickname' => 'Identifiants incorrects.',
                ],
            ],
            // Mot de passe incorrect
            [
                [
                    'nickname' => 'admin-user',
                    'password' => $faker->password(),
                ], [
                    'nickname' => 'Identifiants incorrects.',
                ],
            ],
        ];
    }

}
