@extends('emails.layout')

@section('content')
<div class="body">
  <h1 style="font-size:34px">Thank you for your order</h1>
  <p>Here are the details for your order</p>
  
  <img src="{{ $message->embed($request->productImage) }}" alt="Memorial Image" style="border-radius:5px;max-width:100%">
  
  <table style="width: 100%; padding-top: 20px; margin-top: 20px; margin-bottom: 20px" class="productDetails">
    <thead>
      <th>Order Number</th>
      @foreach (json_decode($request->productDetails) as $product)
            <th style="text-align:center">{!! $product->type_name !!}</th>
        @endforeach
        <th >Price</th>
    </thead>
    <tr>
      <td>{{ $orderNumber }}</td>
        @foreach (json_decode($request->productDetails) as $product)
            <td >{!! $product->name !!}</td>
        @endforeach
        <td>£{!! $request->total_price !!}</td>
    </tr>
  </table>


  <p>Thanks,</p>
  <p>{{$site->name}}</p>
  <div class="mem">
  <a href="#" class="mem-url">Create a Memorial</a>
  </div>
  </div>

@endsection