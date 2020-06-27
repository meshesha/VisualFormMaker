@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{$title??''}}</h5>
            <div class="card-tools">
                <a href="{{ route('groups.create') }}" class="btn btn-sm btn-primary float-md-right">
                    <i class="fa fa-user"></i> New Department
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="my_org_tree"></div>
        </div>
    </div>
</div>
@endsection
