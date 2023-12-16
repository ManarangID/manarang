<x-admin-layout>
@section('title', __('gallery.create_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.gallerys') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('gallerys.index') }}">{{ __('general.gallerys') }}</a></li>
              <li class="breadcrumb-item active">{{ __('gallery.create_title') }}</li>
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
                <h3 class="card-title">{{ __('gallery.create_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('gallerys.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('gallerys.store') }}">
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('gallery.album') }}</label>
							<select id="album_id" name="album_id" class="form-control select2bs4" style="width: 100%;">
								@foreach($albums as $album)
									<option value="{{ $album->id }}">{{ $album->title }}</option>
								@endforeach
							</select>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('gallery.title') }}</label>
            		      <input type="text" class="form-control" id="title" name="title">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      	<label>{{ __('gallery.picture') }}</label>
							<div class="custom-file" x-data="showImage()">
								<input type="file" class="custom-file-input" id="picture" name="picture" @change="showPreview(event)">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		<!-- /.row -->
					</div>
            		<div class="row">
            		  <div class="col-md-12">
            		    <div class="form-group">
            		      <label>{{ __('gallery.content') }}</label>
            		      <textarea class="p-3" id="content" name="content"></textarea>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> {{ __('general.create') }}</button>
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
	      title: "Please enter pages title"
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