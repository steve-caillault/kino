@extends('layouts.admin')

@section('meta-title')
@lang('meta.title.admin.user', [
    'user' => Auth::user()->nickname
])
@endsection

@section('page-title')
@lang('title.admin.user')
@endsection

@section('content')
{!! $form !!}
@endsection