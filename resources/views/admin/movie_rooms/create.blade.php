@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movie_rooms.add.title')
@endsection

@section('page-title')
@lang('page.admin.movie_rooms.add.title')
@endsection

@section('content')
{!! $form !!}
@endsection
