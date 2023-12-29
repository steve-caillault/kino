@extends('layouts.admin')

@section('meta-title')
@lang('meta.admin.movie_rooms.list.title')
@endsection

@section('page-title')
@lang('page.admin.movie_rooms.list.title')
@endsection

@section('content')

    <p>
        <a
            class="button"
            href="{{ route('admin.movie_rooms.create') }}"
            title="@lang('page.admin.movie_rooms.button.add.alt_label')"
        >
            @lang('page.admin.movie_rooms.button.add.label')
        </a>
    </p>

    @if(count($rooms) === 0)
        <p class="empty-list">@lang('page.admin.movie_rooms.list.empty')</p>
    @else
        @foreach($rooms as $room)
        <section class="collection-item">
            <h2>{{ $room['name'] }}</h2>
            <ul>
                <li>
                    @lang('page.admin.movie_rooms.list.item.fields.floor')
                    <span class="counter">{{ $room['floor'] }}</span>
                </li>
                <li>
                    @lang('page.admin.movie_rooms.list.item.fields.nb_places')
                    <span class="counter">{{ $room['nb_places'] }}</span>
                </li>
                <li>
                    @lang('page.admin.movie_rooms.list.item.fields.nb_handicap_places')
                    <span class="counter">{{ $room['nb_handicap_places'] }}</span>
                </li>
            </ul>
            <a 
                class="button" 
                href="{{ route('admin.movie_rooms.show', [
                    'movieRoom' => $room['public_id'],
                ]) }}"
                title="@lang('page.admin.movie_rooms.button.edit.alt_label', [
                    'name' => $room['name']
                ])"
            >
                @lang('page.admin.movie_rooms.button.edit.label')
            </a>
        </section>
        @endforeach
        {!! $pagination !!}
    @endif

@endsection
