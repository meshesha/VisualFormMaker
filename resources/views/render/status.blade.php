@extends('layouts.render')
@section('content')
<div class="container">
        <div class="row ">
            @guest
            @else
                <div class="col-sm">
                    <h5 class="m-0 text-dark">{{ Auth::user()->name }}</h5>
                </div><!-- /.col -->
                <div class="col-2">
                    <a class="" href="{{ route('dashboard') }}">Home</a>
                </div><!-- /.col -->
            @endguest
        </div><!-- /.row -->
</div>
@endsection
