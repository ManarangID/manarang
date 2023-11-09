<x-app-layout>

		<!-- Content
		============================================= -->
		<section id="content">
			<div class="content-wrap">
				<div class="container clearfix">

					<div class="row gutter-40 col-mb-80">
						<!-- Post Content
						============================================= -->
						<div class="postcontent col-lg-9">
							@if($pages->picture != '')
            				<img src="{{ getPicturepages($pages->picture, null, $pages->updated_by) }}" alt="Image" class="alignleft" style="max-width: 320px;">
							@endif
							{!! $pages->content !!}

						</div><!-- .postcontent end -->

						<!-- Sidebar
						============================================= -->
						@include(getTheme('widgets.sidebar'))
						<!-- .sidebar end -->
					</div>

				</div>
			</div>
		</section><!-- #content end -->
    
</x-app-layout>