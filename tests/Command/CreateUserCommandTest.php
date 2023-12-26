<?php

/**
 * Tests de la commande de création d'utilisateur
 */

namespace Tests\Command;

use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Exception\RuntimeException;
/***/
use Tests\TestCase;
use App\Models\AdminUser;

final class CreateUserCommandTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Création d'un compte utilisateur
        $userAdmin = AdminUser::factory()->create([
            'nickname' => 'kino-admin',
            'email' => 'admin@kino.me',
            'permissions' => [ 'ADMIN' ],
        ]);

    }

    /**
     * Retourne le dernier utilisateur créé
     * @return ?AdminUser
     */
    private function getLastCreatedUser() : ?AdminUser
    {
        return AdminUser::orderBy('id', 'desc')->first();
    }

    /**
     * Création d'un utilisateur avec succès
     * @param array $params Paramètre pour la commande
     * @dataProvider successProvider
     * @return void
     */
    public function testSuccess(array $params) : void
    {
        // Récupére le dernier utilisateur créé
        $lastUser = $this->getLastCreatedUser();

        $app = $this->artisan('user:create', $params);
        $app->run();
        $app->assertSuccessful();

        $messageExpected = sprintf('L\'utilisateur %s a été créé.', $params['nickname']);
        $app->expectsOutput($messageExpected);

        // Récupére le dernier utilisateur créé
        $newUserCreated = $this->getLastCreatedUser();

        // Vérifie que le membre a été créé
        $this->assertNotNull($newUserCreated?->id);
        $this->assertNotEquals($lastUser?->id, $newUserCreated?->id);
        $this->assertEquals([
            'nickname' => $params['nickname'],
            'email' => $params['email'],
            'permissions' => json_decode(strtoupper(json_encode($params['permissions'])), true),
        ], [
            'nickname' => $newUserCreated?->nickname,
            'email' => $newUserCreated?->email,
            'permissions' => $newUserCreated?->permissions->jsonSerialize(),
        ]);

        // Vérifie le mot de passe
        $this->assertTrue(Hash::check($params['password'], $newUserCreated?->password));
    }

    /**
     * Provider pour les tests de succès
     * @return array
     */
    public static function successProvider() : array
    {
        $faker = self::getFaker();

        return [
            // Permission admin
            'admin' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ]
            ],

            // Permission admin et une autre permission : non disponible pour le moment
            /*'admin_and_another' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin', 'customer' ],
                ]
            ],

            // Permission autre que admin : non disponible pour le moment
            'other_than_admin' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ $faker->randomElement([ 'permission1', 'permission2', ]) ],
                ]
            ],

            // Permission autre que admin : non disponible pour le moment
            'others_than_admin' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => $faker->randomElements([ 'permission1', 'permission2', ]),
                ]
            ],*/
        ];

    }

    /**
     * Vérification des erreurs lors de la création d'un utilisateur
     * @param array $params Paramètres pour la commande
     * @param array $messagesExpected Messages de la console à vérifier 
     * @dataProvider failureValidationProvider
     * @return void
     */
    public function testValidationFailure(array $params, array $messagesExpected) : void
    {
        // $this->withoutMockingConsoleOutput();

        // Récupére le dernier utilisateur créé
        $lastUser = $this->getLastCreatedUser();

        try {
            $app = $this->artisan('user:create', $params);
            $app->run();
            $app->assertFailed();
            foreach($messagesExpected as $messageExpected)
            {
                $app->expectsOutput($messageExpected);
            }
        } catch(RuntimeException $exception) {
            $this->assertEquals(current($messagesExpected), $exception->getMessage());
        }

        $lastUserAfterCommand = $this->getLastCreatedUser();

        // Vérifie qu'aucun utilisateur n'a été créé
        $this->assertEquals([
            'id' => $lastUser->id,
            'nickname' => $lastUser->nickname,
            'email' => $lastUser->email,
        ], [
            'id' => $lastUserAfterCommand->id,
            'nickname' => $lastUserAfterCommand->nickname,
            'email' => $lastUserAfterCommand->email,
        ]);
        
    }

    /**
     * Provider pour les tests d'échec de la validation
     * @return array
     */
    public static function failureValidationProvider() : array
    {
        $faker = self::getFaker();

        return [
            // Paramètres manquants
            'missing_parameters' => [
                [], [ 'Not enough arguments (missing: "nickname, email, password, permissions").' ],
            ],

            // Paramètre vide
            'empty_parameters' => [
                array_fill_keys([ 'nickname', 'email', 'password', 'permissions' ], ''),
                [
                    'The nickname field is required.',
                    'The email field is required.',
                    'The password field is required.',
                    'The permissions field is required.',
                ]
            ],

            // Nom d'utilisateur trop court
            'nickname_too_short' => [
                [
                    'nickname' => 'kin',
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The nickname must be at least 5 characters.', 
                ]
            ],

            // Nom d'utilisateur trop long
            'nickname_too_long' => [
                [
                    'nickname' => $faker->slug(100),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The nickname may not be greater than 50 characters.', 
                ]
            ],

            // Nom d'utilisateur existant
            'nickname_already_exists' => [
                [
                    'nickname' => 'kino-admin',
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'member', 'test' ],
                ], [ 
                    'The nickname has already been taken.', 
                ]
            ],

            // Email trop court
            'email_too_short' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => 'a@b.com',
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The email must be at least 10 characters.', 
                ],
            ],

            // Email trop long
            'email_too_long' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->slug(20) . '.' . $faker->slug(20) . '@' . $faker->slug(20) . '.org',
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The email may not be greater than 100 characters.', 
                ],
            ],

            // Email incorrect
            'email_invalid' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => 'pommepoirejambon',
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The email must be a valid email address.', 
                ],
            ],

            // Email existant
            'email_already_exists' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => 'admin@kino.me',
                    'password' => $faker->password(8),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The email has already been taken.', 
                ],
            ],

            // Mot de passe trop court
            'password_too_short' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(2, 5),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The password must be at least 8 characters.', 
                ],
            ],

            // Mot de passe trop long
            'password_too_long' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(200) . $faker->password(200) . $faker->password(200),
                    'permissions' => [ 'admin' ],
                ], [ 
                    'The password may not be greater than 100 characters.', 
                ],
            ],

            // Permission incorrect (pas dans un tableau)
            'permission_incorrect' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => 'admin',
                ], [ 
                    'The permissions must be an array.', 
                ],
            ],

            // Permission inconnu
            'permission_unknown' => [
                [
                    'nickname' => $faker->slug(3),
                    'email' => $faker->email(),
                    'password' => $faker->password(8),
                    'permissions' => [ 'master', ],
                ], [ 
                    'The selected permissions is invalid.', 
                ],
            ],
        ];
    }

}
