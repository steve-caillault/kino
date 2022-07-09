<div @class([
    'form-input',
    'form-error' => $errors->has($inputName),
    'with-label' => ($label !== null),
])>
    @if($label !== null)
    <label 
        @class([
            'required' => $required
        ])
        for="{{ $inputId }}"
    >
        {{ $label }}
    </label>
    @endif

    {!! $input !!}

    @error($inputName)
        <p class="error">{{ $message }}</p>
    @enderror
</div>