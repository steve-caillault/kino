<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Facades\Auth;
/***/
use App\Http\Controllers\Auth\AbstractLoginController;

final class LoginController extends AbstractLoginController
{

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm() : \Illuminate\View\View
    {
        return view('auth.login', [
            'forgotPasswordUri' => route('admin.auth.forgot_password.request'),
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
    
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route('admin.index');
    }
}
