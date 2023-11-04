<x-app-layout>

	<!-- Slider
	============================================= -->
	<section id="slider" class="slider-element slider-parallax swiper_wrapper min-vh-60 min-vh-md-100 include-header" data-autoplay="7000" data-speed="650" data-loop="true">
		<div class="slider-inner">

				<div class="swiper-container swiper-parent">
					<div class="swiper-wrapper">
					@foreach(latestSlider(7) as $latestSliders)
						<div class="swiper-slide">
							<div class="container">
								<div class="slider-caption slider-caption-center">
									<h2 data-animate="fadeInUp">{{ $latestSliders->title }}</h2>
									<p class="d-none d-sm-block" data-animate="fadeInUp" data-delay="200">Create just what you need for your Perfect Website. Choose from a wide range of Elements &amp; simply put them on our Canvas.</p>
								</div>
							</div>
							<div class="swiper-slide-bg" style="background-image: url({{ Storage::url('gallery/'.$latestSliders->picture) }});"></div>
						</div>
					@endforeach
					</div>
					<div class="slider-arrow-left"><i class="icon-angle-left"></i></div>
					<div class="slider-arrow-right"><i class="icon-angle-right"></i></div>
					<div class="slide-number"><div class="slide-number-current"></div><span>/</span><div class="slide-number-total"></div></div>
					<div class="swiper-pagination"></div>
				</div>

		</div>
	</section>

    <!-- Content
    ============================================= -->
    <section id="content">
			<div class="content-wrap">
				<div class="container clearfix">

					<div class="mx-auto center clearfix" style="max-width: 900px;">
						<img class="bottommargin" src="{{ Storage::url('images/'.getSetting('logo')) }}" width="147" alt="Image">
						<h1>{{ getSetting('web_name') }} - <span>{{ getSetting('web_author') }}</span></h1>
						<h2>{{ getSetting('web_description') }}</h2>
						<a href="/contact" class="button button-3d button-dark button-large ">Contact us</a>
					</div>

				</div>

				<div class="clear"></div>

				<div class="section parallax dark mb-0 border-bottom-0" style="background-image: url({{ Storage::url('images/parallax.jpg') }});" data-bottom-top="background-position:0px 0px;" data-top-bottom="background-position:0px -300px;">

					<div class="container clearfix">

						<div class="heading-block center">
							<h2>{{ \Str::limit(strip_tags(getPages(1)->title), 200) }}</h2>
							<span>{{ \Str::limit(strip_tags(getPages(1)->content), 200) }}</span>
						</div>

						<div style="position: relative; margin-bottom: -60px;" data-height-xl="415" data-height-lg="342" data-height-md="262" data-height-sm="160" data-height-xs="102">
							<img src="{{ Storage::url('images/services1.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" alt="Chrome">
							<img src="{{ Storage::url('images/services2.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="300" alt="iPad">
						</div>

					</div>

				</div>

				<div class="row align-items-stretch">

					<div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #1abc9c;">
						<div>
							<h3 class="text-uppercase" style="font-weight: 600;">Why choose Us</h3>
							<p style="line-height: 1.8;">Transform, agency working families thinkers who make change happen communities. Developing nations legal aid public sector our ambitions future aid The Elders economic security Rosa.</p>
							<a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
							<i class="icon-bulb bgicon"></i>
						</div>
					</div>

					<div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #34495e;">
						<div>
							<h3 class="text-uppercase" style="font-weight: 600;">Our Mission</h3>
							<p style="line-height: 1.8;">Frontline respond, visionary collaborative cities advancement overcome injustice, UNHCR public-private partnerships cause. Giving, country educate rights-based approach; leverage disrupt solution.</p>
							<a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
							<i class="icon-cog bgicon"></i>
						</div>
					</div>

					<div class="col-lg-4 dark col-padding overflow-hidden" style="background-color: #e74c3c;">
						<div>
							<h3 class="text-uppercase" style="font-weight: 600;">What you get</h3>
							<p style="line-height: 1.8;">Sustainability involvement fundraising campaign connect carbon rights, collaborative cities convener truth. Synthesize change lives treatment fluctuation participatory monitoring underprivileged equal.</p>
							<a href="#" class="button button-border button-light button-rounded text-uppercase m-0">Read More</a>
							<i class="icon-thumbs-up bgicon"></i>
						</div>
					</div>

					<div class="clear"></div>

				</div>

				<!-- <div class="section border-top-0 topmargin-sm bottommargin-sm border-0 bg-transparent">
					<div class="container clearfix">

						<div class="row col-mb-50">
							<div class="col-sm-6 col-lg-3 text-center" data-animate="bounceIn">
								<i class="i-plain i-xlarge mx-auto mb-0 icon-code"></i>
								<div class="counter counter-lined"><span data-from="100" data-to="846" data-refresh-interval="50" data-speed="2000"></span>K+</div>
								<h5>Lines of Codes</h5>
							</div>

							<div class="col-sm-6 col-lg-3 text-center" data-animate="bounceIn" data-delay="200">
								<i class="i-plain i-xlarge mx-auto mb-0 icon-magic"></i>
								<div class="counter counter-lined"><span data-from="3000" data-to="15360" data-refresh-interval="100" data-speed="2500"></span>+</div>
								<h5>KBs of HTML Files</h5>
							</div>

							<div class="col-sm-6 col-lg-3 text-center" data-animate="bounceIn" data-delay="400">
								<i class="i-plain i-xlarge mx-auto mb-0 icon-file-text"></i>
								<div class="counter counter-lined"><span data-from="10" data-to="386" data-refresh-interval="25" data-speed="3500"></span>*</div>
								<h5>No. of Templates</h5>
							</div>

							<div class="col-sm-6 col-lg-3 text-center" data-animate="bounceIn" data-delay="600">
								<i class="i-plain i-xlarge mx-auto mb-0 icon-time"></i>
								<div class="counter counter-lined"><span data-from="60" data-to="1200" data-refresh-interval="30" data-speed="2700"></span>+</div>
								<h5>Hours of Coding</h5>
							</div>
						</div>

					</div>
				</div> -->

				<div class="section mt-0 border-0 mb-0" >
					<div class="container clearfix">

						<div class="heading-block center mb-0">
							<h2>Our Awesome Works</h2>
							<span>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, earum!</span>
						</div>

					</div>
				</div>

				<!-- Portfolio Items
				============================================= -->
				<div id="portfolio" class="portfolio row bottommargin-lg g-0 portfolio-reveal grid-container">
				@foreach(latestGallery(8) as $latestGallery)
					<article class="portfolio-item col-6 col-md-4 col-lg-3 pf-media pf-icons">
						<div class="grid-inner">
							<div class="portfolio-image">
								<a href="{{ url('album/'.$latestGallery->aseotitle) }}">
									<img src="{{ getPicture($latestGallery->picture, 'thumb', $latestGallery->updated_by) }}" alt="Open Imagination">
								</a>
								<div class="bg-overlay">
									<div class="bg-overlay-content dark" data-hover-animate="fadeIn" data-hover-parent=".portfolio-item">
										<a href="{{ getPicture($latestGallery->picture, 'original', $latestGallery->updated_by) }}" class="overlay-trigger-icon bg-light text-dark" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350" data-hover-parent=".portfolio-item" data-lightbox="image" title="Image"><i class="icon-line-plus"></i></a>
									</div>
									<div class="bg-overlay-bg dark" data-hover-animate="fadeIn" data-hover-parent=".portfolio-item"></div>
								</div>
							</div>
							<div class="portfolio-desc">
								<h3>{{ $latestGallery->title }}</h3>
								<span><a href="{{ url('album/'.$latestGallery->aseotitle) }}">{{ $latestGallery->atitle }}</a></span>
							</div>
						</div>
					</article>
				@endforeach
				</div>

				<!-- <div class="container clearfix">

					<div class="row align-items-center gutter-40 col-mb-50">
						<div class="col-md-5">
							<img data-animate="fadeInLeftBig" src="images/services/imac.png" alt="Imac">
						</div>

						<div class="col-md-7">
							<div class="heading-block">
								<h2>Retina Device Ready.</h2>
								<span>Fabulously Sharp &amp; Intuitive on your HD Devices.</span>
							</div>

							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus deserunt, nobis quae eos provident quidem. Quaerat expedita dignissimos perferendis, nihil quo distinctio eius architecto reprehenderit maiores.</p>

							<a href="#" class="button button-border button-rounded button-large ms-0 topmargin-sm">Experience More</a>
						</div>
					</div>

					<div class="line"></div>

				</div> -->

				<!-- <div class="container mx-auto clearfix">

					<div class="heading-block center">
						<h2>Canvas: We know you want it!</h2>
						<span>Built with passion &amp; intuitiveness in mind. Canvas is a masterclass piece of work presented to you.</span>
					</div>

					<div style="position: relative;" data-height-xl="624" data-height-lg="518" data-height-md="397" data-height-sm="242" data-height-xs="154">
						<img src="images/services/fbrowser.png" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="100" alt="Chrome">
						<img src="images/services/fmobile.png" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="400" alt="iPad">
						<img src="images/services/fbrowser2.png" style="position: absolute; top: 0; left: 0;" data-animate="fadeIn" data-delay="1200" alt="iPad">
					</div>
				</div>

				<div class="section dark my-0 border-0" style="height: 500px; padding: 145px 0;">

					<div class="container clearfix">

						<div class="slider-caption slider-caption-center" style="position: relative;">
							<div data-animate="fadeInUp">
								<h2 style="font-size: 42px;">Beautiful HTML5 Videos</h2>
								<p class="d-none d-sm-block">Looks beautiful &amp; ultra-sharp on Retina Screen Displays. Powerful Layout with Responsive functionality that can be adapted to any screen size.</p>
								<a href="#" class="button button-border button-rounded button-white button-light button-large ms-0 mb-0 d-none d-md-inline-block" style="margin-top: 20px;">Show More</a>
							</div>
						</div>

					</div>

					<div class="video-wrap">
						<video poster="images/videos/explore-poster.jpg" preload="none" loop autoplay muted>
							<source src='images/videos/explore.mp4' type='video/mp4' />
							<source src='images/videos/explore.webm' type='video/webm' />
						</video>
						<div class="video-overlay" style="background-color: rgba(0,0,0,0.3);"></div>
					</div>

				</div> -->

				<div class="container clearfix">

					<div class="row col-mb-50">
						<div class="col-lg-8">
							<div class="fancy-title title-border">
								<h4>Recent Posts</h4>
							</div>

							<div class="row posts-md col-mb-30">
							@foreach(latestPost(1,0) as $latestPost1)
								<div class="entry col-md-6">
									<div class="grid-inner">
										<div class="entry-image">
											<a href="{{ prettyUrl($latestPost1) }}"><img src="{{ getPicturepost($latestPost1->picture, '', $latestPost1->updated_by) }}" alt="{{ $latestPost1->title }}"></a>
										</div>
										<div class="entry-title title-sm nott">
											<h3><a href="{{ prettyUrl($latestPost1) }}">{{ $latestPost1->title }}</a></h3>
										</div>
										<div class="entry-meta">
											<ul>
												<li><i class="icon-calendar3"></i> {{ date('d F Y' , strtotime($latestPost1->created_at)) }}</li>
												<li><a href="{{ prettyUrl($latestPost1) }}#comments"><i class="icon-comments"></i> 13</a></li>
												<li><a href="{{ prettyUrl($latestPost1) }}"><i class="icon-eyes"></i> {{ $latestPost1->hits }} Views</a></li>
											</ul>
										</div>
										<div class="entry-content">
											<p>{{ \Str::limit(strip_tags($latestPost1->content), 150) }}</p>
										</div>
									</div>
								</div>
							@endforeach

								
							@foreach(latestPost(1,1) as $latestPost2)
								<div class="entry col-md-6">
									<div class="grid-inner">
										<div class="entry-image">
											<a href="{{ prettyUrl($latestPost2) }}"><img src="{{ getPicturepost($latestPost2->picture, '', $latestPost2->updated_by) }}" alt="{{ $latestPost2->title }}"></a>
										</div>
										<div class="entry-title title-sm nott">
											<h3><a href="{{ prettyUrl($latestPost2) }}">{{ $latestPost2->title }}</a></h3>
										</div>
										<div class="entry-meta">
											<ul>
												<li><i class="icon-calendar3"></i> {{ date('d F Y' , strtotime($latestPost2->created_at)) }}</li>
												<li><a href="{{ prettyUrl($latestPost2) }}#comments"><i class="icon-comments"></i> 13</a></li>
												<li><a href="{{ prettyUrl($latestPost2) }}"><i class="icon-eyes"></i> {{ $latestPost2->hits }} Views</a></li>
											</ul>
										</div>
										<div class="entry-content">
											<p>{{ \Str::limit(strip_tags($latestPost2->content), 150) }}</p>
										</div>
									</div>
								</div>
							@endforeach
							</div>
						</div>

						<div class="col-lg-4">
							<div class="fancy-title title-border">
								<h4>Client Testimonials</h4>
							</div>

							<div class="fslider testimonial p-0 border-0 shadow-none" data-animation="slide" data-arrows="false">
								<div class="flexslider">
									<div class="slider-wrap">
										<div class="slide">
											<div class="testi-image">
												<a href="#"><img src="images/testimonials/3.jpg" alt="Customer Testimonails"></a>
											</div>
											<div class="testi-content">
												<p>Similique fugit repellendus expedita excepturi iure perferendis provident quia eaque. Repellendus, vero numquam?</p>
												<div class="testi-meta">
													Steve Jobs
													<span>Apple Inc.</span>
												</div>
											</div>
										</div>
										<div class="slide">
											<div class="testi-image">
												<a href="#"><img src="images/testimonials/2.jpg" alt="Customer Testimonails"></a>
											</div>
											<div class="testi-content">
												<p>Natus voluptatum enim quod necessitatibus quis expedita harum provident eos obcaecati id culpa corporis molestias.</p>
												<div class="testi-meta">
													Collis Ta'eed
													<span>Envato Inc.</span>
												</div>
											</div>
										</div>
										<div class="slide">
											<div class="testi-image">
												<a href="#"><img src="images/testimonials/1.jpg" alt="Customer Testimonails"></a>
											</div>
											<div class="testi-content">
												<p>Incidunt deleniti blanditiis quas aperiam recusandae consequatur ullam quibusdam cum libero illo rerum!</p>
												<div class="testi-meta">
													John Doe
													<span>XYZ Inc.</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card topmargin overflow-hidden">
								<div class="card-body">
									<h4>Opening Hours</h4>

									<p>Hubungi kami di jam operasional berikut</p>

									<ul class="iconlist mb-0">
										<li><i class="icon-time color"></i> <strong>Mondays-Fridays:</strong> 9AM to 4PM</li>
										<li><i class="icon-time color"></i> <strong>Saturdays:</strong> 10AM to 3PM</li>
										<li><i class="icon-time text-danger"></i> <strong>Sundays:</strong> Closed</li>
									</ul>

									<i class="icon-time bgicon"></i>
								</div>
							</div>
						</div>
					</div>

				</div>

				<div class="section mb-0">
					<div class="container clearfix">

						<div class="heading-block center">
							<h3>Subscribe to our <span>Newsletter</span></h3>
						</div>

						<div class="">
							
							<form action="{{ route('subscribe') }}" method="post" class="mb-0">
								@csrf
								@if (session('subscribed'))
								<div class="alert alert-success">
									{{ session('subscribed') }}
								</div>
								@endif
								<div class="input-group input-group-lg mx-auto" style="max-width:600px;">
									<div class="input-group-text"><i class="icon-email2"></i></div>
									<input type="email" name="email" class="form-control required email" placeholder="Enter your Email">
									<button class="btn btn-secondary" type="submit">Subscribe Now</button>
								</div>
							</form>
						</div>

					</div>
				</div>

				<div id="oc-clients" class="section bg-transparent mt-0 owl-carousel owl-carousel-full image-carousel footer-stick carousel-widget" data-margin="80" data-loop="true" data-nav="false" data-autoplay="5000" data-pagi="false" data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="5" data-items-xl="6">

					<div class="oc-item"><a href="#"><img src="images/clients/1.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/2.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/3.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/4.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/5.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/6.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/7.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/8.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/9.png" alt="Clients"></a></div>
					<div class="oc-item"><a href="#"><img src="images/clients/10.png" alt="Clients"></a></div>

				</div>


			</div>
    </section><!-- #content end -->

</x-app-layout>