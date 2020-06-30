@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card rounded-0">
        <div class="card-body">
            <div id="fb-render"></div>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <div class="btn-toolbar float-md-left" role="toolbar">
            <div class="btn-group" role="group">
                <a href="{{ route('forms.index') }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-arrow-left"></i> Forms
                </a>
                <a href="{{ route('forms.show', $form->id) }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-info-circle"></i> Details
                </a>
                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-edit"></i> Edit form
                </a>
                <a href="#" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-th-list"></i> Submissions
                </a>
            </div>
        </div>
        <form action="{{route('forms.destroy', $form->id)}}" method="POST" class="delete_form d-inline-block float-right">
            @csrf 
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm delete_form_btn" >
                <i class="fa fa-trash"></i> Delete form
            </button>
        </form>
    @endif
@endsection

@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form-render.min.js') }}" ></script>
    <script type="text/javascript">
        window._form_builder_content = {!! json_encode($form->form_builder_json) !!}
    </script>
    <script src="{{ asset('js/formbuilder/render-form.js') }}" ></script>
@endsection

