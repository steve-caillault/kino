<?php

/**
 * Ajout d'une salle de ciné
 */

namespace App\Http\Controllers\Admin\MovieRooms;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
/***/
use App\Http\Controllers\Admin\AbstractController;
use App\Forms\Admin\MovieRoom\{
    MovieRoomForm,
    MovieRoomFormRender
};

final class AddController extends AbstractController
{
    public function index(Request $request) : Renderable|RedirectResponse
    {
        $formData = $request->post();

        $form = new MovieRoomForm($formData);
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
            $messageFlash = ($success) ? 'form.movie_room.add.success' : 'form.movie_room.add.failure';
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

        return view('admin.movie_rooms.add', [
            'form' => $formRender,
        ]);
    }

}