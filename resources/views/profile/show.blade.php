@extends('layouts.app')

@section('content')
<div class="container snippet">
    <!--
    <div class="row">
  		<div class="col-sm-10"><h1>User name</h1></div>
    </div>
    -->
    <div class="row">
        <div class="col-sm-3"><!--left col-->
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        @if ($user->image != "" && $user->image !== null)
                            src="{{asset('storage/users')}}/{{$user->image}}"
                        @else
                            src="{{asset('storage/users')}}/user.png"
                        @endif
                        alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{$user->name}}</h3>
                    <p class="text-muted text-center">{{$user->title ?? ''}}</p>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!--/col-3-->
    	<div class="col-sm-9">
            <ul class="nav nav-tabs"  id="myTab" role="tablist">
                <li class="nav-item" >
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
                </li>
                @if(auth()->check() && auth()->user()->id == $user->id)
                <li class="nav-item">
                    <a class="nav-link" id="edit-tab" data-toggle="tab" href="#edit" role="tab" aria-controls="edit" aria-selected="false">Edit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password" aria-selected="false">Password</a>
                </li>
                @endif
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <strong>Name</strong>
                            <p>{{$user->name}}</p>
                            <strong>Email</strong>
                            <p>{{$user->email}}</p>
                            <strong>Group</strong>
                            <p>{{implode(", ", $user->roles()->get()->pluck('name')->toArray())}}</p>
                            <strong>Department</strong>
                            <p>{{implode(", ", $user->dep()->get()->pluck('name')->toArray())}}</p>
                        </div>
                    </div>
                </div><!--/tab-profile-->
                @if(auth()->check() && auth()->user()->id == $user->id)
                <div class="tab-pane fade show" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="row">
                            <div class="col">
                                <form class="form" action="{{ route('profile.update', $user) }}" method="post" enctype="multipart/form-data">
                                    @csrf 
                                    <div class="form-group row">
                                        <label for="user_name" class="col-md-2 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input id="user_name" name="user_name" type="text" class="form-control" value="{{ $user->name }}" required autocomplete="name" autofocus>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="user_email" class="col-md-2 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="user_email"  name="user_email" type="email" class="form-control " value="{{ $user->email }}" required autocomplete="email">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label for="user_image" class="col-md-2 col-form-label text-md-right">Image</label>
                                        <div class="col-md-6">
                                            <input type="file" id="user_image" name="user_image" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">Update User</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <!-- Profile Image -->
                                <h5 class="text-center">Preview</h5>
                                <div class="card card-primary">
                                    <div class="card-body box-profile">
                                        <div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle"
                                                id="profile-user-img-preview"
                                                @if ($user->image != "" && $user->image !== null)
                                                    src="{{asset('storage/users')}}/{{$user->image}}"
                                                @else
                                                    src="{{asset('storage/users')}}/user.png"
                                                @endif
                                                alt="User profile picture">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                    $(document).ready(function () {
                        var readURL = function (input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#profile-user-img-preview').attr('src', e.target.result);
                                }

                                reader.readAsDataURL(input.files[0]);
                            }
                        }


                        $("#user_image").on('change', function () {
                            readURL(this);
                        });
                    });
                    </script>
                </div><!--/tab-edit-->
                <div class="tab-pane fade show" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <hr>
                    <div class="card card-primary">
                        <div class="card-body">
                            <form class="form" action="{{ route('profile.setpass', $user) }}" method="post" id="registrationForm">
                                @csrf 
                                <div class="form-group row">
                                    <label for="current_password" class="col-md-2 col-form-label text-md-right">Current password</label>

                                    <div class="col-md-6">
                                         <input type="password" id="current_password"  name="current_password" class="form-control"  placeholder="current password" title="enter your password.">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="new_password" class="col-md-2 col-form-label text-md-right">New password</label>

                                    <div class="col-md-6">
                                         <input type="password" id="new_password"  name="new_password" class="form-control"  placeholder="new password" title="enter new password.">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="new_confirm_password" class="col-md-2 col-form-label text-md-right">Verify</label>

                                    <div class="col-md-6">
                                         <input type="password" id="new_confirm_password"  name="new_confirm_password" class="form-control"  placeholder="verify password" title="verify your password.">
                                    </div>
                                </div>
                                
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!--/tab-password-->
            @endif
        </div><!--/tab-content-->
    </div><!--/col-9-->
</div><!--/row-->
@endsection