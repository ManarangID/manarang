<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="{{ getSetting('web_author') }}" />
        <link rel="icon" href="{{ Storage::url('images/favicon.png') }}">
        
        <title>{{ config('app.name', 'Laravel') }}</title>
        {!! SEO::generate() !!}
        <!-- Stylesheets
        ============================================= -->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas/css')}}/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas')}}/style.css" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas/css')}}/dark.css" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas/css')}}/font-icons.css" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas/css')}}/animate.css" type="text/css" />
        <link rel="stylesheet" href="{{asset('canvas/css')}}/magnific-popup.css" type="text/css" />
		<link rel="stylesheet" href="{{asset('canvas/css')}}/swiper.css" type="text/css" />
		 <link rel="stylesheet" href="{{asset('canvas/css')}}/custom.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {!! RecaptchaV3::initJs() !!}
        <!-- Styles -->
        @livewireStyles
		<script>
		window.Laravel = <?php echo json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
		</script>

		{!! NoCaptcha::renderJs() !!}

		@if(getSetting('google_analytics_id') != '')
			<script type="text/javascript">
				var _gaq = _gaq || [];
				_gaq.push(['_setAccount', "{{ getSetting('google_analytics_id') }}"]);
				_gaq.push(['_trackPageview']);
				(function() {
					var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
					ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				})();
			</script>
		@endif
    </head>
    <body class="sticky-footer stretched">

        <!-- Document Wrapper
        ============================================= -->
        <div id="wrapper" class="clearfix">

        <!-- Top Bar & Header
        ============================================= -->
        @include(getTheme('widgets.header'))
        <!-- #top bar & header end -->
        @include(getTheme('widgets.flash-message'))
        {{ $slot }}


		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">

			<!-- Copyrights
			============================================= -->
			<div id="copyrights">
				<div class="container">

					<div class="row justify-content-between col-mb-30">
						<div class="col-12 col-lg-auto text-center text-lg-start order-last order-lg-first">
							<img src="{{ Storage::url('images/logo-footer.png') }}" height="23" alt="Image" class="mb-4"><br>
							Copyrights &copy; {{ date('Y') }} {{ getSetting('web_author') }}. All Rights Reserved.
						</div>

						<div class="col-12 col-lg-auto text-center text-lg-end">
							<div class="copyrights-menu copyright-links">
								<a href="/">Home</a>/<a href="/pages/tentang-kami">About</a>/<a href="#">Features</a>/<a href="#">Portfolio</a>/<a href="#">FAQs</a>/<a href="/contact">Contact</a>
							</div>
							<a href="{{ getSetting('facebook') }}" class="social-icon inline-block si-small si-borderless mb-0 si-facebook">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>

							<a href="{{ getSetting('twitter') }}" class="social-icon inline-block si-small si-borderless mb-0 si-twitter">
								<i class="icon-twitter"></i>
								<i class="icon-twitter"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-gplus">
								<i class="icon-gplus"></i>
								<i class="icon-gplus"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-pinterest">
								<i class="icon-pinterest"></i>
								<i class="icon-pinterest"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-vimeo">
								<i class="icon-vimeo"></i>
								<i class="icon-vimeo"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-github">
								<i class="icon-github"></i>
								<i class="icon-github"></i>
							</a>

							<a href="{{ getSetting('youtube') }}" class="social-icon inline-block si-small si-borderless mb-0 si-yahoo">
								<i class="icon-yahoo"></i>
								<i class="icon-yahoo"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless mb-0 si-linkedin">
								<i class="icon-linkedin"></i>
								<i class="icon-linkedin"></i>
							</a>
						</div>
					</div>

				</div>
			</div><!-- #copyrights end -->

		</footer><!-- #footer end -->

        </div><!-- #wrapper end -->

        <!-- Go To Top
        ============================================= -->
        <div id="gotoTop" class="icon-angle-up"></div>

        <!-- JavaScripts
        ============================================= -->
        <script src="{{asset('canvas/js')}}/jquery.js"></script>
        <script src="{{asset('canvas/js')}}/plugins.min.js"></script>

        <!-- Footer Scripts
        ============================================= -->
        <script src="{{asset('canvas/js')}}/functions.js"></script>
		<script src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}"></script>
		<script src="https://www.google.com/recaptcha/api.js?render={{ env('GOOGLE_RECAPTCHA_KEY') }}"></script>
    </body>
</html>
