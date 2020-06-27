@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Forms Settings
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.forms.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="forms_paginate" class="col-md-2 col-form-label text-md-right"><strong>paginate</strong></label>
                    <div class="col-md-3">
                        <input type="number" id="forms_paginate" name="forms_paginate" class="form-control" value="{{ $forms['paginate'] }}"  min="1" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="default_allows_edit" class="col-md-2 col-form-label text-md-right"><strong>Default allows edit</strong></label>
                    <div class="col-md-3">
                        <select name="default_allows_edit" id="default_allows_edit" class="form-control" required>
                             <option value="0" @if ($forms['default_allows_edit']=="0") selected @endif>No</option>
                             <option value="1" @if ($forms['default_allows_edit']=="1") selected @endif>Yes</option>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                User Submissions Settings
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.my_submissions.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="submissions_paginate" class="col-md-2 col-form-label text-md-right"><strong>paginate</strong></label>
                    <div class="col-md-3">
                        <input type="number" id="submissions_paginate" name="submissions_paginate" class="form-control" value="{{ $user_submissions['paginate'] }}"  min="1" />
                    </div>
                </div>
                <div class="form-group row">
                        <label for="enable_form_details_view" class="col-md-2 col-form-label text-md-right"><strong>Enable form details view</strong></label>
                        <div class="col-md-3">
                        <select name="enable_form_details_view" id="enable_form_details_view" class="form-control" required>
                             <option value="0" @if ($user_submissions['enable_form_details_view']=="0") selected @endif>No</option>
                             <option value="1" @if ($user_submissions['enable_form_details_view']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="enable_delete" class="col-md-2 col-form-label text-md-right"><strong>Enable delete</strong></label>
                    <div class="col-md-3">
                        <select name="enable_delete" id="enable_delete" class="form-control" required>
                             <option value="0" @if ($user_submissions['enable_delete']=="0") selected @endif>No</option>
                             <option value="1" @if ($user_submissions['enable_delete']=="1") selected @endif>Yes</option>
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