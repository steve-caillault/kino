<?php

/**
 * Tests de l'édition du compte connecté au panneau d'administration
 */

namespace Tests\Admin\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use App\Models\AdminUser;

final class EditTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        $faker = $this->getFaker();

        // Création des utilisateurs
        AdminUser::factory()->state(new Sequence(
            [
                'nickname' => 'admin-user',
                'password' => 'admin-user-password',
                'email' => 'admin-user@kino.me',
                'permissions' => [ 'ADMIN', ],
            ],
            [
                'nickname' => 'admin-user-with-name',
                'password' => 'admin-user-with-name-password',
                'email' => 'admin-user-with-name@kino.me',
                'first_name' => $faker->realTextBetween(5, 100),
                'last_name' => $faker->realTextBetween(5, 100),
                'permissions' => [ 'ADMIN', ],
            ]
        ))->count(2)->create();
    }

    /**
     * Test de l'enregistrement des données
     * @param array $formParams
     * @param string $originalNickname
     * @param string $passwordExpected
     * @dataProvider successProvider
     * @return void
     */
    public function testSuccess(array $formParams, string $originalNickname, string $passwordExpected) : void
    {
        $formParams['form_name'] = 'admin-user-settings';

        $user = AdminUser::where('nickname', '=', $originalNickname)->first();
        $userBeforeCalling = clone $user;

        $response = $this->actingAs($user, 'admin')
            ->followingRedirects()
            ->from('admin/user')
            ->post('admin/user', $formParams)
            ->assertStatus(200);

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le message Flash
        $expectedMessage = 'Votre compte a été mis à jour.';
        $flashMessage = $crawler->filter('p.with-color.with-color-green')->text();
        $this->assertEquals($expectedMessage, $flashMessage);

        // Vérification des données
        $userAfterCalling = AdminUser::where('id', '=', $userBeforeCalling->id)->first();
        $expectedData = [
            'nickname' => $formParams['nickname'] ?? $userBeforeCalling->nickname,
            'email' => $formParams['email'] ?? $userBeforeCalling->email,
            'first_name' => $formParams['first_name'] ?? $userBeforeCalling->first_name,
            'last_name' => $formParams['last_name'] ?? $userBeforeCalling->last_name,
            'permissions' => $userBeforeCalling->permissions,
        ];
        $realData = [
            'nickname' => $userAfterCalling->nickname,
            'email' => $userAfterCalling->email,
            'first_name' => $userAfterCalling->first_name,
            'last_name' => $userAfterCalling->last_name,
            'permissions' => $userAfterCalling->permissions,
        ];
        $this->assertEquals($expectedData, $realData);

        // Vérification du mot de passe
        $this->assertTrue(Hash::check($passwordExpected, $userAfterCalling?->password));
    }

    /**
     * Provider pour les tests de succès
     * @return array
     */
    public function successProvider() : array
    {
        $faker = $this->getFaker();

        return [
            // Test sans modification
            'without-modification' => [
                [], 'admin-user-with-name', 'admin-user-with-name-password',
            ],
            // Affectation du nom et du prénom seulement
            'set-name' => [
                [
                    'first_name' => $faker->realTextBetween(5, 100),
                    'last_name' => $faker->realTextBetween(5, 100),
                ], 'admin-user', 'admin-user-password',
            ],
            // Modification du nom et du prénom seulementtartellete
            'change-name' => [
                [
                    'first_name' => $faker->realTextBetween(5, 100),
                    'last_name' => $faker->realTextBetween(5, 100),
                ], 'admin-user-with-name', 'admin-user-with-name-password',
            ],
            // Modification du nom d'utilisateur
            'change-nickname' => [
                [
                    'nickname' => 'admin-user-2'
                ], 'admin-user-with-name', 'admin-user-with-name-password',
            ],
            // Modification du mot de passe
            'change-password' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => 'tartelette',
                    'new_password_confirmation' => 'tartelette',
                ], 'admin-user-with-name', 'tartelette',
            ],
            // Modification de toutes les données
            'change-all-data' => [
                [
                    'nickname' => 'admin-user-1',
                    'email' => 'admin-user-1@kino.me',
                    'first_name' => 'Pomme',
                    'last_name' => 'Poire',
                    'current_password' => 'admin-user-password',
                    'new_password' => 'petite tartelette',
                    'new_password_confirmation' => 'petite tartelette',
                ], 'admin-user', 'petite tartelette',
            ],
        ];
    }

    /**
     * Test d'échec de la validation des données
     * @param array $formParams
     * @param array $errors
     * @param string $originalNickname
     * @dataProvider validationFailureProvider
     * @return void
     */
    public function testValidationFailure(array $formParams, array $errors, string $originalNickname) : void
    {
        $formParams['form_name'] = 'admin-user-settings';

        $user = AdminUser::where('nickname', '=', $originalNickname)->first();
        $userBeforeCalling = clone $user;

        $response = $this->actingAs($user, 'admin')
            ->followingRedirects()
            ->from('admin/user')
            ->post('admin/user', $formParams)
            ->assertStatus(200);

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

        // On vérifie que les données n'ont pas été modifié
        $userAfterCalling = AdminUser::where('id', '=', $userBeforeCalling->id)->first();
        $this->assertEquals($userBeforeCalling, $userAfterCalling);
    }

    /**
     * Provider pour les tests de validation
     * @return array
     */
    public function validationFailureProvider() : array
    {
        $faker = $this->getFaker();

        $normalPassword = $faker->password(10);
        $longPassword = $faker->realTextBetween();

        return [
            // Test avec des valeurs vide
            'empty-data' => [
                [
                    'nickname' => '',
                    'email' => '',
                    'first_name' => '',
                    'last_name' => '',
                ], [
                    'nickname' => 'Le nom d\'utilisateur est nécessaire.',
                    'email' => 'L\'adresse email est nécessaire.',
                    'first_name' => 'Le prénom est nécessaire.',
                    'last_name' => 'Le nom est nécessaire.',
                ], 'admin-user-with-name',
            ],
            // Nom d'utilisateur trop court
            'nickname-too-short' => [
                [
                    'nickname' => 'pom',
                ], [
                    'nickname' => 'Le nom d\'utilisateur doit avoir au moins 5 caractères.',
                ], 'admin-user-with-name',
            ],
            // Nom d'utilisateur trop long
            'nickname-too-long' => [
                [
                    'nickname' => $faker->slug(200),
                ], [
                    'nickname' => 'Le nom d\'utilisateur doit avoir au plus 50 caractères.',
                ], 'admin-user-with-name',
            ],

            // Nom d'utilisateur déjà utilisé
            'nickname-already-exists' => [
                [
                    'nickname' => 'admin-user',
                ], [
                    'nickname' => 'Ce nom d\'utilisateur est déjà utilisé.',
                ], 'admin-user-with-name',
            ],

            // Adresse email trop courte
            'email-too-short' => [
                [
                    'email' => 'pom',
                ], [
                    'email' => 'L\'adresse email doit avoir au moins 5 caractères.',
                ], 'admin-user-with-name',
            ],

            // Adresse email trop longue
            'email-too-long' => [
                [
                    'email' => $faker->realTextBetween(),
                ], [
                    'email' => 'L\'adresse email doit avoir au plus 100 caractères.',
                ], 'admin-user-with-name',
            ],

            // Adresse email incorrecte
            'email-invalid' => [
                [
                    'email' => 'popopom',
                ], [
                    'email' => 'L\'adresse email n\'est pas valide.',
                ], 'admin-user-with-name',
            ],

            // Adresse email déjà utilisée
            'email-already-exists' => [
                [
                    'email' => 'admin-user@kino.me',
                ], [
                    'email' => 'Cette adresse email est déjà utilisée.',
                ], 'admin-user-with-name',
            ],

            // Prénom trop court
            'first-name-too-short' => [
                [
                    'first_name' => 'Pom',
                ], [
                    'first_name' => 'Le prénom doit avoir au moins 5 caractères.',
                ], 'admin-user-with-name',
            ],

            // Prénom trop long
            'first-name-too-long' => [
                [
                    'first_name' => $faker->realTextBetween(),
                ], [
                    'first_name' => 'Le prénom doit avoir au plus 100 caractères.',
                ], 'admin-user-with-name',
            ],

            // Nom trop court 
            'last-name-too-short' => [
                [
                    'last_name' => 'Pom',
                ], [
                    'last_name' => 'Le nom doit avoir au moins 5 caractères.',
                ], 'admin-user-with-name',
            ],

            // Nom trop long
            'last-name-too-long' => [
                [
                    'last_name' => $faker->realTextBetween(),
                ], [
                    'last_name' => 'Le nom doit avoir au plus 100 caractères.',
                ], 'admin-user-with-name',
            ],

            // Mot de passe courant requis
            'current-password-required' => [
                [
                    'new_password' => $normalPassword,
                    'new_password_confirmation' => $normalPassword,
                ], [
                    'current_password' => 'Votre mot de passe actuel est requis.',
                ], 'admin-user-with-name',
            ],

             // Mot de passe courant vide
             'current-password-empty' => [
                [
                    'current_password' => '',
                    'new_password' => $normalPassword,
                    'new_password_confirmation' => $normalPassword,
                ], [
                    'current_password' => 'Votre mot de passe actuel est requis.',
                ], 'admin-user-with-name',
            ],

            // Mot de passe courant incorrect
            'current-password-incorrect' => [
                [
                    'current_password' => 'piment-de-cayenne',
                    'new_password' => $normalPassword,
                    'new_password_confirmation' => $normalPassword,
                ], [
                    'current_password' => 'Le mot de passe est incorrect.',
                ], 'admin-user-with-name',
            ],

            // Mots de passe courant et nouveau identiques
            'same-passwords' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => 'admin-user-with-name-password',
                    'new_password_confirmation' => 'admin-user-with-name-password',
                ], [
                    'new_password' => 'Votre nouveau mot de passe doit être diffèrent de l\'actuel.',
                ], 'admin-user-with-name',
            ],

            // Nouveau mot de passe trop court
            'password-too-short' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => 'pom',
                    'new_password_confirmation' => 'pom',
                ], [
                    'new_password' => 'Le mot de passe doit avoir au moins 8 caractères.',
                ], 'admin-user-with-name',
            ],

            // Nouveau mot de passe trop long
            'password-too-long' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => $longPassword,
                    'new_password_confirmation' => $longPassword,
                ], [
                    'new_password' => 'Le mot de passe doit avoir au plus 100 caractères.',
                ], 'admin-user-with-name',
            ],

            // Confirmation du mot de passe vide
            'password-confirmation-empty' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => $faker->password(8),
                    'new_password_confirmation' => '',
                ], [
                    'new_password' => 'Les deux mots de passe doivent être identiques.',
                ], 'admin-user-with-name',
            ],

            // Confirmation du mot de passe incorrect
            'password-confirmation-incorrect' => [
                [
                    'current_password' => 'admin-user-with-name-password',
                    'new_password' => $faker->password(8),
                    'new_password_confirmation' => $faker->password(8),
                ], [
                    'new_password' => 'Les deux mots de passe doivent être identiques.',
                ], 'admin-user-with-name',
            ],
        ];
    }
}