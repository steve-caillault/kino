<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Auth\AbstractLoginController;

final class LoginController extends AbstractLoginController
{
    
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
