<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $isAdminUri = str_starts_with($request->route()->uri(), 'admin');
        $loginRouteName = ($isAdminUri) ? 'admin.auth.login' : 'auth.login';

        if(! $request->expectsJson())
        {
            return route($loginRouteName);
        }
    }
}
