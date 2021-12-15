<?php

/**
 * Contrôleur abstrait pour l'authentification
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
/***/
use App\Http\Controllers\AbstractController;

abstract class AbstractLoginController extends AbstractController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Champs du nom d'utilisateur à utiliser
     * @return string
     */
    public function username() : string
    {
        return 'nickname';
    }
}
