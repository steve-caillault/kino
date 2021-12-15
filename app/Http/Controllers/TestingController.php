<?php

/**
 * Contrôleur de test en local
 */

namespace App\Http\Controllers;

final class TestingController extends AbstractController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(! app()->environment('local'))
        {
            abort(403);
        }
    }

    /**
     *
     * @return never
     */
    public function index() : never
    {
        exit;
    }
}
