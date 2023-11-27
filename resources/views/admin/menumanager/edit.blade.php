<x-admin-layout>
@section('title', __('menumanager.edit_title'))

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
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.menu_manager') }}</a></li>
              <li class="breadcrumb-item active">{{ __('menumanager.edit_title') }}</li>
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
                <h3 class="card-title">{{ __('menumanager.edit_title') }}</h3>
				<div class="card-tools">
					<button onclick="location.href='javascript:history.back()'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('menumanager.update', Hashids::encode($menumanager->id)) }}">
              	@method('PUT')
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-4">
						<div class="form-group">
							<label>{{ __('menumanager.title') }} *</label>
							<input type="text" class="form-control" id="title" name="title" value="{{ $menumanager->title }}">
						</div>
						<!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('menumanager.url') }}</label>
            		      <input type="text" class="form-control" id="url" name="url" value="{{ $menumanager->url }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('menumanager.class') }}</label>
            		      <input type="text" class="form-control" id="class" name="class" value="{{ $menumanager->class }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      	<label>{{ __('menumanager.target') }}</label>
							<select id="target" name="target" class="form-control select2bs4" style="width: 100%;">
								<option value="none" {{ $menumanager->target == 'none' ? 'selected' : '' }}>none</option>
								<option value="_blank" {{ $menumanager->target == '_blank' ? 'selected' : '' }}>_blank</option>
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
	      title: {
	        required: true,
	      },
	      url: {
	        required: true,
	      },
	    },
	    messages: {
	      title:  "Please enter menu title",
	      url:  "Please fill in the url address"
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