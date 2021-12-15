<?php

/**
 * Page d'accueil
 */

namespace App\Http\Controllers;

final class HomeController extends AbstractController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.home');
    }
}
