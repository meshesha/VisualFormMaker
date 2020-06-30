@extends('layouts.app')

@section('content')

    <div class="card rounded-0">
        @if($forms && count($forms) > 0)
            <div class="table-responsive">
                <table class="table table-bordered d-table table-striped pb-0 mb-0">
                    <thead>
                        <tr>
                            <th class="five">#</th>
                            <th>Name</th>
                            <th class="ten">Type</th>
                            <th class="ten">Status</th>
                            <th class="ten">Visibility</th>
                            <th class="ten">VisibileTo</th>
                            <th class="fifteen">Allows Edit?</th>
                            <th class="ten">Submissions</th>
                            <th class="twenty-five">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $form->name }}</td>
                                <td>{{ $form_types->firstWhere('id',$form->type)->name }}</td>
                                <td>{{ $form_statuses->firstWhere('id',$form->status)->name }}</td>
                                <td>{{ $visibility_typs->firstWhere('id',$form->visibility_type)->name}}</td>
                                <td>
                                    @if ($form->visibility_type == "3")
                                        @foreach(explode(',', $form->visibility_to) as $group)
                                            <span class="p-1 mr-1 mb-1 bg-light text-white rounded">
                                                {{ $groups->firstWhere('id',$group)->name }}
                                            </span>
                                        @endforeach
                                    @elseif($form->visibility_type == "4")
                                        @foreach(explode(',', $form->visibility_to) as $dep)
                                            <span class="p-1 mr-1 mb-1 bg-light text-white rounded">
                                                {{ $deps->firstWhere('id',$dep)->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        {{ $visibility_typs->firstWhere('id',$form->visibility_type)->name}}
                                    @endif
                                </td>
                                <td>{{ $form->allows_edit ? 'YES' : 'NO' }}</td>
                                <td>{{ $form->submitted()->count() }}</td>
                                <td>
                                    <a href="{{route('submitted.index', $form->id)}}" class="btn btn-primary btn-sm" title="View submissions for form '{{ $form->name }}'">
                                        <i class="fa fa-th-list"></i> Data
                                    </a>
                                    <a href="{{ route('forms.show',$form->id) }}" class="btn btn-info btn-sm" title="Details">
                                        <i class="fa fa-info-circle"></i> Details
                                    </a> 
                                    <!--
                                    <a href="#" class="btn btn-primary btn-sm" title="Edit form">
                                        <i class="fa fa-pencil-alt"></i> 
                                    </a>
                                        
                                    <button class="btn btn-primary btn-sm clipboard" data-clipboard-text="" data-message="" data-original="" title="Copy form URL to clipboard">
                                        <i class="fa fa-clipboard"></i> 
                                    </button> 
                                        
                                    <form action="{{ route('forms.destroy', $form) }}" method="POST" id="deleteFormForm_{{ $form->id }}" class="d-inline-block">
                                        @csrf 
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm confirm-form" data-form="deleteFormForm_{{ $form->id }}" data-message="Delete form '{{ $form->name }}'?" title="Delete form '{{ $form->name }}'">
                                            <i class="fa fa-trash"></i> 
                                        </button>
                                    </form>
                                    -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($forms->hasPages())
                <div class="card-footer mb-0 pb-0">
                    <div>{{ $forms->links() }}</div>
                </div>
            @endif
        @else
            <div class="card-body">
                <h4 class="text-danger text-center">
                    No form to display.
                </h4>
            </div>  
        @endif
    </div>
@endsection

@section('action_buttons')
    
    <div class="btn-toolbar float-md-right" role="toolbar">
        <div class="btn-group" role="group" aria-label="Third group">
            @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
                <a href="{{ route('forms.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus-circle"></i> Create a New Form
                </a>
            @endif
            <a href="{{ route('submitted.user.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-th-list"></i> My Submissions
            </a>
        </div>
    </div>
@endsection