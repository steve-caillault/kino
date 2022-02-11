<?php

/**
 * Utilisateur du panneau d'administration
 */

namespace App\Models;

final class AdminUser extends User
{
    /**
     * Retourne l'URI de réinitialisation de mot de passe
     * @param string $token
     * @return string
     */
    public function getResetPasswordUri(string $token) : string
    {
        return route('admin.auth.reset_password.index', [
            'token' => $token,
        ]);
    }
}