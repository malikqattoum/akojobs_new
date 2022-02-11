@component('mail::message')
Welcome to akoJobs

Inorder to let the employers to reach you, Please upload your resume

@component('mail::button', ['url' => 'http://www.akojobs.com/account'])
My account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
