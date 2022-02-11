<?php

/**
 * Demande de réinitialisation de mot de passe
 */

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Facades\Password;
/***/
use App\Http\Controllers\Auth\AbstractForgotPasswordController;

final class ForgotPasswordController extends AbstractForgotPasswordController
{
    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Retourne l'URI de redirection en cas de succès
     * @return string
     */
    protected function getSuccessUri() : string
    {
        return route('admin.auth.login');
    }

    /**
     * Retourne l'URI de connexion
     * @return string
     */
    protected function getLoginUri() : string
    {
        return route('admin.auth.login');
    }

}
