@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-2  text-md-right">
                    <span class="form-group">Status: </span>
                </div>

                <div class="col-md-6">
                    <span class="form-group">{{$status}}</span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2  text-md-right">
                    <span class="form-group">Group/Role name: </span>
                </div>

                <div class="col-md-6">
                    <span class="form-group">{{$group->name}}</span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2  text-md-right">
                    <span class="form-group">Users: </span>
                </div>

                <div class="col-md-6">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>name</th>
                                <th>email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($users && count($users)>0)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id}}</td>
                                    <td>{{ $user->name}}</td>
                                    <td>{{ $user->email}}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <div class="btn-toolbar float-md-left" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('groups.index') }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <form action="{{ route('groups.edit', $group->id)}}" method="POST" class="delete-group-form float-right">
            @csrf 
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm delete-group-btn"><i class="fa fa-trash"></i>Delete</button>
        </form>
    @endif
@endsection


@section('scripts')
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script>
        
    $(".delete-group-btn").click(function (e) {
        e.preventDefault();
        alertify.confirm("Confirm", "Are you sure you want to delete this group?", function () {
            $(".delete-group-form").submit();
            alertify.success('Deleted')
        }, function () { alertify.error('Canceled!') })
    });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pristinejs.css') }}" />
@endsection

