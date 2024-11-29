@extends('emails.layout')

@section('content')
<div class="body">
<h1 style="font-size:34px">{{ $greeting ? $greeting : 'Hi,' }}</h1>
<p>Please see my memorial:</p>
<img src="{{ $message->embed($editedImage) }}" alt="Memorial Image" style="border-radius:5px;max-width:100%">
<p style="font-style:italic">{{ $custommessage ?: "" }}</p>
<p>If you'd like to create your own memorial, you can do so at the link below.</p>
<p>Thanks,</p>
<p>{{$sitename}}</p>
<div class="mem">
<a href="{{ $url }}" class="mem-url">Create a Memorial</a>
</div>
</div>
@endsection