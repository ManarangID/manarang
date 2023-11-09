<!-- Header
	============================================= -->
	<header id="header" class="full-header transparent-header" data-sticky-class="not-dark">
		<div id="header-wrap">
			<div class="container">
				<div class="header-row">
					<!-- Logo
					============================================= -->
					<div id="logo">
					<a href="index.html" class="standard-logo" data-dark-logo="{{ Storage::url('images/'.getSetting('logo')) }}"><img src="{{ Storage::url('images/'.getSetting('logo')) }}" width="126" alt="{{ getSetting('web_name') }}"></a>
						<a href="index.html" class="retina-logo" data-dark-logo="{{ Storage::url('images/logo.png') }}"><img src="{{ Storage::url('images/logo.png') }}" alt="{{ getSetting('web_url') }}"></a>
					</div><!-- #logo end -->
					<div class="header-misc">
						<!-- Top Search
						============================================= -->
						<div id="top-search" class="header-misc-icon">
							<a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i class="icon-line-cross"></i></a>
						</div><!-- #top-search end -->
						<!-- Top Cart
						============================================= -->
						@if (Route::has('login'))
						@auth
						<div class="dropdown mx-3 me-lg-0">
							<a href="#" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="icon-user"></i></a>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu1">
								<a class="dropdown-item text-start" href="#">Profile</a>
								<a class="dropdown-item text-start" href="#">Messages <span class="badge rounded-pill bg-secondary float-end" style="margin-top: 3px;">5</span></a>
								<a class="dropdown-item text-start" href="#">Settings</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item text-start" href="#">Logout <i class="icon-signout"></i></a>
							</ul>
						</div>
						@else
						<div id="top-cart" class="header-misc-icon d-none d-sm-block">
							<a href="/login" ><i class="icon-signin"></i></a>
						</div><!-- #top-cart end -->
						@endauth
						@endif
					</div>
					<div id="primary-menu-trigger">
						<svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
					</div>
					<!-- Primary Navigation
					============================================= -->
					<nav class="primary-menu style-5">
                        <ul class="menu-container">
                            @each(getTheme('widgets.menu'), getMenus(), 'menu', getTheme('widgets.menu'))
                        </ul>
					</nav><!-- #primary-menu end -->
					<form class="top-search-form" action="search.html" method="get">
						<input type="text" name="q" class="form-control" value="" placeholder="Type &amp; Hit Enter.." autocomplete="off">
					</form>
				</div>
			</div>
		</div>
		<div class="header-wrap-clone"></div>
	</header><!-- #header end -->