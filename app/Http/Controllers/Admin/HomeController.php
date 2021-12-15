<?php

/**
 * Contrôleur d'index pour le panneau d'administration
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;

final class HomeController extends AbstractController
{
    public function index() : Renderable
    {
        return view('admin.home');
    }
}   
