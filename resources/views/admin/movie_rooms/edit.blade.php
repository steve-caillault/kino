@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movie_rooms.edit.title', [
    'name' => $movieRoomName,
])
@endsection

@section('page-title')
@lang('page.admin.movie_rooms.edit.title', [
    'name' => $movieRoomName,
])
@endsection

@section('content')
{!! $form !!}
@endsection
