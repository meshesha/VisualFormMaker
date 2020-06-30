@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                New User
            </h5>
            <div class="card-tools">
                <div class="btn-toolbar float-md-right" role="toolbar">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.users.index')}}" class="btn btn-primary ">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="user_status" class="col-md-2 col-form-label text-md-right">Users Status</label>

                    <div class="col-md-6">
                        <select name="user_status" id="user_status" class="form-control" required>
                            <option value="1" @if($default_user_status =="1") selected @endif>Active</option>
                            <option value="0" @if($default_user_status =="0") selected @endif>Disabled</option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="new_password" class="col-md-2 col-form-label text-md-right">Password</label>
                    <div class="col-md-6">
                        <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="confirm_password" class="col-md-2 col-form-label text-md-right">Confirm Password</label>
                    <div class="col-md-6">
                        <input id="confirm_password" type="password" class="form-control" name="confirm_password" autocomplete="current-password">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="user_dep" class="col-md-2 col-form-label text-md-right">Department</label>

                    <div class="col-md-6">
                        <select name="user_dep" id="user_dep" class="form-control" required>
                            @foreach($deps as $dep)
                                <option value="{{ $dep->id }}">
                                    {{ $dep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="form-group row" >
                    <label for="roles" class="col-md-2 col-form-label text-md-right">Roles/Groups</label>
                    <div class="col-md-6">
                        <select name="roles[]" id="roles" class="group_option form-control" multiple="multiple" style="width: 99%">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @if(in_array($role->id, explode(',', $default_groups))) selected="selected" @endif>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
	<script src="{{ asset('plugins/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form.js') }}" ></script>
    <script type="text/javascript">
        $(".group_option").select2({
            width: 'resolve' // need to override the changed default
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
@endsection