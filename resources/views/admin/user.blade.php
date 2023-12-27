@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.user.title', [
    'user' => Auth::user()->nickname
])
@endsection

@section('page-title')
@lang('page.admin.user.title')
@endsection

@section('content')
{!! $form !!}
@endsection
