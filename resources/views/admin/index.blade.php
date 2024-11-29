@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-12">
        <main-admin-component :current-user="{{ (new App\Http\Resources\UserResource(Auth::user()))->toJson() }}" />
    </div>
</div>
@endsection
