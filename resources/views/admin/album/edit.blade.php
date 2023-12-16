<x-admin-layout>
@section('title', __('album.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.albums') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('albums.index') }}">{{ __('general.albums') }}</a></li>
              <li class="breadcrumb-item active">{{ __('album.edit_title') }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
		<!-- START ALERTS AND CALLOUTS -->
        <h5 class="mt-4 mb-2">{{ __('album.edit_title') }}</h5>

        <div class="row">
          <div class="col-md-6">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-exclamation-triangle"></i>
                  {{ __('general.albums') }}
                </h3>
              </div>
              <!-- /.card-header -->
			  	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('albums.update', Hashids::encode($albums->id)) }}">
                	@method('PUT') @CSRF
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<label>{{ __('album.title') }}</label>
								<input type="text" class="form-control" id="title" name="title" value="{{ $albums->title }}">
								<input type="hidden" class="form-control" id="seotitle" name="seotitle" value="{{ $albums->seotitle }}">
								</div>
								<!-- /.form-group -->
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>{{ __('album.active') }}</label>
									<select id="active" name="active" class="form-control select2bs4" style="width: 100%;">
										<option value="Y" {{ $albums->active == 'Y' ? 'selected' : '' }}>{{ __('album.active') }}</option>
										<option value="N" {{ $albums->active == 'N' ? 'selected' : '' }}>{{ __('album.deactive') }}</option>
									</select>
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
        		<!-- form start -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-md-6">
            <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-bullhorn"></i>
                  {{ __('general.gallerys') }}
                </h3>
              </div>
              <!-- /.card-header -->
				<div class="card-body">
		            <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                	<div id="accordion">
						@if(count($gallerys) > 0)
                	  <div class="card card-primary">
                	    <div class="card-header">
                	      <h4 class="card-title w-100">
                	        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
							{{ __('post.picture_gallery') }}
                	        </a>
                	      </h4>
                	    </div>
                	    <div id="collapseOne" class="collapse show" data-parent="#accordion">
                	      <div class="card-body">
							<div class="row">
						  		@foreach($gallerys as $gallery)
									<div class="col-md-12 col-lg-6 col-xl-6" id="box-item-gallery-{{ Hashids::encode($gallery->id) }}">
              						  <div class="card mb-2 bg-gradient-dark">
              						    <img class="card-img-top" src="{{ Storage::url('public/gallery/'.$gallery->picture) }}" alt="{{ $gallery->title }}">
              						    <div class="card-img-overlay d-flex flex-column justify-content-end">
              						      <h5 class="card-title text-primary text-white"></h5>
              						      <p class="card-text text-white pb-2 pt-1"></p>
              						      <a href="#" class="text-white">{{ $gallery->title }}</a>
												<div class="d-flex justify-content-center">
													<div class="btn-group">
														<a href="javascript:void(0);" class="btn btn-dark btn-icon btn-remove-gallery" id="{{ Hashids::encode($gallery->id) }}"><i class="fa fa-trash"></i> {{ __('general.delete') }}</a>
													</div>
												</div>
              						    </div>
              						  </div>
              						</div>	
								@endforeach
                	      </div>
                	      </div>
                	    </div>
                	  </div>
						@endif
                	</div>
					<div class="row">
						<div class="col-md-12">
							<form method="post" action="{{route('albums.createGallery')}}" enctype="multipart/form-data" class="dropzone" id="dropzone">
								@csrf
								<div class="form-group">
									<input type="hidden" class="form-control" id="title" name="title" value="{{ $albums->title }}">
									<input type="hidden" class="form-control" id="album_id" name="album_id" value="{{ $albums->id }}">
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- /.card-body -->
				<div class="card-footer">
				<code>{{ __('general.max_file') }}</code>
				</div>
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
				url: '{{ url("albums/delete-gallery") }}',
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