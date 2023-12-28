<?php

/**
 * Trait pour les tests ayant besoin d'un administrateur
 */

namespace Tests\Admin;

use App\Models\AdminUser;

trait WithAdminUser {

    /**
     * Administrateur connecté
     * @var AdminUser
     */
    private AdminUser $mainAdminUser;

    /**
     * Initialisation de l'administrateur
     * @return void
     */
    private function initAdminUser() : void
    {
        $this->mainAdminUser = AdminUser::factory()->create([
            'nickname' => 'admin-user',
            'password' => 'admin-user-password',
            'email' => 'admin-user@kino.me',
            'permissions' => [ 'ADMIN', ],
        ]);
    }

}

