@extends('layouts.default')

@section('head-meta')
@parent
<meta name="robots" content="noindex" />
@endsection

@section('page-title')
@lang('page.error.title', [
    'code' => $code,
])
@endsection

@section('content')
<p>{{ $message }}</p>
@endsection
