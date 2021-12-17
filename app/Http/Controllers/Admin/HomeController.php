<?php

/**
 * Contrôleur d'index pour le panneau d'administration
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

final class HomeController extends AbstractController
{
    public function index(Request $request) : Renderable
    {
        return view('admin.home');
    }
}   
