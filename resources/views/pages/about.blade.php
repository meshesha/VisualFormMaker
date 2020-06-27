@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4 text-center">About</h1>
        <br>
        <div class="row">
            <label for="description" class="col-md-2  text-md-right">Description</label>
            <div class="col-md-6">
                <p class="lead">Visual Form Maker is web application that allowing you to build and manage simple html forms using simple drag-and-drop action.</p>
            </div>
        </div>
        <div class="row">
            <label for="license" class="col-md-2  text-md-right">License</label>
            <div class="col-md-6">
                <p><a href="https://github.com/meshesha/VisualFormMaker/blob/master/LICENSE" target="_blank" role="button">MIT</a></p>
            </div>
        </div>
        <div class="row">
            <label for="version" class="col-md-2  text-md-right">Version</label>
            <div class="col-md-6">
                <p>{{$app_ver}}</p>
            </div>
        </div>
        <div class="row">
            <label for="author" class="col-md-2  text-md-right">Author</label>
            <div class="col-md-6">
                <p><a href="https://github.com/meshesha/" target="_blank" role="button">Tady meshesha</a></p>
            </div>
        </div>
        <hr class="my-4">
        <a class="btn btn-primary btn-lg" href="https://github.com/meshesha/VisualFormMaker" target="_blank" role="button">Learn more</a>
    </div>
</div>
@endsection