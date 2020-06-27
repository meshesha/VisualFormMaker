@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('groups.store')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="group_status" class="col-md-2 col-form-label text-md-right">Status</label>

                    <div class="col-md-6">
                        <select name="group_status" id="group_status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="2" >Disabled</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="group_name" class="col-md-2 col-form-label text-md-right">Group/Role name</label>

                    <div class="col-md-6">
                        <input type="text" id="group_name" name="group_name" class="form-control" value="" required  autofocus>
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
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <a href="{{ route('groups.index') }}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    @endif
@endsection

