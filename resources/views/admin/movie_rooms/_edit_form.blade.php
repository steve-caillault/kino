<form method="post" action="{{ $actionUrl ?? '' }}">
    @csrf
    @method($method ?? 'post')

    <x-form-input 
        input-id="room-public-id"
        input-name="public_id"
        :required="true" 
        :label="trans('form.admin.movie_room.fields.public_id')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="room-public-id" 
                name="public_id" 
                required 
                autocomplete="off" 
                value="{{ old('public_id', $data['public_id'] ?? null) }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-name"
        input-name="name"
        :required="true" 
        :label="trans('form.admin.movie_room.fields.name')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="room-name" 
                name="name" 
                required 
                autocomplete="off" 
                value="{{ old('name', $data['name'] ?? null) }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-floor"
        input-name="floor"
        :required="true" 
        :label="trans('form.admin.movie_room.fields.floor')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-floor" 
                name="floor" 
                required 
                autocomplete="off" 
                value="{{ old('floor', $data['floor'] ?? null) }}" 
                min="-10"
                max="10"
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-nb-places"
        input-name="nb_places"
        :required="true" 
        :label="trans('form.admin.movie_room.fields.nb_places')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-nb-places" 
                name="nb_places" 
                required 
                autocomplete="off" 
                value="{{ old('nb_places', $data['nb_places'] ?? null) }}" 
                min="20"
                max="1000"
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-nb-handicap-places"
        input-name="nb_handicap_places"
        :required="true" 
        :label="trans('form.admin.movie_room.fields.nb_handicap_places')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-nb-handicap-places" 
                name="nb_handicap_places" 
                required 
                autocomplete="off" 
                value="{{ old('nb_handicap_places', $data['nb_handicap_places'] ?? null) }}" 
                min="20"
                max="1000"
            />
        </x-slot>
    </x-form-input>
    
    <x-form-input-submit :label="trans('form.input.submit.default.label')"></x-form-input-submit>
    
</form>
