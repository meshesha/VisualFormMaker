@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.users.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="users_paginate" class="col-md-2 col-form-label text-md-right"><strong>paginate</strong></label>
                    <div class="col-md-3">
                        <input type="number" id="users_paginate" name="users_paginate" class="form-control" value="{{ $users['paginate'] }}"  min="1" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="group_selection" class="col-md-2 col-form-label text-md-right"><strong>Default groups</strong></label>
                    <div class="col-md-3">
                        <select name="group_selection[]" id="group_selection" class="group_options form-control"  multiple="multiple"  style="width: 99%">
                            @foreach($groups as $option)
                                <option value="{{ $option['id'] }}" @if(in_array($option['id'], explode(',', $users['default_groups']))) selected="selected" @endif>{{ $option['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="default_user_status" class="col-md-2 col-form-label text-md-right"><strong>Default user status</strong></label>
                    <div class="col-md-3">
                        <select name="default_user_status" id="default_user_status" class="form-control" required>
                             <option value="0" @if ($users['default_user_status']=="0") selected @endif>Disabled</option>
                             <option value="1" @if ($users['default_user_status']=="1") selected @endif>Active</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="allows_registration" class="col-md-2 col-form-label text-md-right"><strong>Allow registration</strong></label>
                    <div class="col-md-3">
                        <select name="allows_registration" id="allows_registration" class="form-control" required>
                             <option value="0" @if ($users['allows_registration']=="0") selected @endif>No</option>
                             <option value="1" @if ($users['allows_registration']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="allow_reset_password" class="col-md-2 col-form-label text-md-right"><strong>Allow reset password on login page</strong></label>
                    <div class="col-md-3">
                        <select name="allow_reset_password" id="allow_reset_password" class="form-control" required>
                             <option value="0" @if ($users['allow_reset_password']=="0") selected @endif>No</option>
                             <option value="1" @if ($users['allow_reset_password']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="allow_remember" class="col-md-2 col-form-label text-md-right"><strong>Allow remember on login page</strong></label>
                    <div class="col-md-3">
                        <select name="allow_remember" id="allow_remember" class="form-control" required>
                             <option value="0" @if ($users['allow_remember']=="0") selected @endif>No</option>
                             <option value="1" @if ($users['allow_remember']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-2 offset-md-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
	<script src="{{ asset('plugins/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>

    <script type="text/javascript">
        
        $(".group_options").select2({
            width: 'resolve' // need to override the changed default
        });
        
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
@endsection