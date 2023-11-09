<li class="comment even thread-even depth-1" id="li-comment-1">
                                        
    <div id="comment-1" class="comment-wrap clearfix">

        <div class="comment-meta">

            <div class="comment-author vcard">

                <span class="comment-avatar clearfix">
                <img alt='Image' src='https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=60' class='avatar avatar-60 photo avatar-default' height='60' width='60' /></span>

            </div>

        </div>

        <div class="comment-content clearfix">

            <div class="comment-author">{{ $comment->name }}<span><a href="#" title="Permalink to this comment">{{ date('yyyy-mm-dd', strtotime($comment->created_at)) }}</a></span></div>

            <p>{{ strip_tags($comment->content) }}</p>

            <a class='comment-reply-link'  id="{{ $comment->id }}" href='javascript:void(0);'><i class="icon-reply"></i></a>

        </div>

        <div class="clear"></div>

    </div>

	@if (count($comment->children) > 0)
	<ul class='children'>
			@foreach($comment->children as $comment)
				@include(getTheme('widgets.subcomment'), $comment)
			@endforeach

	</ul>
	@endif

</li>