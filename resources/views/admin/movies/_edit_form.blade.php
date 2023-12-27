<form method="post" action="{{ $actionUrl ?? '' }}">
    @csrf
    @method($method ?? 'post')

    <x-form-input 
        input-id="movie-public-id"
        input-name="public_id"
        :required="true" 
        :label="trans('form.admin.movie.fields.public_id')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="movie-public-id"
                name="public_id" 
                required 
                autocomplete="off" 
                value="{{ old('public_id', $data['public_id'] ?? null) }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="movie-name"
        input-name="name"
        :required="true" 
        :label="trans('form.admin.movie.fields.name')"
    >
        <x-slot name="input">
            <input 
                type="text" 
                id="movie-name"
                name="name" 
                required 
                autocomplete="off" 
                value="{{ old('name', $data['name'] ?? null) }}" 
            />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="movie-produced-at"
        input-name="produced_at"
        :required="true" 
        :label="trans('form.admin.movie.fields.produced_at')"
    >
        <x-slot name="input">
            <input 
                type="date"
                id="movie-produced-at"
                name="produced_at"
                required 
                autocomplete="off" 
                value="{{ old('produced_at', $data['produced_at'] ?? null) }}"
                min="1895-03-19"
                max="9999-12-31"
            />
        </x-slot>
    </x-form-input>
    
    <x-form-input-submit :label="trans('form.input.submit.default.label')"></x-form-input-submit>
    
</form>
