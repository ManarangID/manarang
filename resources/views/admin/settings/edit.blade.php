<x-admin-layout>
@section('title', __('setting.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.settings') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.settings') }}</a></li>
              <li class="breadcrumb-item active">{{ __('setting.edit_title') }}</li>
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
                <h3 class="card-title">{{ __('setting.edit_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('settings.group') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('settings.update', Hashids::encode($setting->id)) }}">
              	@method('PUT')
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-4">
						<div class="form-group">
							<label>{{ __('setting.group') }} *</label>
							<select id="groups" name="groups" class="form-control select2bs4" style="width: 100%;">
								<option value="General" {{ $setting->groups == 'General' ? 'selected' : '' }}>{{ __('setting.general') }}</option>
								<option value="Image" {{ $setting->groups == 'Image' ? 'selected' : '' }}>{{ __('setting.image') }}</option>
								<option value="Config" {{ $setting->groups == 'Config' ? 'selected' : '' }}>{{ __('setting.config') }}</option>
								<option value="Mail" {{ $setting->groups == 'Mail' ? 'selected' : '' }}>{{ __('setting.mail') }}</option>
								<option value="Other" {{ $setting->groups == 'Other' ? 'selected' : '' }}>{{ __('setting.other') }}</option>
							</select>
						</div>
						<!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('setting.options') }}</label>
            		      <input type="text" class="form-control" id="options" name="options" value="{{ $setting->options }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
					@if (isset($setting))
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      	<label>{{ __('setting.value') }}</label>
						  	@if($setting->options == 'favicon' || $setting->options == 'logo' || $setting->options == 'logo_footer')
							<div class="custom-file" x-data="showImage()">
								<input type="file" class="custom-file-input" id="image" name="image" @change="showPreview(event)">
								<label class="custom-file-label" for="customFile">@if ( $setting->value == '') Choose file @else {{ $setting->value }} @endif</label>
							</div>
							
							<div class="row">
								<div class="col-md-12 col-lg-6 col-xl-4">
									<div class="card mb-2 bg-gradient-dark">
									@if ( $setting->value != '')
									<img class="card-img-top" id="preview" src="{{ Storage::url('public/images/'.$setting->value) }}" alt="{{ $setting->value }}">
									@else
									<img class="card-img-top" id="preview">
									@endif
									<div class="card-img-overlay d-flex flex-column justify-content-end">
									</div>
									</div>
								</div>
							</div>
							<!-- /.row -->
							@elseif($setting->options == 'maintenance_mode' || $setting->options == 'member_registration' || $setting->options == 'comment')
							<select id="value" name="value" class="form-control select2bs4" style="width: 100%;">
								@if (isset($setting))
								<option value="{{ $setting->value }}">{{ __('general.selected') }} {{ $setting->value == 'Y' ? 'Yes' : 'No' }}</option>
								@endif
								<option value="Y">Y</option>
								<option value="N">N</option>
							</select>
							@elseif($setting->options == 'mail_protocol')
							<select id="value" name="value" class="form-control select2bs4" style="width: 100%;">
								@if (isset($setting))
								<option value="{{ $setting->value }}">{{ __('general.selected') }} {{ $setting->value }}</option>
								@endif
								<option value="SMTP">SMTP</option>
								<option value="Mail">Mail</option>
							</select>
							@elseif($setting->options == 'sitemap_frequency')
							<select id="value" name="value" class="form-control select2bs4" style="width: 100%;">
								@if (isset($setting))
								<option value="{{ $setting->value }}">{{ __('general.selected') }} {{ $setting->value }}</option>
								@endif
								<option value="daily">daily</option>
								<option value="weekly">weekly</option>
								<option value="monthly">monthly</option>
								<option value="yearly">yearly</option>
							</select>
							@elseif($setting->options == 'slug')
							<select id="value" name="value" class="form-control select2bs4" style="width: 100%;">
								@if (isset($setting))
								<option value="{{ $setting->value }}">{{ __('general.selected') }} {{ $setting->value }}</option>
								@endif
								<option value="detailpost/slug">detailpost/slug</option>
								<option value="post/slug">post/slug</option>
								<option value="post/slug-id">post/slug-id</option>
								<option value="article/yyyy/mm/dd/slug">article/yyyy/mm/dd/slug</option>
							</select>
							@else
							<input type="text" class="form-control" id="value" name="value" value="{{ $setting->value }}" require>
							@endif
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
					@endif
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> {{ __('general.edit') }}</button>
            	  </div>
            	</form>
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
	  $('#editForm').validate({
	    rules: {
	      email: {
	        required: true,
	        email: true,
	      },
	      name: {
	        required: true,
	      },
	    },
	    messages: {
	      email: {
	        required: "Please enter a email address",
	        email: "Please enter a valid email address"
	      },
	      password: {
	        required: "Please provide a password",
	        minlength: "Your password must be at least 5 characters long"
	      },
	      name: "Please enter your name"
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