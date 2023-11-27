<x-admin-layout>
@section('title', __('setting.create_title'))

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
              <li class="breadcrumb-item active">{{ __('setting.create_title') }}</li>
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
                <h3 class="card-title">{{ __('setting.create_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('settings.group') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('settings.store') }}">
              	@csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-4">
						<div class="form-group">
							<label>{{ __('setting.group') }} *</label>
							<select id="groups" name="groups" class="form-control select2bs4" style="width: 100%;">
								<option value="General">{{ __('setting.general') }}</option>
								<option value="Image">{{ __('setting.image') }}</option>
								<option value="Config">{{ __('setting.config') }}</option>
								<option value="Mail">{{ __('setting.mail') }}</option>
								<option value="Other">{{ __('setting.other') }}</option>
							</select>
						</div>
						<!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('setting.options') }}</label>
            		      <input type="text" class="form-control" id="options" name="options">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      	<label>{{ __('setting.value') }}</label>
							<input type="text" class="form-control" id="value" name="value" require>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> {{ __('general.add') }}</button>
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
	  $('#createForm').validate({
	    rules: {
	      groups: {
	        required: true,
	      },
	      options: {
	        required: true,
	      },
	      value: {
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