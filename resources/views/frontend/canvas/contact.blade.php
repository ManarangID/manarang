<x-app-layout>

	<!-- Content
		============================================= -->
		<section id="content">
			<div class="content-wrap">
				<div class="container clearfix">

					<div class="row align-items-stretch col-mb-50 mb-0">
						<!-- Contact Form
						============================================= -->
						<div class="col-lg-6">

							<div class="fancy-title title-border">
								<h3>Send us an Email</h3>
							</div>

							<div class="">
								
								<form class="mb-0" role="form" method="POST" action="{{ route('contact.send') }}">
                       			 {!! csrf_field() !!}

									<div class="row">
										<div class="col-md-6 form-group">
											<label for="name">Name <small>*</small></label>
											<input type="text" name="name" value="{{ old('name') }}" class="sm-form-control required" />
											@if ($errors->has('name'))
												<span class="text-danger">{{ $errors->first('name') }}</span>
											@endif
										</div>

										<div class="col-md-6 form-group">
											<label for="email">Email <small>*</small></label>
											<input type="email" name="email" value="{{ old('email') }}" class="required email sm-form-control" />
											@if ($errors->has('email'))
												<span class="text-danger">{{ $errors->first('email') }}</span>
											@endif
										</div>

										<div class="w-100"></div>

										<div class="col-md-12 form-group">
											<label for="subject">Subject <small>*</small></label>
											<input type="text" name="subject" value="{{ old('subject') }}" class="required sm-form-control" />
											@if ($errors->has('subject'))
												<span class="text-danger">{{ $errors->first('subject') }}</span>
											@endif
										</div>

										<div class="w-100"></div>

										<div class="col-12 form-group">
											<label for="message">Message <small>*</small></label>
											<textarea class="required sm-form-control"  name="message" rows="6" cols="30">{{ old('message') }}</textarea>
											@if ($errors->has('message'))
												<span class="text-danger">{{ $errors->first('message') }}</span>
											@endif
										</div>

										<div class="col-12 form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
											{!! RecaptchaV3::field('register') !!}
											@if ($errors->has('g-recaptcha-response'))
												<span class="help-block">
													<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
												</span>
											@endif
										</div>

										<div class="col-12 form-group">
											<button type="submit" class="button button-3d m-0">Submit Comment</button>
										</div>
									</div>

									<input type="hidden" name="prefix" value="template-contactform-">

								</form>
							</div>

						</div><!-- Contact Form End -->

						<!-- Google Map
						============================================= -->
						<div class="col-lg-6 min-vh-50">
							<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d248.365066480061!2d119.51672080986899!3d-5.129289487756596!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1699504595028!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div><!-- Google Map End -->
					</div>

					<!-- Contact Info
					============================================= -->
					<div class="row col-mb-50">
						<div class="col-sm-6 col-lg-3">
							<div class="feature-box fbox-center fbox-bg fbox-plain">
								<div class="fbox-icon">
									<a href="https://maps.app.goo.gl/J3YEsVPSRRdqomQbA"><i class="icon-map-marker2"></i></a>
								</div>
								<div class="fbox-content">
									<h3>Our Headquarters<span class="subtitle"></span></h3>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-lg-3">
							<div class="feature-box fbox-center fbox-bg fbox-plain">
								<div class="fbox-icon">
									<a href="tel:{{ getSetting('telephone') }}"><i class="icon-phone3"></i></a>
								</div>
								<div class="fbox-content">
									<h3>Speak to Us<span class="subtitle"></span></h3>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-lg-3">
							<div class="feature-box fbox-center fbox-bg fbox-plain">
								<div class="fbox-icon">
									<a href="mailto:{{ getSetting('email') }}"><i class="icon-email"></i></a>
								</div>
								<div class="fbox-content">
									<h3>Chat with us<span class="subtitle"></span></h3>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-lg-3">
							<div class="feature-box fbox-center fbox-bg fbox-plain">
								<div class="fbox-icon">
									<a href="{{ getSetting('twitter') }}"><i class="icon-twitter2"></i></a>
								</div>
								<div class="fbox-content">
									<h3>Follow on Twitter<span class="subtitle"></span></h3>
								</div>
							</div>
						</div>
					</div><!-- Contact Info End -->

				</div>
			</div>
		</section><!-- #content end -->

</x-app-layout>