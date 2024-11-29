@extends('emails.layout')

@section('content')

<div class="body">
    <h1 style="font-size:34px">Please verify your email address </h1>
    <p>Click the link below to indicate your agreement with our <a href="{{ $tcandcsLink }}"> Terms &amp; Conditions</a></p>
    <p><a href="{{ $verifyLink }}">Verify my email address</a></p>
    <p>Why have I recieved this email?</p>
    <p>Your email address has been used to send an email from <a href="{{ $deceasedLink }}">{{ $memorialSite }}</a>, that message is queued awaiting your email address to be verified. Once verified we will do our best to delivery your message</p>
    <p>If you believe you have recieved this email in error, please ignore it.</p>
    <p>This is an unmonitored email box, please use the Contact Us form on <a href="{{ $deceasedLink }}">{{ $memorialSite }}</a> to contact us.
</div>

@endsection
