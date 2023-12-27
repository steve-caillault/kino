@extends('layouts.default')

@use('Illuminate\Support\Facades\Vite')

@section('stylesheets')
     <link href="{{ Vite::asset('resources/sass/admin.scss') }}" rel="stylesheet" />
@endsection

@section('header-main-nav')
<nav class="user">
    <a href="{{ route('admin.user.index') }}" title="@lang('menu.user.edit.alt_label')">{{ Auth::user()->nickname }}</a>
    <a href="{{ route('admin.auth.logout') }}" title="@lang('button.logout.alt_label')">@lang('button.logout.label')</a>
</nav>
<nav class="modules">
    <a 
        href="{{ route('admin.movie_rooms.index') }}"
        title="@lang('menu.movie_rooms.list.alt_label')"
        @class([
            'selected' => str_contains(url()->current(), '/movie-rooms')
        ])
    >
        @lang('menu.movie_rooms.list.label')
    </a>

    <a
        href="{{ route('admin.movies.index') }}"
        title="@lang('menu.movie.list.alt_label')"
        @class([
            'selected' => str_contains(url()->current(), '/movies')
        ])
    >
        @lang('menu.movies.list.label')
    </a>
</nav>
@endsection
