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
    'as' => 'admin.',
], function() {

    // Authentification
    Route::group([
        'prefix' => 'auth',
        'namespace' => 'Auth',
        'as' => 'auth.',
    ], function() {
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });
   
    Route::group([
        'middleware' => [ 'auth:admin', 'admin', ],
    ], function() {
        Route::get('', 'HomeController@index')->name('index');

        // Edition du compte
        Route::match([ 'get', 'post', ], 'user', 'UserController@index')->name('user.index');

        // Gestion des salles de cinéma
        Route::group([
            'prefix' => 'movie-rooms',
            'namespace' => 'MovieRooms',
            'as' => 'movie_rooms.'
        ], function() {
            // Liste des salles
            Route::get('', 'ListController@index')->name('list');
            // Ajout d'une salle
            Route::match([ 'get', 'post' ], 'add', 'AddController@index')->name('add');
            // Edition d'une salle
            Route::match([ 'get', 'post' ], '{movieRoomPublicId}/edit', 'EditController@index')
                ->name('edit')
                ->where('movieRoomPublicId', '[^\/]+');
        });
    });
});

// Tests ponctuels en local
if(app()->environment('local'))
{
    Route::get('/testing', 'TestingController@index')->name('testing');
}

