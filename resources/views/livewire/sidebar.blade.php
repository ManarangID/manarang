<div class="sidebar col-lg-3">
							<div class="sidebar-widgets-wrap">

								<div class="widget clearfix">

									<div class="tabs mb-0 clearfix" id="sidebar-tabs">

										<ul class="tab-nav clearfix">
											<li><a href="#tabs-1">Popular</a></li>
											<li><a href="#tabs-2">Recent</a></li>
											<li><a href="#tabs-3"><i class="icon-comments-alt me-0"></i></a></li>
										</ul>

										<div class="tab-container">

											<div class="tab-content clearfix" id="tabs-1">
												<div class="posts-sm row col-mb-30" id="popular-post-list-sidebar">
                                                @php $nop = 1; @endphp
                            					@foreach(popularPost(5) as $popularPost)
													<div class="entry col-12">
														<div class="grid-inner row g-0">
															<div class="col-auto">
																<div class="entry-image">
																	<a href="{{ prettyUrl($popularPost) }}"><img class="rounded-circle" src="{{ getPicturepost($popularPost->picture, 'thumb', $popularPost->updated_by) }}" alt="{{ $popularPost->title }}"></a>
																</div>
															</div>
															<div class="col ps-3">
																<div class="entry-title">
																	<h4><a href="#">{{ $popularPost->title }}</a></h4>
																</div>
																<div class="entry-meta">
																	<ul>
																		<li><i class="icon-bi-emoji-heart-eyes-fill"></i> {{ $popularPost->hits }} Views</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
                                                    @endforeach
                                                    
												</div>
											</div>
											<div class="tab-content clearfix" id="tabs-2">
												<div class="posts-sm row col-mb-30" id="recent-post-list-sidebar">
                                                    @php $nol = 1; @endphp
                                                    @foreach(latestPost(5) as $latestPost)
													<div class="entry col-12">
														<div class="grid-inner row g-0">
															<div class="col-auto">
																<div class="entry-image">
																	<a href="{{ prettyUrl($latestPost) }}"><img class="rounded-circle" src="{{ getPicturepost($latestPost->picture, 'thumb', $latestPost->updated_by) }}" alt="{{ $popularPost->title }}" alt="{{ $latestPost->title }}"></a>
																</div>
															</div>
															<div class="col ps-3">
																<div class="entry-title">
																	<h4><a href="{{ prettyUrl($latestPost) }}">{{ $latestPost->title }}</a></h4>
																</div>
																<div class="entry-meta">
																	<ul>
																		<li>{{ date('d F Y' , strtotime($latestPost->created_at)) }}</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
                                                    @endforeach

												</div>
											</div>
											<div class="tab-content clearfix" id="tabs-3">
												<div class="posts-sm row col-mb-30" id="recent-comments-list-sidebar">
													<div class="entry col-12">
														<div class="grid-inner row g-0">
															<div class="col-auto">
																<div class="entry-image">
																	<a href="#"><img class="rounded-circle" src="images/icons/avatar.jpg" alt="User Avatar"></a>
																</div>
															</div>
															<div class="col ps-3">
																<strong>John Doe:</strong> Veritatis recusandae sunt repellat distinctio...
															</div>
														</div>
													</div>

													<div class="entry col-12">
														<div class="grid-inner row g-0">
															<div class="col-auto">
																<div class="entry-image">
																	<a href="#"><img class="rounded-circle" src="images/icons/avatar.jpg" alt="User Avatar"></a>
																</div>
															</div>
															<div class="col ps-3">
																<strong>Mary Jane:</strong> Possimus libero, earum officia architecto maiores....
															</div>
														</div>
													</div>

													<div class="entry col-12">
														<div class="grid-inner row g-0">
															<div class="col-auto">
																<div class="entry-image">
																	<a href="#"><img class="rounded-circle" src="images/icons/avatar.jpg" alt="User Avatar"></a>
																</div>
															</div>
															<div class="col ps-3">
																<strong>Site Admin:</strong> Deleniti magni labore laboriosam odio...
															</div>
														</div>
													</div>
												</div>
											</div>

										</div>

									</div>

								</div>

								<div class="widget clearfix">

									<h4>Tag Cloud</h4>
									<div class="tagcloud">
                                        @foreach(getTag(10) as $tag)
										<a href="{{ url('tag/'.$tag->seotitle) }}">{{ $tag->title }}</a>
										@endforeach
									</div>

								</div>

							</div>

						</div>
