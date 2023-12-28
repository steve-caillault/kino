<?php

/**
 * Tests de la liste des films
 */

namespace Tests\Admin\Movie;

use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use Tests\Admin\WithAdminUser;
use App\Models\Movie;

final class ListTest extends TestCase
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
    }

    /**
     * Test pour une liste vide
     * @return void
     */
    public function testEmpty() : void
    {
        $response = $this->actingAs($this->mainAdminUser, 'admin')
            ->followingRedirects()
            ->get('admin/movies')
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        $emptyMessage = $crawler->filter('p.empty-list')->text();
        $expectedMessage = 'Aucun film n\'a été trouvé.';
        $this->assertEquals($expectedMessage, $emptyMessage);
    }

    /**
     * Test pour une liste sans pagination
     * @return void
     */
    public function testWithoutPagination() : void
    {
        Movie::factory()->count(5)->create();

        $this->checkPageData(1);
    }

    /**
     * Test pour une liste avec pagination
     * @return void
     */
    public function testWithPagination() : void
    {
        Movie::factory()->count(45)->create();

        $this->checkPageData(1);
    }

    /**
     * Test de la page trois d'une liste
     * @return void
     */
    public function testWithPaginationPageThree() : void
    {
        Movie::factory()->count(45)->create();

        $this->checkPageData(3);
    }

    /**
     * Vérification des données de la page qui est données en paramètre
     * @param int $page
     * @return void
     */
    private function checkPageData(int $page) : void
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Données en base de données
        $expectedListData = Movie::orderBy('name', 'asc')->offset($offset)->limit($limit)->get()->map(fn(Movie $movie) => [
            'name' => $movie->name,
            'produced_at' => $movie->produced_at->format('c'),
            'edit_url' => url(sprintf('admin/movies/%s', $movie->public_id)),
        ]);

        $requestUrl = sprintf('admin/movies?page=%s', $page);

        // Requête HTTP
        $response = $this->actingAs($this->mainAdminUser, 'admin')
            ->followingRedirects()
            ->get($requestUrl)
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        $sections = $crawler->filter('section.collection-item');

        // Vérifie le nombre d'éléments
        $this->assertEquals($expectedListData->count(), $sections->count());

        // Vérification des données pour chaque élément
        foreach($sections as $sectionIndex => $section)
        {
            $expectedData = $expectedListData->get($sectionIndex);

            $movieName = trim($section->getElementsByTagName('h2')->item(0)->textContent);
            $movieProducedAt = $section->getElementsByTagName('time')->item(0)->getAttribute('datetime');
            $movieEditUrl = $section->getElementsByTagName('a')->item(0)->getAttribute('href');

            $currentData = [
                'name' => $movieName,
                'produced_at' => $movieProducedAt,
                'edit_url' => $movieEditUrl,
            ];

            $this->assertEquals($expectedData, $currentData);
        }
    }
}
