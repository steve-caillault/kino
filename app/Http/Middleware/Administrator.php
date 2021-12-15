<?php

/**
 * Vérifie qu'un utilisateur est connecté en tant qu'administrateur
 */

namespace App\Http\Middleware;

use Closure;

final class Administrator
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$user = $request->user();
		
		if($user === null or ! $user->isAdministrator())
		{
			abort(403, __('error.forbidden'));
		}
		
		return $next($request);
	}
	
}
