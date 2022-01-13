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
    <a href="{{ route('admin.movie-rooms.list') }}" title="@lang('menu.movie-rooms.list.alt_label')">
        @lang('menu.movie-rooms.list.label')
    </a>
</nav>
@endsection