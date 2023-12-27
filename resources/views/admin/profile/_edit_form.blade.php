<form method="post">
    @csrf

    <x-form-input 
        input-id="nickname"
        input-name="nickname"
        :required="true" 
        :label="trans('form.user.profile.edit.fields.nickname')"
    >
        <x-slot name="input">
            <input type="text" id="nickname" name="nickname" required autocomplete="off" value="{{ $data['nickname'] }}" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-id="email"
        input-name="email"
        :required="true" 
        :label="trans('form.user.profile.edit.fields.email')"
    >
        <x-slot name="input">
            <input type="email" id="email" name="email" required autocomplete="off" value="{{ $data['email'] }}" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-name="first_name"
        input-id="first-name"
        :required="true" 
        :label="trans('form.user.profile.edit.fields.first_name')"
    >
        <x-slot name="input">
            <input type="text" id="first-name" name="first_name" required autocomplete="off" value="{{ $data['firstName'] }}" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-name="last_name"
        input-id="last-name"
        :required="true" 
        :label="trans('form.user.profile.edit.fields.last_name')"
    >
        <x-slot name="input">
            <input type="text" id="last-name" name="last_name" required autocomplete="off" value="{{ $data['lastName'] }}" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-name="current_password"
        input-id="current_password"
        :label="trans('form.user.profile.edit.fields.current_password')"
    >
        <x-slot name="input">
            <input type="password" id="current_password" name="current_password" autocomplete="off" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-name="new_password"
        input-id="new_password"
        :label="trans('form.user.profile.edit.fields.new_password')"
    >
        <x-slot name="input">
            <input type="password" id="new_password" name="new_password" autocomplete="off" />
        </x-slot>
    </x-form-input>

    <x-form-input 
        input-name="new_password_confirmation"
        input-id="new_password_confirmation"
        :label="trans('form.user.profile.edit.fields.new_password_confirmation')"
    >
        <x-slot name="input">
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="off" />
        </x-slot>
    </x-form-input>

    <x-form-input-submit :label="trans('form.input.submit.default.label')"></x-form-input-submit>
    
</form>
