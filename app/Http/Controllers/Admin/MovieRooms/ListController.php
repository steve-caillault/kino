<?php

/**
 * Liste des salles de ciméma
 */

namespace App\Http\Controllers\Admin\MovieRooms;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
/***/
use App\Http\Controllers\Admin\AbstractController;
use App\Models\MovieRoom;
use App\UI\PaginationRendering;

final class ListController extends AbstractController
{
    public function index(Request $request) : Renderable
    {
        $currentPage = max(1, (int) $request->query('page', 1));
        $itemsPerPage = 20;
        $offset = ($currentPage - 1) * $itemsPerPage;

        $rooms = [];
        $totalItems = MovieRoom::count();
        $roomModels = ($totalItems === 0) ? [] : MovieRoom::orderBy('name', 'asc')->take($itemsPerPage)->skip($offset)->get();
       
        // Formattage
        foreach($roomModels as $room)
        {
            $rooms[] = [
                'name' => $room->name,
                'floor' => $room->floor,
                'nb_places' => $room->nb_places,
                'nb_handicap_places' => $room->nb_handicap_places,
                'editUrl' => route('admin.movie_rooms.edit', [
                    'movieRoomPublicId' => $room->public_id,
                ]),
            ];
        }

        // Pagination 
        $pagination = new PaginationRendering(
            items: $rooms, 
            totalItems: $totalItems, 
            itemsPerPage: $itemsPerPage, 
            currentPage: $currentPage
        );

        return view('admin.movie_rooms.list', [
            'rooms' => $rooms,
            'pagination' => $pagination,
        ]);
    }
}   
