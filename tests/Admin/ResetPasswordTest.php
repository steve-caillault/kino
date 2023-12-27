<?php

/**
 * Tests de la demande de réinitialisation de mot de passe du panneau d'administration
 */

namespace Tests\Admin;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\{
    DB, Password, Mail, Hash
};
/***/
use Tests\TestCase;
use App\Models\AdminUser;
use App\Mail\ResetPasswordMail;

final class ResetPasswordTest extends TestCase
{
    private const TOKEN = 'a770645965be316e9d0f94e829af1a5847c6cb89d977c499baecbf2079032b4c';

    /**
     * Identifiant du jeton créé
     * @var int
     */
    private int $tokenId;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        // Création des utilisateurs
        $user = AdminUser::factory()->create([
            'nickname' => 'admin-user',
            'email' => 'admin-user@kino.me',
            'password' => 'admin-user-password',
            'permissions' => [ 'ADMIN', ],
        ]);

        // Ajoute le token en base de données
        $this->tokenId = DB::table('password_resets')->insertGetId([
            'email' => $user->email,
            'token' => Hash::make(self::TOKEN),
            'created_at' => \Carbon\CarbonImmutable::now(),
        ]);
    }

    /**
     * Retourne l'URI de réinitialisation à tester
     * @return string
     */
    private function getResetUri() : string
    {
        return sprintf('/admin/auth/reset-password/%s', self::TOKEN);
    }

    /**
     * Test pour un administrateur
     * @return void
     */
    public function testAdministrator() : void
    {
        $user = AdminUser::where('nickname', 'admin-user')->first();

        $response = $this->followingRedirects()
            ->actingAs($user, 'admin')
            ->get($this->getResetUri())
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le titre qui doit correspondre à la page d'accueil du panneau d'administration
        $expectedTitle = 'Panneau d\'administration du site Kino';
        $title = $crawler->filter('title')->first()->text();
        $this->assertEquals($expectedTitle, $title);
    }

    /**
     * Test de succès
     * @return void
     */
    public function testSuccess() : void
    {
        $user = AdminUser::where('nickname', 'admin-user')->first();

        $password = $this->getFaker()->password(minLength: 10);

        $response = $this->followingRedirects()
            ->from($this->getResetUri())
            ->post($this->getResetUri(), [
                'token' => self::TOKEN,
                'email' => $user->email,
                'password' => $password,
                'password_confirmation' => $password,
            ])
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le message Flash
        $expectedMessage = 'Votre mot de passe a été réinitialisé.';
        $flashMessage = $crawler->filter('p.with-color.with-color-green')->text();
        $this->assertEquals($expectedMessage, $flashMessage);

        // On vérifie le titre qui doit correspondre à l'index du panneau d'administration
        $expectedTitle = 'Panneau d\'administration du site Kino';
        $title = $crawler->filter('title')->first()->text();
        $this->assertEquals($expectedTitle, $title);
        
        // On vérifie que la demande a été supprimé
        $this->assertDatabaseMissing(
            table: 'password_resets',
            data: [
                'email' => $user->email,
            ],
        );

        // On vérifie que le mot de passe a été modifié
        $newUser = AdminUser::where('nickname', 'admin-user')->first();

        $this->assertTrue(Hash::check($password, $newUser->password));
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
        $resetRequest = DB::table('password_resets')->where('id', $this->tokenId)->first([
            'email', 'token', 'created_at'
        ]);

        $user = AdminUser::where('nickname', 'admin-user')->first();
        $password = $user->password;

        $response = $this->followingRedirects()
            ->from($this->getResetUri())
            ->post($this->getResetUri(), $formParams)->assertStatus(200)
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

        // Vérifie que le mot de passe n'a pas changé
        $userAfter = AdminUser::where('nickname', 'admin-user')->first();
        $this->assertEquals($password, $userAfter->password);

        // Vérifie que la réinitialisation du mot de passe n'a pas changé
        $newResetRequest = DB::table('password_resets')->where('id', $this->tokenId)->first([
            'email', 'token', 'created_at'
        ]);
        $this->assertEquals($resetRequest, $newResetRequest);
    }

    /**
     * Provider pour les erreurs de validation
     * @return array
     */
    public static function failureValidationProvider() : array
    {
        $faker = self::getFaker();
        $email = 'admin-user@kino.me';
        $password = $faker->password(minLength: 10);
        $token = self::TOKEN;

        return [
            // Formulaire vide
            [
                [], [
                    'email' => 'L\'adresse email est nécessaire.',
                    'password' => 'Le mot de passe est nécessaire.',
                ],
            ],
            // Valeurs vides
            [
                [
                    'email' => '',
                ], [
                    'email' => 'L\'adresse email est nécessaire.',
                    'password' => 'Le mot de passe est nécessaire.',             
                ],
            ],
            // Token incorrect
            [
                [
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $password,
                    'token' => $faker->slug(),
                ], [
                    'email' => 'Le jeton du mot de passe est incorrect.',              
                ],
            ],
            // Adresse email incorrect
            [
                [
                    'email' => 'taratata',
                    'password' => $password,
                    'password_confirmation' => $password,
                    'token' => $token,
                ], [
                    'email' => 'L\'adresse email n\'est pas valide.',
                ]
            ],
            // Adresse email inconnue
            [
                [
                    'email' => $faker->email(),
                    'password' => $password,
                    'password_confirmation' => $password,
                    'token' => $token,
                ], [
                    'email' => 'Aucun utilisateur n\'utilise cette adresse.',
                ],
            ],
            // Mot de passe trop court
            [
                [
                    'email' => $email,
                    'password' => 'pom',
                    'password_confirmation' => 'pom',
                    'token' => $token,
                ], [
                    'password' => 'Le mot de passe doit avoir au moins 8 caractères.',
                ],
            ],
            // Les deux mots de passe ne correspondent pas 
            [
                [
                    'email' => $email,
                    'password' => $faker->password(),
                    'password_confirmation' => $faker->password(),
                    'token' => $token,
                ], [
                    'password' => 'Les deux mots de passe doivent être identiques.',
                ],
            ],
        ];
    }

}
