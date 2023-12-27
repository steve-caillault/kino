<?php

/**
 * Gestion des films
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\{
    Request,
    RedirectResponse
};
/***/
use App\Models\Movie;
use App\Http\Requests\Movie\{
    CreateMovieRequest,
    EditMovieRequest
};
use App\Store\MovieStore;
use App\Date;

final class MovieController extends AbstractController
{
    /**
     * Page de la liste des films
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request) : Renderable
    {
        $itemsPerPage = 20;

        $queryBuilder = (new Movie())->newQuery()->orderBy('name', 'asc')->paginate($itemsPerPage);
        $pagination = $queryBuilder->render();
        $items = collect($queryBuilder->items());

        $movies = $items->map(fn(Movie $movie) =>  [
            'public_id' => $movie->public_id,
            'name' => $movie->name,
            'produced_at' => Date::getI18nFormat($movie->produced_at->toDateTimeImmutable(), [
                'dateType' => \IntlDateFormatter::RELATIVE_SHORT,
                'timeType' => \IntlDateFormatter::NONE,
            ]),
        ]);

        return view('admin.movies.list', [
            'movies' => $movies,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Page de création d'un film
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request) : Renderable
    {
        $form = view('admin.movies._edit_form', [
            'actionUrl' => route('admin.movies.store'),
            'data' => [],
        ]);

        return view('admin.movies.create', [
            'form' => $form,
        ]);
    }

    /**
     * Gestion de la création d'un film
     * @param CreateMovieRequest $request
     * @return RedirectResponse
     */
    public function store(CreateMovieRequest $request) : RedirectResponse
    {
        $store = new MovieStore($request);
        $success = $store->save();

        // Message flash
        $messageFlashType = ($success) ? 'success' : 'error';
        $messageFlash = ($success) ? 'form.admin.movie.add.flash.success' : 'form.admin.movie.add.flash.failure';
        $request->session()->flash($messageFlashType, trans($messageFlash, [
            'name' => $request->get('name'),
        ]));

        return response()->redirectToRoute('admin.movies.index');
    }

    /**
     * Page d'édition d'un film
     * @param Movie $movie
     * @param Request $request
     * @return Renderable
     */
    public function show(Movie $movie, Request $request) : Renderable
    {
        $formDataKeys = [ 'public_id', 'name', 'produced_at', ];
        $formData = collect($movie->attributesToArray())
            ->only($formDataKeys)
            ->all();

        $form = view('admin.movies._edit_form', [
            'method' => 'patch',
            'actionUrl' => route('admin.movies.show', [
                'movie' => $movie->public_id,
            ]),
            'data' => $formData,
        ]);

        return view('admin.movies.edit', [
            'form' => $form,
            'movieName' => $movie->name,
        ]);
    }

    /**
     * Gestion de l'édition d'un film
     * @param Movie $movie
     * @param EditMovieRequest $request
     * @return RedirectResponse
     */
    public function update(Movie $movie, EditMovieRequest $request) : RedirectResponse
    {
        $store = new MovieStore($request);
        $success = $store->save();

        // Message flash
        $messageFlashType = ($success) ? 'success' : 'error';
        $messageFlash = ($success) ? 'form.admin.movie.edit.flash.success' : 'form.admin.movie.edit.flash.failure';
        $request->session()->flash($messageFlashType, trans($messageFlash, [
            'name' => $request->get('name'),
        ]));

        return response()->redirectToRoute('admin.movie.index');
    }

}
