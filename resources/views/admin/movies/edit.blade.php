@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movies.edit.title', [
    'name' => $movieName,
])
@endsection

@section('page-title')
@lang('page.admin.movies.edit.title', [
    'name' => $movieName,
])
@endsection

@section('content')
{!! $form !!}
@endsection
