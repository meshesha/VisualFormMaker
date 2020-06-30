@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container-fluid justify-content-center">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            @if($dashboard_settings["forms"] == "1")
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>Forms</h3>
                            <!--<p>10</p>-->
                        </div>
                        <div class="icon">
                            <i class="fab fa-wpforms"></i>
                        </div>
                        <a href="{{ route('forms.index') }}" class="small-box-footer">
                            Link <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
            <!-- ./col -->
            @if($dashboard_settings["departments"] == "1" && (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']))) )
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Departments</h3>
                            <!--<p>20</p>-->
                        </div>
                        <div class="icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <a href="{{ route('org.index')}}" class="small-box-footer">
                            Link <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
            <!-- ./col -->
            @if($dashboard_settings["users"] == "1" && (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']))) )
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Users</h3>
                            <!--<p>200</p>-->
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                            Link <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
            <!-- ./col -->
            @if($dashboard_settings["groups"] == "1" && (Auth::check() && (Auth::user()->hasAnyRoles(['Administrator','Manager']))) )
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>Groups</h3>
                            <!--<p>20</p>-->
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('groups.index')}}" class="small-box-footer">
                            Link <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <div class="row">
            @if($dashboard_settings["submitted"] == "1")
                <div class="col-lg-3 col-6">
                    <!-- small card -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h2>My Submissions</h2>
                            <!--<p>100</p>-->
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <a href="{{route('submitted.user.index')}}" class="small-box-footer">
                            Link <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content_header')
    @include("inc.sidebar")
@endsection