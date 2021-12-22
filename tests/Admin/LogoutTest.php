<?php

/**
 * Tests de la déconnexion du panneau d'administration
 */

namespace Tests\Admin;

use Tests\TestCase;
use App\Models\User;

final class LogoutTest extends TestCase
{

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {   
        parent::setUp();

        // Création d'un administrateur
        User::factory()->create([
            'nickname' => 'admin-user',
            'password' => 'admin-user-password',
            'permissions' => [ 'ADMIN', ],
        ]);
    }

    /**
     * Vérifie que l'utilisateur est authentifié
     * @return void
     */
    public function testIsLogged() : void
    {
        $user = User::where('nickname', 'admin-user')->first();

        $this->actingAs($user)
            ->followingRedirects()
            ->get('admin')
        ;

        $this->assertAuthenticated();
    }

    /**
     * Vérifie la déconnexion du compte
     * @return void
     */
    public function testLogout() : void
    {
        $user = User::where('nickname', 'admin-user')->first();

        $this->actingAs($user)
                ->followingRedirects()
                ->get('admin/auth/logout')
        ;

        $this->assertGuest();
    }
}