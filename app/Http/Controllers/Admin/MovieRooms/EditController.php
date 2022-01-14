<?php

/**
 * Edition d'une salle de ciné
 */

namespace App\Http\Controllers\Admin\MovieRooms;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
/***/
use App\Http\Controllers\Admin\AbstractController;
use App\Models\MovieRoom;
use App\Forms\Admin\MovieRoom\{
    MovieRoomForm,
    MovieRoomFormRender
};

final class EditController extends AbstractController
{
    /**
     * Edition d'une salle de cinéma
     * @param Request $request
     * @param string $movieRoomPublicId
     * @return Renderable|RedirectResponse
     */
    public function index(Request $request, string $movieRoomPublicId) : Renderable|RedirectResponse
    {
        $movieRoom = MovieRoom::where('public_id', '=', $movieRoomPublicId)->first();
        if($movieRoom === null)
        {
            abort(404);
        }

        $formData = $request->post();

        $form = new MovieRoomForm($formData);
        $form->setMovieRoom($movieRoom);
        $form->process();

        $success = $form->getSuccess();
        $errors = $form->getErrors();

        // Messages flash
        if(count($errors) > 0)
        {
            $request->session()->now('error', trans('form.invalidated'));
        }
        elseif($success !== null)
        {
            $messageFlashType = ($success) ? 'success' : 'error';
            $messageFlash = ($success) ? 'form.movie_room.edit.success' : 'form.movie_room.edit.failure';
            $flashMethod = ($success) ? 'flash' : 'now';
            $request->session()->{ $flashMethod }($messageFlashType, trans($messageFlash, [
                'name' => $form->getInputValue('name'),
            ]));
        }

        if($success)
        {
            return redirect(route('admin.movie_rooms.list'));
        }

        $formRender = with(new MovieRoomFormRender($form))->render();

        return view('admin.movie_rooms.edit', [
            'form' => $formRender,
            'movieRoomName' => $movieRoom->name,
        ]);
    }

}