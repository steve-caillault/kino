@extends('layouts.admin')

@section('meta-title')
@lang('meta.title.admin.movie_rooms.edit', [
    'name' => $movieRoomName,
])
@endsection

@section('page-title')
@lang('title.admin.movie_rooms.edit', [
    'name' => $movieRoomName,
])
@endsection

@section('content')
{!! $form !!}
@endsection