@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Table View Settings
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.departments.table.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="dep_table_paginate" class="col-md-2 col-form-label text-md-right"><strong>paginate</strong></label>
                    <div class="col-md-3">
                        <input type="number" id="dep_table_paginate" name="dep_table_paginate" class="form-control" value="{{ $table['paginate'] }}" min="1" />
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
                 Tree View Settings
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.departments.tree.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="tree_direction" class="col-md-2 col-form-label text-md-righ"><strong>direction</strong></label>
                    <div class="col-md-3">
                        <select name="tree_direction" id="tree_direction" class="form-control" required>
                             <option value="NORTH" @if ($tree['direction']=="NORTH") selected @endif>NORTH</option>
                             <option value="SOUTH" @if ($tree['direction']=="SOUTH") selected @endif>SOUTH</option>
                             <option value="EAST" @if ($tree['direction']=="EAST") selected @endif>EAST</option>
                             <option value="WEST" @if ($tree['direction']=="WEST") selected @endif>WEST</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="profile_link" class="col-md-2 col-form-label text-md-righ"><strong>Show user profile link</strong></label>
                    <div class="col-md-3">
                        <select name="profile_link" id="profile_link" class="form-control" required>
                             <option value="0" @if ($tree['profile_link']=="0") selected @endif>No</option>
                             <option value="1" @if ($tree['profile_link']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_title" class="col-md-2 col-form-label text-md-righ"><strong>Show user title</strong></label>
                    <div class="col-md-3">
                        <select name="is_title" id="is_title" class="form-control" required>
                             <option value="0" @if ($tree['title']=="0") selected @endif>No</option>
                             <option value="1" @if ($tree['title']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_email" class="col-md-2 col-form-label text-md-righ"><strong>Show user email</strong></label>
                    <div class="col-md-3">
                        <select name="is_email" id="is_email" class="form-control" required>
                             <option value="0" @if ($tree['email']=="0") selected @endif>No</option>
                             <option value="1" @if ($tree['email']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_image" class="col-md-2 col-form-label text-md-righ"><strong>Show user image</strong></label>
                    <div class="col-md-3">
                        <select name="is_image" id="is_image" class="form-control" required>
                             <option value="0" @if ($tree['image']=="0") selected @endif>No</option>
                             <option value="1" @if ($tree['image']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_name" class="col-md-2 col-form-label text-md-righ"><strong>Show user name</strong></label>
                    <div class="col-md-3">
                        <select name="is_name" id="is_name" class="form-control" required>
                             <option value="0" @if ($tree['name']=="0") selected @endif>No</option>
                             <option value="1" @if ($tree['name']=="1") selected @endif>Yes</option>
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