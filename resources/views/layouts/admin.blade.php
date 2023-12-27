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
        title="@lang('menu.admin.movie_room.list.alt_label')"
        @class([
            'selected' => str_contains(url()->current(), '/movie-rooms')
        ])
    >
        @lang('menu.admin.movie_room.list.label')
    </a>

    <a
        href="{{ route('admin.movies.index') }}"
        title="@lang('menu.admin.movie.list.alt_label')"
        @class([
            'selected' => str_contains(url()->current(), '/movies')
        ])
    >
        @lang('menu.admin.movie.list.label')
    </a>
</nav>
@endsection
