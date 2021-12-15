<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('login', 'Auth\LoginController@login');*/

Route::get('/', 'HomeController@index')->name('home');

// Panneau d'administration
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function() {

    // Authentification
    Route::group([
        'prefix' => 'auth',
        'namespace' => 'Auth',
    ], function() {
        Route::get('login', 'LoginController@showLoginForm')->name('admin.auth.login');
        Route::post('login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout')->name('admin.auth.logout');
    });
   
    Route::group([
        'middleware' => [ 'auth', 'admin', ],
    ], function() {
        Route::get('', 'HomeController@index')->name('admin.index');
    });
});

// Tests ponctuels en local
if(app()->environment('local'))
{
    Route::get('/testing', 'TestingController@index')->name('testing');
}

