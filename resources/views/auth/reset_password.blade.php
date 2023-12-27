@extends('layouts.default')

@section('meta-title')
@lang('meta.reset_password.title')
@endsection

@section('page-title')
@lang('page.reset_password.title')
@endsection

@section('content')

<form method="POST">
    @csrf

    <input type="hidden" value="{{ $token }}" name="token" />

    @error('token')
    <p class="error">{{ $message }}</p>
    @enderror

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('email'),
    ])>
        <label for="email">@lang('form.auth.reset_password.fields.email')</label>

        <input 
            id="email" 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            required
            autocomplete="off" 
        />

        @error('email')
        <p class="error">{{ $message }}</p>
        @enderror
    </div>

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('password'),
    ])>
        <label for="password">@lang('form.auth.reset_password.fields.password')</label>

        <input 
            id="password" 
            type="password" 
            name="password"
            required
        />

        @error('password')
        <p class="error">{{ $message }}</p>
        @enderror
    </div>

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('password_confirmation'),
    ])>
        <label for="password_confirmation">@lang('form.auth.reset_password.fields.password_confirmation')</label>

        <input 
            id="password_confirmation" 
            type="password" 
            name="password_confirmation"
            required
        />

        @error('password_confirmation')
        <p class="error">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-input form-input-submit">
        <input class="button" type="submit" value="@lang('form.auth.reset_password.fields.submit')" />
    </div>
</form>

@endsection
