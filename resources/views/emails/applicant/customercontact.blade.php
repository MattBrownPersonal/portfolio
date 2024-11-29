@component('mail::message')
You have recieved a new customer query.

Customer's Name: {{ $name }} <br>
Customer's Email: {{ $customerEmail }} <br>
Contact Number: {{ $number }} <br>
Message: {{ $message }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
