<?php

/**
 * Contrôleur d'édition du compte administrateur connecté
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{
    Validator
};
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
/***/
use App\Models\User;
use App\Forms\Admin\User\{
    UserForm,
    UserFormRender
};

final class UserController extends AbstractController
{
    /**
     * Gestion des paramètres du compte connecté
     * @param Request
     * @return Renderable
     */
    public function index(Request $request) : Renderable
    {
        $formData = $request->post();

        $form = new UserForm($formData);
        $form->setUser($request->user());
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
            $messageFlash = ($success) ? 'form.user.success' : 'form.user.failure';
            $request->session()->now($messageFlashType, trans($messageFlash));
        }

        $formRender = with(new UserFormRender($form))->render();

        return view('admin.user', [
            'form' => $formRender,
        ]);
    }

}   
