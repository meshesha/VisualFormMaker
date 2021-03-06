@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <form action="{{ route('forms.store') }}" method="POST" id="createFormForm" enctype="multipart/form-data">
            @csrf 
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="form_name" class="col-form-label">Form Name</label>
                            <input type="text" id="form_name" name="form_name"  class="form-control" value="" required autofocus placeholder="Enter Form Name">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="visibility" class="col-form-label">Form Visibility</label>

                            <select name="visibility" id="visibility" class="form-control" required="required" >
                                <option value="" selected="selected"></option>
                                @foreach($visibility as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" style="display: none;" id="group_dep_DIV">
                        <div class="form-group"  style="display: none;"  id="group_DIV">
                            <label for="group_selection" class="col-form-label "><span class="group_dep_label"></span>
                            <select name="group_selection[]" id="group_selection" class="group_dep_option form-control" multiple="multiple"  required="required" style="width: 99%">
                               <!-- <option value="" selected="selected"></option>-->
                                @foreach($groups as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                                @endforeach
                            </select>
                            </label>
                        </div>
                        <div class="form-group"  style="display: none;"  id="dep_DIV">
                            <label for="deps_selection" class="col-form-label "><span class="group_dep_label"></span>
                            <select name="deps_selection[]" id="deps_selection" class="group_dep_option form-control" multiple="multiple"  required="required"  style="width: 99%">
                                <!--<option value="" selected="selected"></option>-->
                                @foreach($deps as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
                                @endforeach
                            </select>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fa fa-info-circle"></i> 
                            Click on or drag and drop components onto the main panel to build your form content.
                        </div>

                        <div id="fb-editor" class="fb-editor"></div>
                        <input type="hidden" id="formbuilder_json" name="formbuilder_json" required />
                    </div>
                </div>
            </div>
        </form>

        <div class="card-footer" id="fb-editor-footer">
            <button type="button" class="btn btn-warning fb-clear-btn">
                <i class="fa fa-remove"></i> Clear Form 
            </button> 
            <button type="button" class="btn btn-primary fb-save-btn">
                <i class="fa fa-save"></i> Save Form
            </button>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    <a href="{{ route('forms.index') }}" class="btn btn-sm btn-primary float-md-right" id="back_to_form_btn">
        <i class="fa fa-arrow-left"></i> Back To Forms
    </a>
@endsection
@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
	<script src="{{ asset('plugins/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
	<script src="{{ asset('js/formbuilder/form-builder.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form-render.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form.js') }}" ></script>
    <script type="text/javascript">
        $(".group_dep_option").select2({
            width: 'resolve' // need to override the changed default
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pristinejs.css') }}" />
@endsection