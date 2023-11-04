<x-app-layout>

		<!-- Page Title
		============================================= -->
		<section id="page-title" class="page-title-parallax include-header" style="background-image: url({{ Storage::url('images/page-title.jpg') }}); padding: 120px 0;" data-bottom-top="background-position:0px 300px;" data-top-bottom="background-position:0px -300px;">

			<div class="container clearfix">
				<h1 data-animate="fadeInUp">{{ $pages->title }}</h1>
				<span data-animate="fadeInUp" data-delay="300">{{ getSetting('web_name') }} {{ getSetting('web_author') }}</span>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="#">Home</a></li>
					<li class="breadcrumb-item"><a href="#">Pages</a></li>
					<li class="breadcrumb-item active" aria-current="page">{{ $pages->title }}</li>
				</ol>
			</div>

		</section><!-- #page-title end -->

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
						<livewire:sidebar />
						<!-- .sidebar end -->
					</div>

				</div>
			</div>
		</section><!-- #content end -->
    
</x-app-layout>