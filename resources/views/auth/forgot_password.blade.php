@extends('layouts.default')

@section('meta-title')
@lang('meta.forgot_password.title')
@endsection

@section('page-title')
@lang('page.forgot_password.title')
@endsection

@section('content')

<form method="POST">
    @csrf

    <div @class([
        'form-input',
        'form-input-error' => $errors->has('email'),
    ])>
        <label for="email">@lang('form.auth.forgot_password.fields.email')</label>

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

    <p>
        <a 
            href="{{ $loginUri }}" 
            title="@lang('form.auth.forgot_password.button.login.alt_label')"
        >@lang('form.auth.forgot_password.button.login.label')</a>
    </p>


    <div class="form-input form-input-submit">
        <input class="button" type="submit" value="@lang('form.auth.forgot_password.fields.submit')" />
    </div>
</form>

@endsection
