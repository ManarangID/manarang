<x-app-layout>

        <!-- Page Title
        ============================================= -->
        <section id="page-title" class="page-title-mini">

            <div class="container clearfix">
				@if(isset($categories))
                <h1>{{ $categories->title }} ({{ $posts->total() }})</h1>
                @else
                <h1>All Category</h1>
                @endif
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

							<!-- Posts
							============================================= -->
							<div id="posts" class="row gutter-40">

							    @foreach($posts as $post)
								<div class="entry col-12">
									<div class="grid-inner row g-0">
										<div class="col-md-4">
											<div class="entry-image">
												<a href="{{ getPicturepost($post->picture, null, $post->updated_by) }}" data-lightbox="image"><img src="{{ getPicturepost($post->picture, null, $post->updated_by) }}" style="height:250px !important;" alt="{{ $post->title }}"></a>
											</div>
										</div>
										<div class="col-md-8 ps-md-4">
											<div class="entry-title title-sm">
												<h2><a href="{{ prettyUrl($post) }}">{{ $post->title }}</a></h2>
											</div>
											<div class="entry-meta">
												<ul>
													<li><i class="icon-calendar3"></i> {{ date('d F Y' , strtotime($post->created_at)) }}</li>
													<li><a href="{{ prettyUrl($post) }}"><i class="icon-camera-retro"></i>{{ $post->hits }} Views</a></li>
												</ul>
											</div>
											<div class="entry-content">
												<p>{{ \Str::limit(strip_tags($post->content), 250) }}</p>
												<a href="{{ prettyUrl($post) }}" class="more-link">Read More</a>
											</div>
										</div>
									</div>
								</div>
							    @endforeach

							</div><!-- #posts end -->

							<!-- Pager
							============================================= -->
							<div class="d-flex justify-content-between mt-5">
								<a href="#" class="btn btn-outline-secondary">&larr; Older</a>
								<a href="#" class="btn btn-outline-dark">Newer &rarr;</a>
							</div>
							<!-- .pager end -->

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