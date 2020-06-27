@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Edit User {{ $user->name }} 
            </h5>
            <div class="card-tools">
                <div class="btn-toolbar float-md-right" role="toolbar">
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.users.index')}}" class="btn btn-primary ">Back</a>
                        <a href="{{ route('admin.users.changepass', $user)}}" class="btn btn-sm btn-warning ">
                            <i class="fa fa-key"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user)}}" method="POST">
                @csrf 
                @method('PUT')

                <div class="form-group row">
                    <label for="user_status" class="col-md-2 col-form-label text-md-right">Users Status</label>

                    <div class="col-md-6">
                        <select name="user_status" id="user_status" class="form-control" required>
                            <option value="1" @if($user->status =="1") selected @endif>
                                Active
                            </option>
                            <option value="0" @if($user->status =="0") selected @endif>
                                Disabled
                            </option>
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

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
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label for="user_dep" class="col-md-2 col-form-label text-md-right">Department</label>

                    <div class="col-md-6">
                        <select name="user_dep" id="user_dep" class="form-control" required>
                            @foreach($deps as $dep)
                                <option value="{{ $dep->id }}" @if($user->department == $dep->id) selected @endif>
                                    {{ $dep->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="roles" class=" col-md-2 col-form-label text-md-right">Roles/Groups</label>

                    <div class="col-md-6">
                        @foreach($roles as $role)
                            <div class="form-check">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" @if($user->roles->pluck('id')->contains($role->id)) checked @endif />
                                <label for="roles[]">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
