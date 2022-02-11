@component('mail::message')

You have invited to register on Akojobs

@component('mail::button', ['url' => $invitationUrl])
    Accept the invitation
@endcomponent

{{ config('app.name') }}
@endcomponent
