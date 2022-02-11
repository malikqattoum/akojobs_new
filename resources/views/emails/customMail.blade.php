@component('mail::message')

{!! $message !!}

@component('mail::button', ['url' => 'https://akojobs.com/'])
AkoJobs
@endcomponent

{{ config('app.name') }}
@endcomponent
