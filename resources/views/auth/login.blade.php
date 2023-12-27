@extends('layouts.default')

@section('meta-title')
@lang('meta.login.title')
@endsection

@section('page-title')
@lang('page.login.title')
@endsection

@section('content')

<form method="POST">
    @csrf

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('nickname'),
    ])>
        <label for="nickname">@lang('form.auth.login.fields.nickname')</label>

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
        <label for="password">@lang('form.auth.login.fields.password')</label>

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

    <p>
        <a 
            href="{{ $forgotPasswordUri }}" 
            title="@lang('form.auth.login.button.forgot_password.alt_label')"
        >
            @lang('form.auth.login.button.forgot_password.label')
        </a>
    </p>

    <div class="form-input">
        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
        <label for="remember">@lang('form.auth.login.fields.remember')</label>
    </div>

    <div class="form-input form-input-submit">
        <input class="button" type="submit" value="@lang('form.auth.login.fields.submit')" />
    </div>
</form>

@endsection
