<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>@yield('meta-title', 'Kino')</title>

        @section('stylesheets')
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        @show
    </head>
    <body>
        <div id="page">
            <header class="main">
                @yield('header-main-nav')
                <h1>@yield('page-title', trans('title.default'))</h1>
            </header>
            <main>
                @include('misc.flash')
                @yield('content')
            </main>
        </div>
    </body>
</html>
