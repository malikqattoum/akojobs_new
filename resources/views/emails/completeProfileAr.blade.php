@component('mail::message')
مرحبا بك في akoJobs

لكي يستطيع اصحاب العمل رؤية بروفايلك يرجى اكمال معلومات بروفايلك

@component('mail::button', ['url' => 'http://www.akojobs.com/account'])
اكمل بروفايلك
@endcomponent

شكرا,<br>
{{ config('app.name') }}
@endcomponent
