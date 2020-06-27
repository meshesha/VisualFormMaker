<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-primary navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      @guest
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ url('/') }}" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ route('about') }}" class="nav-link">About</a>
        </li>
      @else
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{ route('about') }}" class="nav-link ">About</a>
        </li>
      @endguest
    </ul>

    <!-- SEARCH FORM -->
 

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Authentication Links -->
      @guest
          <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          @if (Route::has('register') && config('global.users.allows_registration') == "1")
              <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
              </li>
          @endif
      @else
          <div class="user-panel d-flex">
            <div class="image">
              <img 
                @if (Auth::user()->image != "" && Auth::user()->image !== null)
                    src="{{asset('storage/users')}}/{{Auth::user()->image}}"
                @else
                    src="{{asset('storage/users')}}/user.png"
                @endif
                class="img-circle elevation-2" alt="User Image">
            </div>
          </div>
          <li class="nav-item dropdown">
              <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                  {{ Auth::user()->name }} <span class="caret"></span>
              </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    Profile
                    </a>
                  <hr>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                      {{ __('Logout') }}
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
              </div>
          </li>
      @endguest
    </ul>
  </nav>