@component('mail::message')
# Welcome!

<p>Hi, {{ $name }}</p>
<p>{{ $body }}</p>

@component('mail::button', ['url' => $url])
Click here to set your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
