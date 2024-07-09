@component('mail::message')
    # Your Email Update Token

    @component('mail::panel')
        {{ $token }}
    @endcomponent

    {{--    This code will expire in {{config('auth.otp_minutes')}} minutes.--}}

    @component('mail::subcopy')
        Please do not reply to this email. If you did not request this code, please ignore this email.
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
