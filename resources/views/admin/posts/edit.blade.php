<x-admin-layout>
@section('title', __('user.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.posts') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">{{ __('general.user') }}</a></li>
              <li class="breadcrumb-item active">{{ __('post.edit_title') }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('post.edit_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('posts.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('posts.update', Hashids::encode($post->id)) }}">
					@method('PUT')
					@csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-8">
            		    <div class="form-group">
            		      <label>{{ __('post.title') }}</label>
            		      <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
            		    </div>
            		    <!-- /.form-group -->
            		    <div class="form-group">
            		      <label>{{ __('post.content') }}</label>
            		      <textarea class="p-3" id="content" name="content">{{ $post->content }}</textarea>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('post.seotitle') }}</label>
            		      <input type="text" class="form-control" id="seotitle" name="seotitle" value="{{ $post->seotitle }}">
            		    </div>
            		    <!-- /.form-group -->
						<div class="form-group">
							<label>{{ __('post.category') }}</label>
							<select id="category_id" name="category_id" class="form-control select2bs4" style="width: 100%;">
								<option value="{{ $post->category_id }}">{{ __('general.selected') }} {{ $post->category->title }}</option>
								{{ categoryTreeOption($categorys) }}
							</select>
						</div>
						<!-- /.form-group -->
            		    <div class="form-group">
            		      <label>{{ __('post.meta_description') }}</label>
            		      <textarea class="form-control" id="meta_description" name="meta_description"> {{ $post->meta_description }}</textarea>
            		    </div>
            		    <!-- /.form-group -->
            		    <div class="form-group">
            		      <label>{{ __('post.tag') }}</label>
            		      <input type="text" class="form-control" id="tag" name="tag" value="{{ $post->tag }}">
            		    </div>
            		    <!-- /.form-group -->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>{{ __('post.type') }}</label>
									<select id="type" name="type" class="form-control select2bs4" style="width: 100%;">
										<option value="general" {{ $post->type == 'general' ? 'selected' : '' }}>{{ __('post.general') }}</option>
										<option value="pagination" {{ $post->type == 'pagination' ? 'selected' : '' }}>{{ __('post.paginatin') }}</option>
										<option value="picture" {{ $post->type == 'picture' ? 'selected' : '' }}>{{ __('post.picture') }}</option>
										<option value="video" {{ $post->type == 'video' ? 'selected' : '' }}>{{ __('post.video') }}</option>
									</select>
								</div>
								<!-- /.form-group -->
								<div class="form-group">
									<label>{{ __('post.headline') }}</label>
									<select id="headline" name="headline" class="form-control select2bs4" style="width: 100%;">
										<option value="Y" {{ $post->headline == 'Y' ? 'selected' : '' }}>{{ __('general.yes') }}</option>
										<option value="N" {{ $post->headline == 'N' ? 'selected' : '' }}>{{ __('general.no') }}</option>
									</select>
								</div>
								<!-- /.form-group -->
							</div>
            		  		<!-- /.col -->
							<div class="col-md-6">
								<div class="form-group">
									<label>{{ __('post.active') }}</label>
									<select id="active" name="active" class="form-control select2bs4" style="width: 100%;">
										<option value="Y" {{ $post->active == 'Y' ? 'selected' : '' }}>{{ __('post.active') }}</option>
										<option value="N" {{ $post->active == 'N' ? 'selected' : '' }}>{{ __('post.draft') }}</option>
									</select>
								</div>
								<!-- /.form-group -->
								<div class="form-group">
									<label>{{ __('post.comment') }}</label>
									<select id="comment" name="comment" class="form-control select2bs4" style="width: 100%;">
										<option value="Y" {{ $post->comment == 'Y' ? 'selected' : '' }}>{{ __('post.active') }}</option>
										<option value="N" {{ $post->comment == 'N' ? 'selected' : '' }}>{{ __('post.deactive') }}</option>
									</select>
								</div>
								<!-- /.form-group -->
							</div>
            		  		<!-- /.col -->
						</div>
	            		<!-- /.row -->
            		    <div class="form-group">
            		      <label>{{ __('post.picture') }}</label>
							<div class="custom-file" x-data="showImage()">
								<input type="file" class="custom-file-input" id="picture" name="picture" @change="showPreview(event)">
								<label class="custom-file-label" for="customFile">@if ( $post->picture == '') Choose file @else {{ $post->picture }} @endif</label>
							</div>
            		    </div>
            		    <!-- /.form-group -->
            		    <div class="form-group">
            		      <label>{{ __('post.picture_description') }}</label>
            		      <textarea class="form-control" id="picture_description" name="picture_description">{{ $post->picture_description }}</textarea>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> {{ __('general.update') }}</button>
            	  </div>
            	</form>
            	<!-- /.form start -->
            	<div class="card-body">
			      <div class="row" id="box-gallery">
			          <div class="col-md-12">
			            <div class="card card-default">
			              <div class="card-header">
			                <h3 class="card-title">{{ __('post.add_picture_gallery') }}</h3>
			              </div>
			              <div class="card-body">
			                <div id="actions" class="row">
								@if(count($post_gallerys) > 0)
									<div class="col-md-12 text-center">
										<div class="divider-text">{{ __('post.picture_gallery') }}</div>
									</div>
								@endif
								
								@foreach($post_gallerys as $post_gallery)
									<div class="col-md-12 col-lg-6 col-xl-3" id="box-item-gallery-{{ Hashids::encode($post_gallery->id) }}">
              						  <div class="card mb-2 bg-gradient-dark">
              						    <img class="card-img-top" src="{{ Storage::url('public/post/gallery/'.$post_gallery->picture) }}" alt="{{ $post_gallery->title }}">
              						    <div class="card-img-overlay d-flex flex-column justify-content-end">
              						      <h5 class="card-title text-primary text-white"></h5>
              						      <p class="card-text text-white pb-2 pt-1"></p>
              						      <a href="#" class="text-white">{{ $post_gallery->title }}</a>
												<div class="d-flex justify-content-center">
													<div class="btn-group">
														<a href="javascript:void(0);" class="btn btn-dark btn-icon btn-remove-gallery" id="{{ Hashids::encode($post_gallery->id) }}"><i class="fa fa-trash"></i> {{ __('general.delete') }}</a>
													</div>
												</div>
              						    </div>
              						  </div>
              						</div>	
								@endforeach
			                </div>
							<!-- Dropzone form -->
							<div id="actions" class="row">
								<div class="col-md-12">
									<form method="post" action="{{route('posts.createGallery')}}" enctype="multipart/form-data" class="dropzone" id="dropzone">
										@csrf
										<div class="form-group">
											<input type="hidden" class="form-control" id="title" name="title" value="{{ $post->title }}">
											<input type="hidden" class="form-control" id="post_id" name="post_id" value="{{ $post->id }}">
										</div>
									</form>
								</div>
							</div>
			              </div>
			              <!-- /.card-body -->
			              <div class="card-footer">
			                Visit <a href="https://www.dropzonejs.com">dropzone.js documentation</a> for more examples and information about the plugin.
			              </div>
			            </div>
			            <!-- /.card -->
			          </div>
			      </div>
			      <!-- /.row -->
            	</div>
            	<!-- /.card-body -->
            </div>
            <!-- /.card -->
			
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
</x-admin-layout>

