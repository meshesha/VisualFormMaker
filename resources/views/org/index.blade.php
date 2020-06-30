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
                        <th scope="col">parent</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deps as $dep)
                        <tr>
                            <th scope="row">{{$dep->id}}</th>
                            <td>{{$dep->name}}</td>
                            <td>
                                @if($dep->parent_id != 0)
                                    {{$dep->firstWhere('id',$dep->parent_id)->name}}
                                @endif
                            </td>
                            <td>
                                @can('manager')
                                    <a href="{{route('org.edit', $dep->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('org.destroy', $dep)}}" method="POST" id="delete-dep-form-{{ $dep->id }}" class="delete-dep-form float-right">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" data-formid="{{ $dep->id }}" class="btn btn-danger btn-sm delete-dep-btn">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
            @if($deps->hasPages())
                <div class="card-footer mb-0 pb-0">
                    <div>{{ $deps->links() }}</div>
                </div>
            @endif
    </div>
</div>
@endsection


@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <a href="{{ route('org.treeview') }}" class="btn btn-sm btn-primary ">
            <i class="fas fa-sitemap"></i> Tree View
        </a>
        <a href="{{ route('org.create') }}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-user"></i> New Department
        </a>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script>
        
    $(".delete-dep-btn").click(function (e) {
        e.preventDefault();
        var form_id = $(this).data("formid");
        alertify.confirm("Confirm", "Are you sure you want to delete this department?", function () {
            $("#delete-dep-form-" + form_id).submit();
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