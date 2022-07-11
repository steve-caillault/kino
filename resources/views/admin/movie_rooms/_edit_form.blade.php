<form method="post">
    @csrf

    <x-form-input 
        input-id="room-public-id"
        input-name="public_id"
        :required="true" 
        :label="trans('form.movie_room.fields.public_id')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="room-public-id" 
                name="public_id" 
                required 
                autocomplete="off" 
                value="{{ $data['public_id'] ?? null }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-name"
        input-name="name"
        :required="true" 
        :label="trans('form.movie_room.fields.name')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="room-name" 
                name="name" 
                required 
                autocomplete="off" 
                value="{{ $data['name'] ?? null }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-floor"
        input-name="floor"
        :required="true" 
        :label="trans('form.movie_room.fields.floor')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-floor" 
                name="floor" 
                required 
                autocomplete="off" 
                value="{{ $data['floor'] ?? null }}" 
                min="-10"
                max="10"
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-nb-places"
        input-name="nb_places"
        :required="true" 
        :label="trans('form.movie_room.fields.nb_places')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-nb-places" 
                name="nb_places" 
                required 
                autocomplete="off" 
                value="{{ $data['nb_places'] ?? null }}" 
                min="20"
                max="1000"
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="room-nb-handicap-places"
        input-name="nb_handicap_places"
        :required="true" 
        :label="trans('form.movie_room.fields.nb_handicap_places')"
    >
        <x-slot name="input">
            <input 
                type="number" 
                id="room-nb-handicap-places" 
                name="nb_handicap_places" 
                required 
                autocomplete="off" 
                value="{{ $data['nb_handicap_places'] ?? null }}" 
                min="20"
                max="1000"
            />
        </x-slot>
    </x-form-input>
    
    <x-form-input-submit :label="trans('form.submit.label')"></x-form-input-submit>
    
</form>