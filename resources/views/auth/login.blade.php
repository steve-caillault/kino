@extends('layouts.default')

@section('meta-title')
@lang('meta.title.login')
@endsection

@section('page-title')
@lang('title.login')
@endsection

@section('content')

<form method="POST">
    @csrf

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('nickname'),
    ])>
        <label for="nickname">{{ __('form.auth.fields.nickname') }}</label>

        <input 
            id="nickname" 
            type="text" 
            name="nickname" 
            value="{{ old('nickname') }}" 
            required
            autocomplete="off" 
        />

        @error('nickname')
        <p class="error">{{ $message }}</p>
        @enderror
    </div>

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('password'),
    ])>
        <label for="password">{{ __('form.auth.fields.password') }}</label>

        <input 
            id="password"
            type="password"
            name="password" 
            required
            autocomplete="off"
        />

        @error('password')
         <p class="error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-input">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
        <label for="remember">{{ __('form.auth.fields.remember') }}</label>
    </div>

    <div class="form-input form-input-submit">
        <input type="submit" value="{{ __('form.auth.fields.submit') }}" />
    </div>
</form>

@endsection