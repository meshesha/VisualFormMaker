@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rounded-0">
                <div class="card-header">
                    <h5 class="card-title">
                        
                        <div class="btn-toolbar float-right" role="toolbar">
                            <div class="btn-group" role="group" aria-label="First group">
                                <a href="javascript:history.back()" class="btn btn-primary float-md-right btn-sm" title="Back To Submissions">
                                    <i class="fa fa-arrow-left"></i> Back 
                                </a>

                                @if(Auth::check() && Auth::user()->id == $submission->user_id &&  $form->allows_edit)
                                    <a href="{{route('submitted.user.edit', $submission->id) }}" class="btn btn-primary btn-sm" title="Edit submission">
                                        <i class="fa fa-pencil"></i>Edit
                                    </a> 
                                @endif
                                @if (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']) || (Auth::user()->id == $submission->user_id &&  $is_enabled_delete=='1')))
                                    <form action="{{ route('submitted.destroy', [$form, $submission]) }}" method="POST" id="delete-submission-form" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm rounded-0 delete-submission-btn"  >
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </h5>
                </div>

                <ul class="list-group list-group-flush">
                    @foreach($form_headers as $header)
                        <li class="list-group-item">
                            <strong>{{ $header['label'] ?? $header['name'] }}: </strong> 
                            <span class="float-right">
                                {{ $submission->renderEntryContent($header['name'], $header['type']) }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']) || (Auth::user()->id == $submission->user_id &&  $is_enables_form_details_view=='1')))
        <div class="col-md-4">
            <div class="card rounded-0">
                <div class="card-header">
                    <h5 class="card-title">Form details</h5>
                    
                    <a href="{{ route('forms.show',$submission->forms->id) }}" class="btn btn-info btn-sm float-right" >
                        <i class="fa fa-info-circle"></i>Details
                    </a> 

                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Form: </strong> 
                        <span class="float-right">{{ $form->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Submitted By: </strong> 
                        <span class="float-right">{{ $submission->user->name ?? '' }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Last Updated On: </strong> 
                        <span class="float-right">{{ $submission->updated_at->toDayDateTimeString() }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Submitted On: </strong> 
                        <span class="float-right">{{ $submission->created_at->toDayDateTimeString() }}</span>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection


@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script>
        
    $('.delete-submission-btn').click(function (e) {
        e.preventDefault()

        alertify.confirm("Confirm", "Are you sure you want to delete this record?", function () {
            alertify.success('deleted!')

            $('.delete-submission-btn').attr('disabled', 'disabled');

            $("#delete-submission-form").submit()

        }, function () { alertify.error('Canceled!') })

    });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
@endsection