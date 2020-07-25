
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
<!-- Brand Logo -->
<a href="{{ url('/') }}" class="brand-link">
    <img src="{{asset('storage/images')}}/logo-icon.png" alt=" Logo" class="brand-image elevation-3"
        style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name', 'VisualFormMaker') }}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional)
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="{{--asset('storage/users')--}}/user.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="{{-- route('profile.edit') --}}" class="d-block">{{-- Auth::user()->name --}} </a>
        </div>
    </div>
    -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link active">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                Dashboard
                </p>
            </a>
        </li>
        <li class="nav-item  has-treeview">
            <a href="" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>Forms</p>
                <i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
                
                <li class="nav-item">
                    <a href="{{ route('forms.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>All Forms</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('submitted.user.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>My submissions</p>
                    </a>
                </li>
            </ul>

        </li>
        @if (Auth::user()->hasAnyRoles(['Administrator','Manager']))
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i class="nav-icon fas fa-user"></i>
                <p>
                Users
                </p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('groups.index')}}" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                Groups/Roles
                </p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-sitemap"></i>
                <p>Organization tree</p>
                <i class="fas fa-angle-left right"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('org.index')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Table view</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('org.treeview')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Tree view</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if (Auth::user()->hasRole('Administrator'))
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="fas fa-cogs"></i>
                <p>
                    Settings
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('settings.app')}}" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <p>General</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.dashboard')}}" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.forms')}}" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <p>Forms</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.users')}}" class="nav-link">
                        <i class="fas fa-user-cog"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.groups')}}" class="nav-link">
                        <i class="fas fa-users-cog"></i>
                        <p>Groups/Roles</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.departments')}}" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <p>Organization</p>
                    </a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
@section('scripts_bottom')
    <script type="text/javascript">
    jQuery(function () {
        //////////fix 404 error after installation/////////////////
        var install_url = "{{route('LaravelInstaller::final')}}";
        if(install_url == window.location.href){
            window.location.href = "{{route('dashboard')}}";
        }
        ////////////////////////////////////////////////////////////////

        var protocol = window.location.protocol;
        var host = window.location.hostname;
        var pathname = window.location.pathname;
        var url =  protocol + "//" + host  + pathname;
        //console.log(url);
        url_ary = url.split("/");
        for(var j=0; j < url_ary.length; j++){
            var new_url = url_ary.join("/"); 
            //console.log("new_url: ",new_url);
            var is_navlink_found = false;
            $.each($("aside .nav-link"),function(i, a_link){
                var link_url = $(a_link).attr("href");
                if(link_url != "#"){
                    if(link_url == new_url){
                        $("aside .nav-link").removeClass("active");
                        $(a_link).addClass("active");
                        if($(a_link).parent().parent().hasClass("nav-treeview")){
                            $(a_link).parent().parent().parent().addClass("menu-open");
                        }
                        is_navlink_found = true;
                        return false;
                    }
                }
            });
            if(is_navlink_found){
                break;
            }
            url_ary.pop();
        }


    });
    </script>
@endsection