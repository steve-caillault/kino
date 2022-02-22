<?php

/**
 * Tests de la gestion des exceptions
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\{
    Session
};
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\TokenMismatchException;
/***/
use Tests\{
    TestCase,
    WithDatabaseLogTrait
};
use App\Models\AdminUser;

final class ExceptionHandlerTest extends TestCase
{
    use WithDatabaseLogTrait;

    /**
     * Gestion d'une erreur 404
     * @return void
     */
    public function testNotFound() : void 
    {
        $expectedTitle = 'Une erreur 404 s\'est produite';
        $expectedMessage = 'Cette page n\'existe pas ou a été déplacée.';

        $path = '/' . $this->getFaker()->slug();
        $response = $this->followingRedirects()
            ->get($path)
            ->assertStatus(404)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        $pageTitle = $crawler->filter('h1')->text();
        $message = $crawler->filter('p')->text();

        $this->assertEquals($expectedTitle, $pageTitle);
        $this->assertEquals($expectedMessage, $message);

        // Vérification du log en base de données
        $this->getLastDatabaseLog('error', 'Cette page n\'existe pas ou a été déplacé.', new \DateTimeImmutable());
    }

    /**
     * Gestion d'une erreur de l'expiration du jeton CSRF
     * @return void
     */
    public function testCSRFTokenExpiration() : void
    {
        $user = AdminUser::factory()->create([
            'nickname' => 'admin-user',
            'password' => 'admin-user-password',
            'email' => 'admin-user@kino.me',
            'permissions' => [ 'ADMIN', ],
        ]);

        $response = $this->actingAs($user, 'admin')
            ->withSession([
                '_token'=> 'test-1',
                'must_checked_csrf_token' => true,
            ])
            ->from('admin/user')
            ->post('admin/user', [
                '_token' => 'test-2',
            ])
        ;

        // Vérifie qu'il y a une redirection avec le paramètre get
        $response->assertRedirect('admin/user?reason=csrfTokenExpired');

    }

}