<?php

/**
 * Routes du panneau d'administration
 */

// Authentification
Route::group([
    'prefix' => 'auth',
    'namespace' => 'Auth',
    'as' => 'auth.',
], function() {
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::group([
        'prefix' => 'forgot-password',
        'middleware' => [ 'guest:admin', ],
        'as' => 'forgot_password.',
    ], function() {
        // Formulaire de demande de réinitialisation de mot de passe
        Route::get('', 'ForgotPasswordController@showLinkRequestForm')->name('request');
        // Traitement de la demande de réinitialisation de mot de passe
        Route::post('', 'ForgotPasswordController@sendResetLinkEmail');
    });

    Route::group([
        'prefix' => 'reset-password/{token}',
        'middleware' => [ 'guest:admin', ],
        'as' => 'reset_password.',
    ], function() {
        // Formulaire de réinitialisation du mot de passe
        Route::get('', 'ResetPasswordController@showResetForm')->name('index');
        // réinitialisation du mot de passe
        Route::post('', 'ResetPasswordController@reset');
    });

});

Route::group([
    'middleware' => [ 'auth:admin', 'admin', ],
], function() {
    Route::get('', 'HomeController@index')->name('index');

    // Formulaire d'édition de compte
    Route::get('user', 'UserController@showEditProfileForm')->name('user.index');
    Route::post('user', 'UserController@updateProfile');

    // Gestion des salles de cinéma
    Route::group([
        'prefix' => 'movie-rooms',
        // 'namespace' => 'MovieRooms',
        'as' => 'movie_rooms.'
    ], function() {

        Route::resource('', 'MovieRoomController')->only([
            'index', 'create', 'store', 'show', 'update',
        ])
            ->parameter('', 'movieRoom:public_id')
            ->where([ 'movieRoom' => '[^\/]+' ])
        ;
    });
});
