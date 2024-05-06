@component('mail::message')
# Your One Time Passcode

@component('mail::panel')
{{ $authCode }}
@endcomponent

{{--    This code will expire in {{config('auth.otp_minutes')}} minutes.--}}

@component('mail::subcopy')
Please do not reply to this email. If you did not request this code, please ignore this email.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
