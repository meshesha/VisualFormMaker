@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('org.update',$edit_dep->id)}}" method="POST">
                @csrf 
                @method('PUT')
                <div class="form-group row">
                    <label for="dep_name" class="col-md-2 col-form-label text-md-right">Department name</label>

                    <div class="col-md-6">
                        <input type="text" id="dep_name" name="dep_name" class="form-control" value="{{$edit_dep->name}}" required  autofocus>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dep_parent" class="col-md-2 col-form-label text-md-right">Parent</label>

                    <div class="col-md-6">
                        <select name="dep_parent" id="dep_parent" class="form-control" required>
                            @if($edit_dep->parent_id == 0 )
                                <option value="0" selected>Root (main)</option>
                            @endif
                            @if ($deps && count($deps) > 0)
                                @foreach ($deps as $dep)
                                    <option value="{{$dep->id}}" @if ($dep->id == $edit_dep->parent_id) selected @endif>{{$dep->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                
                <div class="form-group row">
                    <label for="dep_users" class="col-md-2 col-form-label text-md-right">Users</label>

                    <div class="col-md-6">
                        <select name="dep_users[]" id="dep_users" multiple="multiple" class="form-control" >
                            @if ($users && count($users) > 0)
                                @foreach ($users as $user)
                                     <option value="{{$user->id}}" @if ($user->department == $edit_dep->id) selected="selected" @endif>{{$user->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <a href="javascript:history.back(){{-- route('org.index') --}}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    @endif
@endsection
@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
	<script src="{{ asset('plugins/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form.js') }}" ></script>
    <script type="text/javascript">
        $("#dep_users").select2({
            width: 'resolve' // need to override the changed default
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pristinejs.css') }}" />
    <style>
        .select2-drop-active{
            margin-top: -25px;
        }
    </style>
@endsection
