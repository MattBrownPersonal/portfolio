@extends('emails.layout')

@section('content')

  <div class="body">
  <h1 style="font-size:34px">{{ $greeting ? $greeting : 'Hi,' }}</h1>
  <p>Please see my memorial:</p>
  @if ($editedImage !== 'null')
    <img src="{{ $message->embed($editedImage) }}" alt="Memorial Image" style="border-radius:5px;max-width:100%">
  @endif
  <p style="font-style:italic">{{ $custommessage ?: '' }}</p>
  
  <table style="width: 100%; padding-top: 20px" class="productDetails">
    <thead>
      @foreach (json_decode($customImageDetails) as $customImageDetail)
            <th style="text-align:center">{!! $customImageDetail->type_name !!}</th>
        @endforeach
        <th >Price</th>
    </thead>
    <tr>
        @foreach (json_decode($customImageDetails) as $customImageDetail)
            <td >{!! $customImageDetail->name !!}</td>
        @endforeach
        <td>£{!! $totalPrice !!}</td>
    </tr>
  </table>

  <p>If you'd like to create your own memorial, you can do so at the link below.</p>
  <p>Thanks,</p>
  <p>{{$sitename}}</p>
  <div class="mem">
  <a href="{{ $url }}" class="mem-url">Create a Memorial</a>
  </div>
  </div>

@endsection

