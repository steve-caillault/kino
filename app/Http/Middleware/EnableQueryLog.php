<?php

/**
 * Middleware pour logguer les requêtes à la base de données
 * Ne devrait être activé que pour l'environnement de développement
 * L'option peut être désactivé dans la variable d'environnement LOG_DATABASE_QUERY
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
/***/
use App\Debug;

final class EnableQueryLog
{

    /**
     * Constructeur
     * @param Debug $debug
     */
    public function __construct(private Debug $debug)
    {

    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        $canEnabled = $this->debug->canWriteQueryLog();
        if($canEnabled)
        {
            $this->debug->enableQueryLog(true);
        }

        return $next($request);
    }

}