<!-- Page specific script -->
<script>
	$(function () {
	  //Initialize Select2 Elements
	  $('.select2').select2()	

	  //Initialize Select2 Elements
	  $('.select2bs4').select2({
	    theme: 'bootstrap4'
	  })
	})
</script>

<script>
	$(function () {
	  $('#createForm').validate({
	    rules: {
	      seotitle: {
	        required: true,
	      },
	      title: {
	        required: true,
	      },
	    },
	    messages: {
	      seotitle: {
	        required: "Seotitle cannot be null"
	      },
	      title: "Please enter post title"
	    },
	    errorElement: 'span',
	    errorPlacement: function (error, element) {
	      error.addClass('invalid-feedback');
	      element.closest('.form-group').append(error);
	    },
	    highlight: function (element, errorClass, validClass) {
	      $(element).addClass('is-invalid');
	    },
	    unhighlight: function (element, errorClass, validClass) {
	      $(element).removeClass('is-invalid');
	    }
	  });
	});
	
	$(function() {
		var tagname = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: '{{ url("tags/get-tag") }}',
				prepare: function (query, settings) {
					$(".tt-hint").show();
					settings.type = "GET";
					settings.data = 'term=' + query;
					return settings;
				},
				filter: function (parsedResponse) {
					$(".tt-hint").hide();
					return parsedResponse.data;
				}
			}
		});
		
		tagname.initialize();
		
		$('#tag').tagsinput({
			typeaheadjs: {
				name: 'tagname',
				displayKey: 'title',
				valueKey: 'title',
				source: tagname.ttAdapter()
			}
		});
	});
	
	$(document).ready(function() {
		
		$(document).on('click', '.btn-remove-gallery', function(e){
			var id = $(this).attr('id');
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			var dataString = {
				id : id,
				_token : CSRF_TOKEN
			};
			$.ajax({
				type: 'POST',
				url: '{{ url("posts/delete-gallery") }}',
				data: dataString,
				cache : false,
				success: function(result){
					if (result.code == '2000') {
						$('#box-item-gallery-' + id).hide();
					} else {
						$(".alert-gallery").fadeTo(2000, 500).slideUp(1000, function(){
							$(".alert-gallery").alert('close');
						});
					}
				}
			});
		});
	});

	
</script>

<script>
        function showImage() {
            return {
                showPreview(event) {
                    if (event.target.files.length > 0) {
                        var src = URL.createObjectURL(event.target.files[0]);
                        var preview = document.getElementById("preview");
                        preview.src = src;
                        preview.style.display = "block";
                    }
                }
            }
        }
    </script>