@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">name</th>
                        <th scope="col">email</th>
                        <th scope="col">Department</th>
                        <th scope="col">Roles/Groups</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th scope="row">{{$user->id}}</th>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{implode(", ", $user->dep()->get()->pluck('name')->toArray())}}</td>
                            <td>{{implode(", ", $user->roles()->get()->pluck('name')->toArray())}}</td>
                            <td>
                                @can('manager')
                                    <a href="{{route('admin.users.edit', $user->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user)}}" method="POST" class="delete-user-form float-right">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-user-btn">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
            
            @if($users->hasPages())
                <div class="card-footer mb-0 pb-0">
                    <div>{{ $users->links() }}</div>
                </div>
            @endif
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-user"></i> New User
        </a>
    @endif
@endsection


@section('scripts')
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script type="text/javascript">
        
    $(".delete-user-btn").click(function (e) {
        e.preventDefault();
        alertify.confirm("Confirm", "Are you sure you want to delete this user?", function () {
            $(".delete-user-form").submit();
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