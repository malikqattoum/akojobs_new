@component('mail::message')
مرحبا بك في akoJobs

لكي يستطيع اصحاب العمل الوصول اليك, يرجى رفع سيرتك الذاتية.

@component('mail::button', ['url' => 'http://www.akojobs.com/account'])
حسابي
@endcomponent

شكرا,<br>
{{ config('app.name') }}
@endcomponent
