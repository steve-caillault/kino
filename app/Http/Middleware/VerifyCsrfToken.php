<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

final class VerifyCsrfToken extends Middleware
{

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Requête courante
     * @var Request
     */
    private Request $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle(/*Request */$request, \Closure $next)
    {
        $this->request = $request;
        return parent::handle($request, $next);
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function runningUnitTests() : bool
    {
        if(! parent::runningUnitTests())
        {
            return false;
        }

        // Si on souhaite vérifier le jeton CSRF lors des tests
        return ($this->request->session()->get('must_checked_csrf_token') !== true);
    }
}
