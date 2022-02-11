@component('mail::message')
Welcome to akoJobs

Inorder to let the employers to see your profile, you should complete your profile

@component('mail::button', ['url' => 'http://www.akojobs.com/account'])
Complete my profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
