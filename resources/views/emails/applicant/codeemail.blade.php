@component('mail::message')
# Dear {{ $name }},

{{ $content ? $content : "We know how difficult the loss of a loved one can be. So, as part of our service, we've written a range of bereavement articles to support you. We've also created a dedicated space for you to personalise a lasting memorial. This can be an important part of the grieving process. Not only will your tribute help you remember your loved one, but it will create a place for friends and family to visit and reminisce. You can access the support articles and view your dedicated memorial page here. Your personalised memorial page will be accessible at any time during the next three years. Simply keep hold of this email until you're ready. Or contact us at a later date." }}

<p>Warm regards,</p>

<p>{{ $site }}</p>

@component('mail::button', ['url' => $url])
Enter
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
