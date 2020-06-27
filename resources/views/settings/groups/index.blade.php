@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('settings.groups.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="groups_paginate" class="col-md-2  text-md-right"><strong>paginate</strong></label>
                    <div class="col-md-3">
                        <input type="number" id="groups_paginate" name="groups_paginate" class="form-control" value="{{ $groups['paginate'] }}"  min="1" />
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