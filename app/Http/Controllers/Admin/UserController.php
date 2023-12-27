<?php

/**
 * Contrôleur d'édition du compte administrateur connecté
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\{
    Request, 
    RedirectResponse
};
use Illuminate\Contracts\Support\Renderable;
/***/
use App\Http\Requests\User\UserEditProfileRequest;
use App\Models\User;

final class UserController extends AbstractController
{
    /**
     * Gestion des paramètres du compte connecté
     * @param Request $request
     * @return Renderable
     */
    public function showEditProfileForm(Request $request) : Renderable
    {
        $user = $request->user();

        $formData = [
            'nickname' => old('nickname', $user->nickname),
            'email' => old('email', $user->email),
            'firstName' => old('first_name', $user->first_name),
            'lastName' => old('last_name', $user->last_name),
        ];

        $form = view('admin.profile._edit_form', [
            'data' => $formData,
        ]);

        return view('admin.user', [
            'form' => $form,
        ]);
    }

    /**
     * Gestion de l'édition du profil de l'utilisateur
     * @param UserEditProfileRequest $request
     * @return RedirectResponse
     */
    public function updateProfile(UserEditProfileRequest $request) : RedirectResponse
    {
        $inputs = collect($request->validated());

        /**
         * @var User $user
         */
        $user = $request->user();

        $user->fill([
            'nickname' => $inputs->get('nickname'),
            'first_name' => $inputs->get('first_name'),
            'last_name' => $inputs->get('last_name'),
            'email' => $inputs->get('email'),
        ]);

        $newPassword = $inputs->get('new_password');
        if($newPassword !== null)
        {
            $user->password = $newPassword;
        }

        $success = $user->save();

        $messageFlashType = ($success) ? 'success' : 'error';
        $messageFlash = ($success) ? 'form.user.profile.edit.flash.success' : 'form.user.profile.edit.flash.failure';
        $request->session()->flash($messageFlashType, trans($messageFlash));

        return back();
    }

}   
