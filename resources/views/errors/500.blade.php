@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-danger">
       <h1>Error 500 - Unexpected Error</h1>
       <h2><p>You can go back to <a href="{{ url('/') }}">Home page</a>.</p></h2>
    </div>
</div>
@endsection