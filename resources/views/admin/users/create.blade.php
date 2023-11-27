<x-admin-layout>
@section('title', __('user.create_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.user') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.user') }}</a></li>
              <li class="breadcrumb-item active">{{ __('user.create_title') }}</li>
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
                <h3 class="card-title">{{ __('user.create_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('users.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('users.store') }}">
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('user.username') }}</label>
            		      <input type="text" class="form-control" value="{{ isset($user) ? $user->username : __('user.auto') }}" disabled>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('user.name') }}</label>
            		      <input type="text" class="form-control" id="name" name="name">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('user.email') }}</label>
            		      <input type="email" class="form-control" id="email" name="email">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('user.telephone') }}</label>
            		      <input type="text" class="form-control" id="telephone" name="telephone">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('user.password') }}</label>
            		      <input type="password" class="form-control" id="password" name="password">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-12">
            		    <div class="form-group">
            		      <label>{{ __('user.bio') }}</label>
            		      <textarea class="form-control" rows="3" id="bio" name="bio"></textarea>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-6">
						<div class="form-group">
						<label>{{ __('user.block') }}</label>
						<select id="block" name="block" class="form-control select2bs4" style="width: 100%;">
							<option value="Y">{{ __('user.block') }}</option>
							<option value="N">{{ __('user.unblock') }}</option>
						</select>
						</div>
						<!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
              		  <div class="col-md-6">
              		    <div class="form-group">
              		      <label>{{ __('user.role') }}</label>
              		      <select name="roles[]" class="form-control select2bs4" style="width: 100%;">
							@foreach ($roles as $row)
						 	<option value="{{ $row->name }}">{{ $row->name }}</option>
							@endforeach
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
	      email: {
	        required: true,
	        email: true,
	      },
	      password: {
	        required: true,
	        minlength: 5
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