<?php

/**
 * Liste des salles de ciméma
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
/***/
use App\Http\Controllers\Admin\AbstractController;
use App\Models\MovieRoom;

final class MovieRoomController extends AbstractController
{
    /**
     * Page de la liste des salles de cinéma
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request) : Renderable
    {
        $itemsPerPage = 20;
        $pagination = null;

        $items = collect([]);
        $queryBuilder = (new MovieRoom())->newQuery()->orderBy('name', 'asc')->paginate($itemsPerPage);
        $pagination = $queryBuilder->render();
        $items = collect($queryBuilder->items());

        $rooms = $items->map(fn(MovieRoom $room) =>  [
            'public_id' => $room->public_id,
            'name' => $room->name,
            'floor' => $room->floor,
            'nb_places' => $room->nb_places,
            'nb_handicap_places' => $room->nb_handicap_places,
        ]);

        return view('admin.movie_rooms.list', [
            'rooms' => $rooms,
            'pagination' => $pagination,
        ]);
    }
}   
