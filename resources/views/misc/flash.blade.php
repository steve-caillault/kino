@foreach([ 'success', 'error' ] as $key)
    @if($message = session($key))
        @php
            $flashMessageClass = ($key === 'error') ? 'red' : $key;
        @endphp
    <p @class([
        "with-color",
        "with-color-green" => ($key === 'success'),
        "with-color-red" => ($key === 'error'),
    ])>
        {{ $message }}
    </p>
    @endif
@endforeach
