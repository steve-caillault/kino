@extends('layouts.default')

@section('stylesheets')
<link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
@endsection

@section('header-main-nav')
<div class="user">
    <a href="{{ route('admin.user.index') }}" title="@lang('anchor.user.edit.alt_label')">{{ Auth::user()->nickname }}</a>
    <a href="{{ route('admin.auth.logout') }}" title="@lang('button.logout.alt_label')">@lang('button.logout.label')</a>
</div>
@endsection