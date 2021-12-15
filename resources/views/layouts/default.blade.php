<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1.0" />

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>@yield('meta-title', 'Kino')</title>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    </head>
    <body>
        <div id="page">
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
