@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movies.list.title')
@endsection

@section('page-title')
@lang('page.admin.movies.list.title')
@endsection

@section('content')

    <p>
        <a
            class="button"
            href="{{ route('admin.movies.create') }}"
            title="@lang('page.admin.movies.button.add.alt_label')"
        >
            @lang('page.admin.movies.button.add.label')
        </a>
    </p>

    @if(count($movies) === 0)
        <p>@lang('page.admin.movies.list.empty')</p>
    @else
        @foreach($movies as $movie)
        <section class="collection">
            <h2>{{ $movie['name'] }}</h2>
            <ul>
                <li>
                    @lang('page.admin.movies.list.item.fields.produced_at')
                    {{ $movie['produced_at'] }}
                </li>
            </ul>
            <a 
                class="button" 
                href="{{ route('admin.movies.show', [
                    'movie' => $movie['public_id'],
                ]) }}"
                title="@lang('page.admin.movies.button.edit.alt_label', [
                    'name' => $movie['name']
                ])"
            >
                @lang('page.admin.movies.button.edit.label')
            </a>
        </section>
        @endforeach
        {!! $pagination !!}
    @endif

@endsection
