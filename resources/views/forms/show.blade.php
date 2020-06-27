@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card rounded-0">
                <div class="row pb-4 mt-2 ml-1 border-bottom">
                    <label for="form_type_name" class="col-sm-3"><strong>Form type: </strong></label>
                    <div class="col-sm-4">
                        <span id="form_type_name">{{ $form_types->firstWhere('id',$form->type)->name }}</span>
                    </div>
                </div>
                <div class="row pb-4 mt-2 ml-1 border-bottom">
                    <label for="form_status_btn" class="col-sm-3"><strong>Form status: </strong></label>
                    <div class="col-sm-4">
                        <form action="{{route('forms.status', $form->id)}}" method="POST" style="display:none" id="form_status_frm">
                            @csrf
                            <input type="hidden" name="form_status" id="form_status" value="{{$form->status}}"/>
                        </form>
                        <div id="form_status_btn" class="btn-group btn-group-toggle " data-toggle="buttons">
                            @foreach($form_statuses as $option)
                                <label class="btn btn-sm @if($option['id'] == "1") btn-outline-danger @elseif($option['id'] == "2") btn-outline-success @else btn-outline-info @endif  @if($option['id'] == $form->status) active @endif">
                                    <input type="radio" class="form-status-options-btn" name="options" id="option-{{ $option['id'] }}" value="{{ $option['id'] }}" autocomplete="off"  @if($option['id'] == $form->status) checked @endif > {{$option["name"]}}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row pb-4 pt-2 ml-1 border-bottom">
                    <label for="form_public_url" class="col-sm-3"><strong>Public URL: </strong></label>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col">
                                <a href="{{route('form.render', $form->id)}}" class="pr-3 " target="_blank">
                                    <small id="form_url_link">{{route('form.render', $form->id)}}</small>
                                </a>
                            </div>
                            <div class="col-sm-1">
                                <button id="form_url_name" class="btn btn-primary btn-sm clipboard" data-clipboard-target="#form_url_link" title="Copy form URL to clipboard">
                                    <i class="fa fa-clipboard"></i> <!--Copy-->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pb-4 pt-2 ml-1 border-bottom">
                    <label for="form_managers_users" class="col-sm-3"><strong>Form Managers: </strong></label>
                    <div class="col-sm-8"> 
                        <form id="form_managers_users" action="{{ route('form.managers', $form->id) }}" method="post" >
                            @csrf
                            <div class="row">
                                <div class="col">
                                    @if(count($users) > 0)
                                        <input type="hidden" id="original_managers" value="{{ implode(',', $form_admins)}}" />
                                        <select name="form_managers[]"  class="col-md-12 form-managers-select"  multiple="multiple">
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if(in_array($user->id,$form_admins)) selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-sm-1">
                                    <button class="btn btn-primary btn-xs form_managers_btn" type="submit" >Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row pb-4 pt-2 ml-1">
                    <label for="form_allows_edit_frm" class="col-sm-3"><strong>Allows Edit: </strong></label> 
                    <div class="col-sm-8"> 
                        <form action="{{route('form.allowsedit', $form->id)}}" method="POST" style="display:none" id="form_allows_edit_frm">
                            @csrf
                            <input type="hidden" name="form_allows_edit" id="form_allows_edit" value="{{$form->allows_edit}}"/>
                        </form>
                        <select class="col-md-6 form-control" id="form_allows_edit_opt">
                            <option value="0" @if($form->allows_edit == 0) selected @endif>No</option>
                            <option value="1" @if($form->allows_edit == 1) selected @endif>Yes</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Visibility: </strong> <span class="float-right">{{ $visibility_typs->firstWhere('id',$form->visibility_type)->name }}</span>
                    </li>

                    @if($form->visibility_type == "3")
                        <li class="list-group-item">
                            <strong>Group: </strong> 
                            <span class="float-right">
                                @foreach(explode(',', $form->visibility_to) as $group)
                                    <span class="p-1 mr-1 mb-1 bg-light text-white rounded">
                                        {{ $groups->firstWhere('id',$group)->name }}
                                    </span>
                                @endforeach
                            </span>
                        </li>
                    @elseif($form->visibility_type == "4")
                        <li class="list-group-item">
                            <strong>Department: </strong>
                            <span class="float-right">
                                @foreach(explode(',', $form->visibility_to) as $dep)
                                    <span class="p-1 mr-1 mb-1 bg-light text-white rounded">
                                        {{ $deps->firstWhere('id',$dep)->name }}
                                    </span>
                                @endforeach
                            </span>
                        </li>
                    @endif
                    
                    <li class="list-group-item">
                        <strong>Owner: </strong> <span class="float-right">{{ $form->user->name }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Current Submissions: </strong> 
                        <span class="float-right">{{ $form->submissions_count }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Last Updated On: </strong> 
                        <span class="float-right">
                            {{ $form->updated_at->toDayDateTimeString() }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>Created On: </strong> 
                        <span class="float-right">
                            {{ $form->created_at->toDayDateTimeString() }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']) || in_array(Auth::user()->id ,$form_admins )))
        <div class="btn-toolbar float-md-left" role="toolbar">
            <div class="btn-group" role="group">
                <a href="javascript:history.back(){{-- route('forms.index') --}}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('forms.preview', $form->id) }}" class="btn btn-info float-md-right btn-sm">
                    <i class="fa fa-eye"></i> Preview
                </a>
                <a href="{{ route('forms.edit', $form->id) }}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-edit"></i> Edit form
                </a>
                <a href="{{route('submitted.index', $form->id)}}" class="btn btn-primary float-md-right btn-sm">
                    <i class="fa fa-th-list"></i> Submissions
                </a>
            </div>
        </div>
        <form action="{{route('forms.destroy', $form->id)}}" method="POST"  class="delete_form d-inline-block float-right">
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
	<script src="{{ asset('plugins/select2/js/select2.min.js') }}" ></script>
    <script src="{{ asset('js/pristinejs/pristine.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify.min.js') }}" ></script>
    <script src="{{ asset('js/alertifyjs/alertify_custom.js') }}" ></script>
    <script src="{{ asset('js/clipboard/clipboard.min.js') }}" ></script>
	<script src="{{ asset('js/formbuilder/form-builder.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form-render.min.js') }}" ></script>
    <script src="{{ asset('js/formbuilder/form.js') }}" ></script>
    <script type="text/javascript">
        $(".form-managers-select").select2({
            width: 'resolve' // need to override the changed default
        });

        if(Clipboard && ClipboardJS.isSupported()){
            
            var clip = new ClipboardJS('.clipboard')

            clip.on('success', function( e ) {
                var ref = $( e.trigger )
                ref.removeClass("btn-primary");
                ref.addClass("btn-success");
                ref.html('<i class="fa fa-check-circle"></i>')
                /*
                setTimeout(function() {
                    ref.html('<i class="fa fa-clipboard"></i>')
                }, 1200);
                */
            });
        }else{
            var clip_btn = $(".clipboard")
            clip_btn.removeClass("btn-primary");
            clip_btn.addClass("btn-secondary");
            clip_btn.attr("disabled",true);
            clip_btn.attr("title", "Clipboard not available!");

        }

    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/alertify.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/alertifyjs/css/themes/default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/pristinejs.css') }}" />
@endsection