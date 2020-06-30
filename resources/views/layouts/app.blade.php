<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}" ></script>
    
    <script src="{{ asset('js/app.js') }}" ></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

 
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('admin/css/adminlte.min.css')}}">

    @yield('styles')
    
    @yield('scripts_head')
</head>
<body class="hold-transition @guest layout-top-nav @else sidebar-mini layout-fixed @endguest">
    <div id="app" class="wrapper">
        @include('inc.navbar')
        @guest
        @else
            @include('inc.sidebar')
        @endguest
        <main class="content-wrapper py-4">
            @if(isset($title) && $title != "no-title-header")
                <div class="content-header  mb-1 bg-white border-bottom">
                    <div class="container-fluid">
                        <div class="row mb-1">
                            <div class="col-sm-6">
                                <h4 class="m-0 text-dark">{!! $title ?? '' !!}</h4>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                                @yield('action_buttons')
                                <!--
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard v1</li>
                                </ol>
                                -->
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
            @endif
            <div class="container">
                @include('inc.messages')
            </div>
            <!-- Main content -->
            <section class="content">
                @yield('content')
            </section>
            <!-- /.content -->
        </main>

          <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2020 <a href="#">{{ config('app.name') }}</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
            </div>
        </footer>
        
        @yield('scripts')
        
    <!-- AdminLTE App -->
    <script src="{{asset('admin/js/adminlte.js')}}"></script>
    @yield('scripts_bottom')
    </div>
</body>
</html>
