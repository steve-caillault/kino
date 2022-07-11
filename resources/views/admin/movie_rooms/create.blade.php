@extends('layouts.admin')

@section('meta-title')
@lang('meta.title.admin.movie_rooms.add')
@endsection

@section('page-title')
@lang('title.admin.movie_rooms.add')
@endsection

@section('content')
{!! $form !!}
@endsection