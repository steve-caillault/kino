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
use App\Http\Requests\MovieRoom\{
    CreateMovieRoomRequest,
    EditMovieRoomRequest
};
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

     /**
     * Page d'édition d'une salle de cinéma
     * @param string $movieRoomPublicId
     * @param Request $request
     * @return Renderable
     */
    public function show(string $movieRoomPublicId, Request $request) : Renderable
    {
        $movieRoom = MovieRoom::findByPublicId($movieRoomPublicId);
        if($movieRoom === null)
        {
            abort(404);
        }

        $formDataKeys = [ 'public_id', 'name', 'floor', 'nb_places', 'nb_handicap_places', ];
        $formData = collect($movieRoom->attributesToArray())
            ->filter(fn(mixed $value, string $key) => in_array($key, $formDataKeys))
            ->all();

        $form = view('admin.movie_rooms._edit_form', [
            'method' => 'patch',
            'actionUrl' => route('admin.movie_rooms.show', [
                'movieRoomPublicId' => $movieRoomPublicId,
            ]),
            'data' => $formData,
        ]);

        return view('admin.movie_rooms.edit', [
            'form' => $form,
            'movieRoomName' => $movieRoom->name,
        ]);
    }

    /**
     * Gestion de l'édition d'une salle de cinéma
     * @param string $movieRoomPublicId
     * @param EditMovieRoomRequest $request
     * @return RedirectResponse
     */
    public function update(string $movieRoomPublicId, EditMovieRoomRequest $request) : RedirectResponse
    {
        $store = new MovieRoomStore($request);
        $success = $store->save();

        // Message flash
        $messageFlashType = ($success) ? 'success' : 'error';
        $messageFlash = ($success) ? 'form.movie_room.edit.success' : 'form.movie_room.edit.failure';
        $request->session()->flash($messageFlashType, trans($messageFlash, [
            'name' => $request->get('name'),
        ]));

        return response()->redirectToRoute('admin.movie_rooms.index');
    }

}   
