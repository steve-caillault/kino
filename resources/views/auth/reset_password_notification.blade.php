<p>@lang('notification.reset_password.title', [
    'name' => $userName
])</p>

<p>@lang('notification.reset_password.message')</p>

<p>
    <a href="{{ $resetUrl }}" title="@lang('notification.reset_password.anchor.alt_label')">
        @lang('notification.reset_password.anchor.label')
    </a>
</p>

<p>@lang('notification.signing')</p>