@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card rounded-0">
                <form action="{{route('submitted.update', $submission->id) }}" method="POST" id="submitForm" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card-body">
                        <div id="fb-render"></div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary confirm-form" data-form="submitForm" data-message="Submit update to your entry for '{{ $submission->forms->name }}'?">
                            <i class="fa fa-submit"></i> Submit Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    <div class="btn-toolbar float-md-right" role="toolbar">
        <div class="btn-group" role="group" aria-label="Third group">
            <a href="javascript:history.back(){{-- route('submitted.user.index') --}}" class="btn btn-primary float-md-right btn-sm" title="Back To My Forms">
                <i class="fa fa-th-list"></i> Back to Forms
            </a>
        </div>
    </div>
@endsection

@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form-render.min.js') }}" ></script>
    <script type="text/javascript">
        window._form_builder_content = {!! json_encode($form_builder_array) !!}
    </script>
    
    <script src="{{ asset('js/formbuilder/render-form.js') }}" ></script>
@endsection

