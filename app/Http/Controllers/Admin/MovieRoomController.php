<?php

/**
 * Gestion des salles de cinéma
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{
    Request,
    RedirectResponse
};
/***/
use App\Models\MovieRoom;
use App\Http\Controllers\Admin\AbstractController;
use App\Http\Requests\MovieRoom\CreateMovieRoomRequest;
use App\Store\MovieRoomStore;

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

    /**
     * Page de création d'une salle de cinéma
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request) : Renderable
    {
        $form = view('admin.movie_rooms._edit_form', [
            'actionUrl' => route('admin.movie_rooms.store'),
            'room' => [],
        ]);

        return view('admin.movie_rooms.create', [
            'form' => $form,
        ]);
    }

    /**
     * Gestion de la création d'une salle de cinéma
     * @param CreateMovieRoomRequest $request
     * @return RedirectResponse
     */
    public function store(CreateMovieRoomRequest $request) : RedirectResponse
    {
        $store = new MovieRoomStore($request);
        $success = $store->save();

        // Message flash
        $messageFlashType = ($success) ? 'success' : 'error';
        $messageFlash = ($success) ? 'form.movie_room.add.success' : 'form.movie_room.add.failure';
        $request->session()->flash($messageFlashType, trans($messageFlash, [
            'name' => $request->get('name'),
        ]));

        return response()->redirectToRoute('admin.movie_rooms.index');
    }
}   
