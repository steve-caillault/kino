@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movies.add.title')
@endsection

@section('page-title')
@lang('page.admin.movies.add.title')
@endsection

@section('content')
{!! $form !!}
@endsection
