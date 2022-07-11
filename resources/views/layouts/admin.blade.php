@extends('layouts.default')

@section('stylesheets')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
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
</nav>
@endsection