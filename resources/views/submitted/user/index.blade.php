@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-0">
                @if($submissions->count())
                    <div class="table-responsive">
                        <table class="table table-bordered d-table table-striped pb-0 mb-0">
                            <thead>
                                <tr>
                                    <th class="five">#</th>
                                    <th class="">Form</th>
                                    <th class="twenty-five">Updated On</th>
                                    <th class="twenty-five">Created On</th>
                                    <th class="fifteen">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $submission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $submission->forms->name }}</td>
                                        <td>{{ $submission->updated_at->toDayDateTimeString() }}</td>
                                        <td>{{ $submission->created_at->toDayDateTimeString() }}</td>
                                        <td>
                                            <a href="{{route('submitted.show', [$submission->forms->id, $submission->id])}}" class="btn btn-info btn-sm" title="View submission">
                                                <i class="fa fa-eye"></i> View
                                            </a> 

                                            @if($submission->forms->allows_edit)
                                                <a href="{{route('submitted.user.edit', $submission->id) }}" class="btn btn-primary btn-sm" title="Edit submission">
                                                    <i class="fa fa-pencil"></i>Edit
                                                </a> 
                                            @endif

                                            @if (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']) || (Auth::user()->id == $submission->user_id &&  $is_enabled_delete=='1')))
                                                <form action="{{ route('submitted.destroy', [$submission->forms, $submission]) }}" method="POST" id="deleteSubmissionForm_{{ $submission->id }}" class="d-inline-block">
                                                    @csrf 
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm confirm-del-recorf" data-rid="{{$submission->id}}" title="Delete submission">
                                                        <i class="fa fa-trash"></i> 
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($submissions->hasPages())
                        <div class="card-footer mb-0 pb-0">
                            <div>{{ $submissions->links() }}</div>
                        </div>
                    @endif
                @else
                    <div class="card-body">
                        <h4 class="text-danger text-center">
                            No submission to display.
                        </h4>
                    </div>  
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('action_buttons')
    
    <div class="btn-toolbar float-md-right" role="toolbar">
        <div class="btn-group" role="group" aria-label="Third group">
            <a href="{{ route('forms.index') }}" class="btn btn-primary float-md-right btn-sm" title="Back To My Forms">
                <i class="fa fa-th-list"></i> Back to Forms
            </a>
        </div>
    </div>
@endsection

@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/confirm_del_record.js') }}" ></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
@endsection