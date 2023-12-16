<x-admin-layout>
@section('title', __('category.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.categories') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('general.categories') }}</a></li>
              <li class="breadcrumb-item active">{{ __('category.edit_title') }}</li>
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
                <h3 class="card-title">{{ __('category.edit_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('categories.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('categories.update', Hashids::encode($category->id)) }}">
              	@method('PUT')
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('category.title') }} *</label>
            		      <input type="text" class="form-control" id="title" name="title" value="{{ $category->title }}" required>
            		      <input type="hidden" id="seotitle" name="seotitle" value="{{ $category->seotitle }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('category.parent') }} *</label>
							<select id="parent" name="parent" class="form-control select2bs4" style="width: 100%;">
								@if($category->parent == 0)
								<option value="0">{{ __('general.selected') }} {{ __('category.no_parent') }}</option>
								@else
								<option value="{{ $category->parent }}">{{ __('general.selected') }} {{ $category->mainParent->title }}</option>
								@endif
								<option value="0">{{ __('category.no_parent') }}</option>
								{{ categoryTreeOption($parents) }}
							</select>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('category.active') }} *</label>
							<select id="active" name="active" class="form-control select2bs4" style="width: 100%;">
								<option value="Y" {{ $category->active == 'Y' ? 'selected' : '' }}>{{ __('category.active') }}</option>
								<option value="N" {{ $category->active == 'N' ? 'selected' : '' }}>{{ __('category.deactive') }}</option>
							</select>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('category.picture') }}</label>
							<div class="custom-file" x-data="showImage()">
								<input type="file" class="custom-file-input" id="picture" name="picture" @change="showPreview(event)">
								<label class="custom-file-label" for="customFile">@if ( $category->picture == '') Choose file @else {{ $category->picture }} @endif</label>
							</div>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
					<div class="row">
						<div class="col-md-12 col-lg-6 col-xl-4">
							<div class="card mb-2 bg-gradient-dark">
							@if ( $category->picture != '')
							<img class="card-img-top" id="preview" src="{{ Storage::url('public/post/category/'.$category->picture) }}" alt="{{ $category->title }}">
							@else
							<img class="card-img-top" id="preview">
							@endif
							<div class="card-img-overlay d-flex flex-column justify-content-end">
							</div>
							</div>
						</div>
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-paper-plane"></i> {{ __('general.update') }}</button>
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
	      seotitle: {
	        required: true,
	      },
	    },
	    messages: {
	      title: "Please enter category title",
	      seotitle: "Please enter category seotitle"
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