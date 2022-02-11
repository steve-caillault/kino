@extends('layouts.admin')

@section('meta-title')
@lang('meta.title.admin.home')
@endsection

@section('page-title')
@lang('title.admin.home')
@endsection

@section('content')
<p>{{ __('message.admin.home') }}</p>
@endsection