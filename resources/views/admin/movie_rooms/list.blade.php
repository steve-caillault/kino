@extends('layouts.admin')

@section('meta-title')
@lang('meta.title.admin.movie_rooms.list')
@endsection

@section('page-title')
@lang('title.admin.movie_rooms.list')
@endsection

@section('content')

    <p>
        <a class="button" href="{{ route('admin.movie_rooms.create') }}" title="@lang('button.admin.movie_rooms.add.alt_label')">
            @lang('button.admin.movie_rooms.add.label')
        </a>
    </p>

    @if(count($rooms) === 0)
        <p>@lang('message.admin.movie_rooms.list.empty')</p>
    @else
        @foreach($rooms as $room)
        <section class="movie-rooms">
            <h2>{{ $room['name'] }}</h2>
            <ul>
                <li>
                    @lang('message.admin.movie_rooms.list.fields.floor')
                    {{ $room['floor'] }}
                </li>
                <li>
                    @lang('message.admin.movie_rooms.list.fields.nb_places')
                    {{ $room['nb_places'] }}
                </li>
                <li>
                    @lang('message.admin.movie_rooms.list.fields.nb_handicap_places')
                    {{ $room['nb_handicap_places'] }}
                </li>
            </ul>
            <a 
                class="button" 
                href="{{ route('admin.movie_rooms.show', [
                    'movieRoom' => $room['public_id'],
                ]) }}"
                title="@lang('button.admin.movie_rooms.edit.alt_label', [
                    'name' => $room['name']
                ])"
            >
                @lang('button.admin.movie_rooms.edit.label')
            </a>
        </section>
        @endforeach
        {!! $pagination !!}
    @endif

@endsection
