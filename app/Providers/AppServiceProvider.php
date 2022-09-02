<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
/***/
use App\Vite;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrap();

        // Directive Blade pour retourner l'URL d'un fichier d'assets
        Blade::directive('vite_asset_url', function(string $path) {
            return (new Vite())->getAssetUrl($path);
        });

    }
}
