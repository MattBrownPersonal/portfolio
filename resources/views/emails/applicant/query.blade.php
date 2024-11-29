@component('mail::message')
# New Query

You have received a new query from {{ $name }}.

{{ $query }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
