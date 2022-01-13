<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $redirectRouteName = $this->getRedirectRouteNameByGuardName($guard);
                $redirectUrl = route($redirectRouteName);
                return redirect($redirectUrl);
            }
        }

        return $next($request);
    }

    /**
     * Retourne le nom de la route de redirection en fonction du Auth Guard à utiliser
     * @param ?string Nom du Auth Guard
     * @return string
     */
    private function getRedirectRouteNameByGuardName(?string $guard) : string
    {
        return match($guard){
            'admin' => 'admin.index',
            default => 'home'
        };
    }
}
