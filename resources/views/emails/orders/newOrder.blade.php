@component('mail::message')

Hi {{ $name }}, a new order has arrived for {{ $cartItems['site_name'] }}: 

Order Date: {{ $cartItems['order_date'] }} 

Click  <a href="{{ env('ADMIN_APP_URL').'orders/'.$cartItems['order_id'] }}">HERE</a> to view the order

Thanks,<br>
{{ config('app.name') }}
@endcomponent
