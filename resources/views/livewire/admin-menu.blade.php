        <div>
        <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                <!-- User Dropdown Menu -->
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    @if (Auth::user()->profile_photo_path != null)
                    <img src="{{ Storage::url('public/profile-photos/'.Auth::user()->profile_photo_path) }}" class="user-image img-circle elevation-2" alt="{{ Auth::user()->name }}">
                    @else
                    <img src="{{ Storage::url('images/logo.png') }}" class="user-image img-circle elevation-2" alt="{{ Auth::user()->name }}">
                    @endif
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        @if (Auth::user()->profile_photo_path != null)
                        <img src="{{ Storage::url('public/profile-photos/'.Auth::user()->profile_photo_path) }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
                        @else
                        <img src="{{ Storage::url('images/logo.png') }}" class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
                        @endif
                        <p>
                        {{ Auth::user()->name }} - Web Developer
                        <small>Member since {{ Auth::user()->created_at }}</small>
                        </p>
                    </li>
                    <!-- Menu Body -->
                    <li class="user-body">
                        <div class="row">
                        <div class="col-4 text-center">
                            <a href="/" target="_blank">Home</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Sales</a>
                        </div>
                        <div class="col-4 text-center">
                            <a href="#">Friends</a>
                        </div>
                        </div>
                        <!-- /.row -->
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <a href="{{ route('profile.show') }}" class="btn btn-default btn-flat">Profile</a>
                            <button type="submit" class="btn btn-default btn-flat float-right">
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </li>
                    </ul>
                </li>
                <!-- Language Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="flag-icon flag-icon-us"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-0">
                    <a href="#" class="dropdown-item active">
                        <i class="flag-icon flag-icon-us mr-2"></i> English
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="flag-icon flag-icon-de mr-2"></i> German
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="flag-icon flag-icon-fr mr-2"></i> French
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="flag-icon flag-icon-es mr-2"></i> Spanish
                    </a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                    </a>
                </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="dashboard" class="brand-link">
                <img src="{{ Storage::url('images/logo.png') }}" alt="{{ config('app.name') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">

                    <!-- SidebarSearch Form -->
                    <div class="form-inline">
                        <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                            <li class="nav-header">GENERAL</li>
                            <li class="nav-item">
                                <a href="/dashboard" class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/pages" class="nav-link {{ (request()->is('pages')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    Pages
                                    <span class="badge badge-info right">6</span>
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/posts" class="nav-link {{ (request()->is('dashboard')) ? 'posts' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>
                                    Posts
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/categories" class="nav-link {{ (request()->is('categories')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>
                                    Categories
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/gallerys" class="nav-link {{ (request()->is('gallerys')) ? 'active' : '' }}">
                                <i class="nav-icon fas fa-images"></i>
                                <p>
                                    Gallery
                                </p>
                                </a>
                            </li>
                            <li class="nav-header">{{ __('general.appearance') }}</li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-desktop"></i>
                                <p>
                                    {{ __('general.themes') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menumanager.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-bars"></i>
                                <p>
                                    {{ __('general.menu_manager') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('settings.group') }}" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    {{ __('general.settings') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-header">{{ __('general.component') }}</li>
                            <li class="nav-item {{ (request()->is('subscriber/all')||request()->is('subscriber/compose')) ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ (request()->is('subscriber/all')||request()->is('subscriber/compose')) ? 'active' : '' }}">
                                <i class="nav-icon far fa-envelope"></i>
                                <p>
                                    Subscriber
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                                </a>
                                <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="/subscriber/all" class="nav-link {{ (request()->is('subscriber/all')) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>All Subscriber</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/subscriber/compose" class="nav-link {{ (request()->is('subscriber/compose')) ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Compose</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="mailbox/read-mail.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Read</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('components.index') }}" class="nav-link">
                                <i class="nav-icon far fa-folder"></i>
                                <p>
                                    {{ __('general.component') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-header">{{ __('general.user') }}</li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    {{ __('general.users') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>
                                    {{ __('general.roles') }}
                                </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('permissions.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>
                                    {{ __('general.permissions') }}
                                </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
        </div>