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
                        <th scope="col">status</th>
                        <th scope="col">create at</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groups as $group)
                        <tr>
                            <th scope="row">{{$group->id}}</th>
                            <td>{{$group->name}}</td>
                            <td>
                                @if($group->group_status == 1)
                                Active
                                @elseif($group->group_status == 2)
                                Disabled
                                @endif
                            <td>{{$group->created_at}}</td>
                            <td>
                                @can('manager')
                                    <!-- not administrator -->
                                    @if ($group->id != "1")
                                        <a href="{{route('groups.show', $group->id)}}" class="btn btn-info btn-sm">Show</a>
                                        <a href="{{route('groups.edit', $group->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('groups.destroy', $group->id)}}" method="POST" id="delete-group-form-{{ $group->id }}" class="delete-group-form float-right">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" data-formid="{{ $group->id }}" class="btn btn-danger btn-sm delete-group-btn">Delete</button>
                                        </form>
                                    @else
                                        <a href="{{route('groups.show', $group->id)}}" class="btn btn-info btn-sm">Show</a>
                                    @endif

                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
            @if($groups->hasPages())
                <div class="card-footer mb-0 pb-0">
                    <div>{{ $groups->links() }}</div>
                </div>
            @endif
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <a href="{{ route('groups.create') }}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-user"></i> New Group
        </a>
    @endif
@endsection



@section('scripts')
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script>
        
    $(".delete-group-btn").click(function (e) {
        e.preventDefault();
        var form_id = $(this).data("formid");
        alertify.confirm("Confirm", "Are you sure you want to delete this group?", function () {
            $("#delete-group-form-" + form_id).submit();
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
