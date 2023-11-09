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

                            <div class="single-post mb-0">

                                <!-- Single Post
                                ============================================= -->
                                <div class="entry clearfix">
                                    <!-- Entry Title
                                    ============================================= -->
                                    <div class="entry-title">
                                        <h2>{{ $post->title }}</h2>
                                    </div><!-- .entry-title end -->

                                    <!-- Entry Meta
                                    ============================================= -->
                                    <div class="entry-meta">
                                        <ul>
                                            <li><i class="icon-calendar3"></i> {{ date('d F Y' , strtotime($post->created_at)) }}</li>
                                            <li><i class="icon-user"></i> {{ $post->name }}</li>
                                            <li><i class="icon-line-eye"> {{ $post->hits }} Views</i></li>
                                            <li><a href="{{ url('category/'.$post->cseotitle) }}"><i class="icon-news"> {{ $post->ctitle }}</i></a></li>
                                        </ul>
                                    </div><!-- .entry-meta end -->

                                    <!-- Entry Image
                                    ============================================= -->
                                    <div class="entry-image">
                                        <a href="#"><img src="{{ getPicturepost($post->picture, null, $post->updated_by) }}" alt="{{ $post->title }}"></a>
                                    </div><!-- .entry-image end -->

                                    <!-- Entry Content
                                    ============================================= -->
                                    <div class="entry-content mt-0">

                                        {!! $content !!}
                                        <!-- Post Single - Content End -->

                                        <div class="clear"></div>

                                        <!-- Post Single - Share
                                        ============================================= -->
                                        <div class="si-share border-0 d-flex justify-content-between align-items-center">
                                            <span>Share this Post:</span>
                                            <div>
                                                <a href="#" class="social-icon si-borderless si-facebook">
                                                    <i class="icon-facebook"></i>
                                                    <i class="icon-facebook"></i>
                                                </a>
                                                <a href="#" class="social-icon si-borderless si-twitter">
                                                    <i class="icon-twitter"></i>
                                                    <i class="icon-twitter"></i>
                                                </a>
                                                <a href="#" class="social-icon si-borderless si-pinterest">
                                                    <i class="icon-pinterest"></i>
                                                    <i class="icon-pinterest"></i>
                                                </a>
                                                <a href="#" class="social-icon si-borderless si-gplus">
                                                    <i class="icon-gplus"></i>
                                                    <i class="icon-gplus"></i>
                                                </a>
                                                <a href="#" class="social-icon si-borderless si-rss">
                                                    <i class="icon-rss"></i>
                                                    <i class="icon-rss"></i>
                                                </a>
                                                <a href="#" class="social-icon si-borderless si-email3">
                                                    <i class="icon-email3"></i>
                                                    <i class="icon-email3"></i>
                                                </a>
                                            </div>
                                        </div><!-- Post Single - Share End -->

                                    </div>
                                </div><!-- .entry end -->

                                <!-- Post Navigation
                                ============================================= -->
                                @if($post->type == 'pagination')
                                <div class="row justify-content-between col-mb-30 post-navigation">
                                    {!! postWithPagination($paginator, '<span class="ti-angle-left"></span>', '<span class="ti-angle-right"></span>') !!}
                                </div><!-- .post-navigation end -->
                                @endif

                                <div class="line"></div>

                                <!-- Post Author Info
                                ============================================= -->
                                <div class="card">
                                    <div class="card-header"><strong>Posted by <a href="#">{{ $post->name }}</a></strong></div>
                                    <div class="card-body">
                                        <div class="author-image">
                                            <img src="{{ getPictureuser($post->photo, null, $post->updated_by) }}" alt="Image" class="rounded-circle">
                                        </div>
                                        {{ $post->bio }}
                                    </div>
                                </div><!-- Post Single - Author End -->

                                <div class="line"></div>

                                <h4>Related Posts:</h4>

                                <div class="related-posts row posts-md col-mb-30">
                                @foreach(relatedPost($post->id, $post->tag, 2, 0) as $relatedPost)
                                    <div class="entry col-12 col-md-12">
                                        <div class="grid-inner row align-items-center gutter-20">
                                            <div class="col-4">
                                                <div class="entry-image">
                                                    <a href="{{ prettyUrl($relatedPost) }}"><img src="{{ getPicturepost($relatedPost->picture, 'null', $relatedPost->updated_by) }}" alt="{{ $relatedPost->title }}"></a>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="entry-title title-xs">
                                                    <h3><a href="{{ prettyUrl($relatedPost) }}">{{ $relatedPost->title }}</a></h3>
                                                </div>
                                                <div class="entry-meta">
                                                    <ul>
                                                        <li><i class="icon-calendar3"></i> {{ date('d F Y', strtotime($relatedPost->created_at)) }}</li>
                                                        <li><a href="{{ prettyUrl($relatedPost) }}"><i class="icon-eye"></i> {{ $relatedPost->hits }} Views</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                </div>

                                <!-- Comments
                                ============================================= -->
                                @if(getSetting('comment') == 'Y')
                                <div id="comments" class="clearfix">
                                    @if($post->comments_count > 0)                                        
                                    <h3 id="comments-title"><span>({{ $post->comments_count }})</span> Comments</h3>

                                    <!-- Comments List
                                    ============================================= -->
                                    <ol class="commentlist clearfix">
                                    
                                        @each(getTheme('widgets.comment'), getComments($post->id, 5), 'comment', getTheme('widgets.comment'))
        
                                    </ol><!-- .commentlist end -->
                                    @else
                                    <h3 id="comments-title">There are no comments yet</h3>
                                    @endif
                                    <div class="clear"></div>

                                    <!-- Comment Form
                                    ============================================= -->
                                    <div id="respond">

                                        <h3>Leave a <span>Comment</span></h3>

                                        <form class="row" action="{{ url('comment/send/'.$post->seotitle) }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="parent" id="parent" value="{{ old('parent') == null ? 0 : old('parent') }}" />
                                        <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}" />
						
                                            <div class="col-sm-12">
                                                @if (Session::has('flash_message'))
                                                    <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
                                                @endif
                                                
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label for="author">Name</label>
                                                <input type="text" name="name" id="name" value="{{ old('name') }}" size="22" tabindex="1" class="sm-form-control" />
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" id="email" value="{{ old('email') }}" size="22" tabindex="2" class="sm-form-control" />
                                            </div>

                                            <div class="w-100"></div>

                                            <div class="col-12 form-group">
                                                <label for="comment">Comment</label>
                                                <textarea name="content" id="content" cols="58" rows="7" tabindex="4" class="sm-form-control">{{ old('content') }}</textarea>
                                            </div>

                                            <div class="col-12 form-group">
                                            {!! NoCaptcha::display() !!}
                                            </div>

                                            <div class="col-12 form-group">
                                                <button name="submit" type="submit" id="submit-button" tabindex="5" value="Submit" class="button button-3d m-0">Submit Comment</button>
                                            </div>
                                        </form>

                                    </div><!-- #respond end -->

                                </div><!-- #comments end -->
                                @endif

                            </div>

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