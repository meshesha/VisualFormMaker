@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="my_org_tree"></div>
        </div>
        <script type="text/javascript">
            var chart_config = {!! $chart_config !!};
            //console.log(chart_config)
            new Treant( chart_config );
        </script>
    </div>
</div>
@endsection

@section('action_buttons')
    @if (Auth::check() && Auth::user()->hasAnyRoles(['Administrator','Manager']))
    
        <a href="{{ route('org.index') }}" class="btn btn-sm btn-primary ">
            <i class="fas fa-table"></i> Table View
        </a>
        <!--
        <a href="{{--route('org.editor') --}}" class="btn btn-sm btn-primary ">
            <i class="fas fa-table"></i> Org Editor
        </a>
        -->
        <a href="{{ route('org.create') }}" class="btn btn-sm btn-primary float-md-right">
            <i class="fa fa-user"></i> New Department
        </a>
    @endif
@endsection

@section('scripts_head')
	<script src="{{ asset('js/treant-js/vendor/raphael.js') }}" ></script>
	<script src="{{ asset('js/treant-js/Treant.js') }}" ></script>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('js/treant-js/Treant.css') }}" />
    <style>
        
        .chart {
            height: 100%;
            margin: 5px;
            width: 100%; 
        }
        .Treant > p {
            font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            font-weight: bold;
            font-size: 12px; 
        }
        .node-name { 
            font-weight: bold;
        }

        .nodeMyOrgTree {
            padding: 2px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            background-color: #ffffff;
            border: 1px solid #000;
            width: 200px;
            font-family: Tahoma;
            font-size: 12px;
        }
        .nodeMyOrgTree img{
            border:3px solid #adb5bd;
            margin: 0 auto;
            padding: 3px;
            /*width: 100px;*/
            width: 3rem;
            max-width: 100%;
            height: auto;
            /*border-radius: 50%;circle*/
        }
        .nodeMyOrgTree p{
            margin-bottom: 0;
        }
    </style>
@endsection
