@extends('layouts.render')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card rounded-0">
                <div class="card-header">
                    <div class="row justify-content-center">
                        @guest
                        @else
                            <div class="col-md-10">
                                <h5 class="m-0 text-dark">{{ Auth::user()->name }}</h5>
                            </div><!-- /.col -->
                            <div class="col">
                                <a class="" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div><!-- /.col -->
                        @endguest
                    </div><!-- /.row -->
                </div>

                <form action="{{ route('form.submit', $form->id) }}" method="POST" id="submit-form" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card-body">
                        <div id="fb-render"></div>
                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary submit-form-btn" >
                            <i class="fa fa-arrow-circle-right"></i> Submit
                        </button>
                        <button type="button" class="btn btn-warning clear-form-btn" >
                            <i class="fa fa-eraser"></i> Clear
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
	<script src="{{ asset('js/jquery-ui/jquery-ui.min.js') }}" ></script>
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form-render.min.js') }}" ></script>
    <script type="text/javascript">
        window._form_builder_content = {!! json_encode($form->form_builder_json) !!}
    </script>
    <script src="{{ asset('js/formbuilder/submit_form.js') }}" ></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pristinejs.css') }}" />
@endsection