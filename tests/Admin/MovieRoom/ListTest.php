<?php

/**
 * Tests de la liste des salles
 */

namespace Tests\Admin\MovieRoom;

use Symfony\Component\DomCrawler\Crawler;
/***/
use Tests\TestCase;
use Tests\Admin\WithAdminUser;
use App\Models\MovieRoom;

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
            ->get('admin/movie-rooms')
            ->assertStatus(200)
        ;

        $responseString = $response->getContent();
        $crawler = new Crawler($responseString);

        $emptyMessage = $crawler->filter('p.empty-list')->text();
        $expectedMessage = 'Aucune salle de cinéma n\'a été trouvée.';
        $this->assertEquals($expectedMessage, $emptyMessage);
    }

    /**
     * Test pour une liste sans pagination
     * @return void
     */
    public function testWithoutPagination() : void
    {
        MovieRoom::factory()->count(5)->create();

        $this->checkPageData(1);
    }

    /**
     * Test pour une liste avec pagination
     * @return void
     */
    public function testWithPagination() : void
    {
        MovieRoom::factory()->count(45)->create();

        $this->checkPageData(1);
    }

    /**
     * Test de la page trois d'une liste
     * @return void
     */
    public function testWithPaginationPageThree() : void
    {
        MovieRoom::factory()->count(45)->create();

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
        $expectedListData = MovieRoom::orderBy('name', 'asc')->offset($offset)->limit($limit)->get()->map(fn(MovieRoom $movieRoom) => [
            'name' => $movieRoom->name,
            'floor' => $movieRoom->floor,
            'nb_places' => $movieRoom->nb_places,
            'nb_handicap_places' => $movieRoom->nb_handicap_places,
            'edit_url' => url(sprintf('admin/movie-rooms/%s', $movieRoom->public_id)),
        ]);

        $requestUrl = sprintf('admin/movie-rooms?page=%s', $page);

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

            $movieRoomName = trim($section->getElementsByTagName('h2')->item(0)->textContent);

            // Récupération des compteurs
            $ulDetails = $section->getElementsByTagName('ul')->item(0);
            $counters = collect($ulDetails->getElementsByTagName('span'))
                ->filter(fn(\DomElement $span) : bool => $span->getAttribute('class') === 'counter')
                ->map(fn(\DomElement $span) : string => $span->textContent)
                ->values()
            ;

            $movieRoomFloor = intval($counters->get(0));
            $movieRoomNbPlaces = intval($counters->get(1));
            $movieRoomNbHandicapPlaces = intval($counters->get(2));
            $movieRoomEditUrl = $section->getElementsByTagName('a')->item(0)->getAttribute('href');

            $currentData = [
                'name' => $movieRoomName,
                'floor' => $movieRoomFloor,
                'nb_places' => $movieRoomNbPlaces,
                'nb_handicap_places' => $movieRoomNbHandicapPlaces,
                'edit_url' => $movieRoomEditUrl,
            ];

            $this->assertEquals($expectedData, $currentData);
        }
    }
}
