<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Support\Facades\{
    Auth, Password
};
use App\Http\Controllers\Auth\AbstractResetPasswordController;

final class ResetPasswordController extends AbstractResetPasswordController
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
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
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
    public function redirectPath() : string
    {
        return route('admin.index');
    }
}
