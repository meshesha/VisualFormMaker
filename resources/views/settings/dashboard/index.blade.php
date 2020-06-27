@extends('layouts.app')

@section('content')
<div class="container snippet">
    <div class="card card-primary">
        <div class="card-body">
            <form action="{{ route('settings.dashboard.update')}}" method="POST">
                @csrf 
                <div class="form-group row">
                    <label for="is_forms_link" class="col-md-2 col-form-label text-md-right"><strong>Show forms card?</strong></label>
                    <div class="col-md-2">
                        <select name="is_forms_link" id="is_forms_link" class="form-control" required>
                             <option value="0" @if ($dashboard['forms']=="0") selected @endif>No</option>
                             <option value="1" @if ($dashboard['forms']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_users_link" class="col-md-2 col-form-label text-md-right"><strong>Show users card?</strong></label>
                    <div class="col-md-2">
                        <select name="is_users_link" id="is_users_link" class="form-control" required>
                             <option value="0" @if ($dashboard['users']=="0") selected @endif>No</option>
                             <option value="1" @if ($dashboard['users']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_groups_link" class="col-md-2 col-form-label text-md-right"><strong>Show groups card?</strong></label>
                    <div class="col-md-2">
                        <select name="is_groups_link" id="is_groups_link" class="form-control" required>
                             <option value="0" @if ($dashboard['groups']=="0") selected @endif>No</option>
                             <option value="1" @if ($dashboard['groups']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_departments_link" class="col-md-2 col-form-label text-md-right"><strong>Show departments card?</strong></label>
                    <div class="col-md-2">
                        <select name="is_departments_link" id="is_departments_link" class="form-control" required>
                             <option value="0" @if ($dashboard['departments']=="0") selected @endif>No</option>
                             <option value="1" @if ($dashboard['departments']=="1") selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="is_submitted_link" class="col-md-2 col-form-label text-md-right"><strong>Show submitted card?</strong></label>
                    <div class="col-md-2">
                        <select name="is_submitted_link" id="is_submitted_link" class="form-control" required>
                             <option value="0" @if ($dashboard['submitted']=="0") selected @endif>No</option>
                             <option value="1" @if ($dashboard['submitted']=="1") selected @endif>Yes</option>
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