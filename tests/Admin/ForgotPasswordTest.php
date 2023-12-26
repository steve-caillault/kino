<?php

/**
 * Tests de la demande de réinitialisation de mot de passe du panneau d'administration
 */

namespace Tests\Admin;

use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\{
    DB, Password, Mail
};
/***/
use Tests\TestCase;
use App\Models\AdminUser;
use App\Mail\ResetPasswordMail;

final class ForgotPasswordTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        Mail::fake();

        // Création des utilisateurs
        AdminUser::factory()->create([
            'nickname' => 'admin-user',
            'email' => 'admin-user@kino.me',
            'password' => 'admin-user-password',
            'permissions' => [ 'ADMIN', ],
        ]);

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
            ->get('/admin/auth/forgot-password')
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
        $beginDatetime = new \DateTime(timezone: new \DateTimeZone('UTC'));

        $response = $this->followingRedirects()
            ->from('/admin/auth/forgot-password')
            ->post('/admin/auth/forgot-password', [
                'email' => $user->email,
            ])
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        // On vérifie le message Flash
        $expectedMessage = 'Un lien de réinitialisation de votre mot de passe a été envoyé à votre adresse.';
        $flashMessage = $crawler->filter('p.with-color.with-color-green')->text();
        $this->assertEquals($expectedMessage, $flashMessage);

        // On vérifie le titre qui doit correspondre à la page de connexion
        $expectedTitle = 'Connexion au site Kino';
        $title = $crawler->filter('title')->first()->text();
        $this->assertEquals($expectedTitle, $title);

        // On vérifie que la demande a été créé
        $this->assertDatabaseHas(
            table: 'password_resets',
            data: [
                [ 'email', '=',  $user->email, ],
                [ 'token', '!=', null, ],
                [ 'created_at', '>=', $beginDatetime, ],
            ],
        );

        // Vérifie que le mail est envoyé
        Mail::assertSent(ResetPasswordMail::class, 1);
    }

    /**
     * Test pour une demande trop rapprochée d'une autre
     * @return void
     */
    public function testTrottle() : void
    {
        $user = AdminUser::where('nickname', 'admin-user')->first();
        Password::createToken($user);

        $this->testValidationFailure([
            'email' => $user->email,
        ], [
            'email' => 'Veuillez patienter avant de recommencer.',
        ]);
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
        $countPasswordResets = DB::table('password_resets')->count();

        $response = $this->followingRedirects()
            ->from('/admin/auth/forgot-password')
            ->post('/admin/auth/forgot-password', $formParams)->assertStatus(200)
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

        // Vérifie qu'aucune réinitialisation de mot de passe n'a été créé
        $countNewPasswordResets = DB::table('password_resets')->count();
        $this->assertEquals($countPasswordResets, $countNewPasswordResets);

        // Vérifie que le mail n'est pasenvoyé
        Mail::assertNotSent(ResetPasswordMail::class);
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
                    'email' => 'L\'adresse email est nécessaire.',
                ],
            ],
            // Valeurs vides
            [
                [
                    'email' => '',
                ], [
                    'email' => 'L\'adresse email est nécessaire.',
                ],
            ],
            // La valeur n'est pas une adresse email
            [
                [
                    'email' => 'taratata',
                ], [
                    'email' => 'L\'adresse email n\'est pas valide.',
                ]
            ],
            // Adresse email inconnue
            [
                [
                    'email' => $faker->email(),
                ], [
                    'email' => 'La valeur email est incorrecte.',
                ],
            ],
        ];
    }

}
