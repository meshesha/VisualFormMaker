@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-0">
                @if($submitted_entries->count())
                    <div class="table-responsive">
                        <table class="table table-bordered d-table table-striped pb-0 mb-0">
                            <thead>
                                <tr>
                                    <th class="five">#</th>
                                    <th class="fifteen">User Name</th>
                                    @foreach($form_headers as $header)
                                        <th>{{ $header['label'] ?? $header['name'] }}</th>
                                    @endforeach
                                    <th class="fifteen">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submitted_entries as $submission)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $submission->user->name ?? 'n/a' }}</td>
                                        @foreach($form_headers as $header)
                                            <td>
                                                {{ 
                                                    $submission->renderEntryContent(
                                                        $header['name'], $header['type'], true
                                                    ) 
                                                }}
                                            </td>
                                        @endforeach
                                        <td>
                                            <a href="{{ route('submitted.show', [$form->id, $submission->id]) }}" class="btn btn-info btn-sm" title="View submission">
                                                <i class="fa fa-eye"></i> View
                                            </a> 

                                            @if(Auth::check() && Auth::user()->id == $submission->user_id &&  $form->allows_edit)
                                                <a href="{{route('submitted.user.edit', $submission->id) }}" class="btn btn-primary btn-sm" title="Edit submission">
                                                    <i class="fa fa-pencil"></i>Edit
                                                </a> 
                                            @endif

                                            @if (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']) || (Auth::user()->id == $submission->user_id &&  $is_enabled_delete=='1')))
                                                <form action="{{ route('submitted.destroy', [$form, $submission]) }}" method="POST" id="deleteSubmissionForm_{{ $submission->id }}" class="d-inline-block">
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
                    @if($submitted_entries->hasPages())
                        <div class="card-footer mb-0 pb-0">
                            <div>{{ $submitted_entries->links() }}</div>
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
    {{--@if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))--}}
        <div class="btn-toolbar float-md-right" role="toolbar">
            <div class="btn-group" role="group">
                <a href="javascript:history.back()" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    {{--@endif--}}
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
