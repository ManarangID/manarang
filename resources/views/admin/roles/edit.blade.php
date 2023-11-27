<x-admin-layout>
@section('title', __('role.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.roles') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.roles') }}</a></li>
              <li class="breadcrumb-item active">{{ __('role.edit_title') }}</li>
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
                <h3 class="card-title">{{ __('role.edit_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('roles.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('roles.update', Hashids::encode($role->id)) }}">
              	@method('PUT')
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-12">
            		    <div class="form-group">
            		      <label>{{ __('role.name') }} *</label>
            		      <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
					@if(isset($role))
            		<div class="row" id="checkAllBox">
            		  <div class="col-md-12 text-center">
            		    <div class="form-group">
							<div class="custom-control custom-checkbox">
								<input class="custom-control-input" type="checkbox" id="customCheckbox1" value="option1">
								<label for="customCheckbox1" class="custom-control-label">{{ __('general.check_all') }}</label>
							</div>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
					  <div class="col-md-3" style="margin-bottom:10px;">
					  	@php $no = 1; @endphp
					  	@foreach ($permissions as $key => $row)
					  		<input type="checkbox" name="permission[]" value="{{ $row }}" {{ in_array($row, $hasPermission) ? "checked" : "" }} /> {{ $row }} <br />
					  		@if ($no++%4 == 0)
					  			</div>
					  			<div class="col-md-3" style="margin-bottom:10px;">
					  		@endif
					  	@endforeach
					  </div>
            		  <!-- /.col -->
            		</div>
					@endif
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-12">
            		    <div class="form-group">
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


<script type="text/javascript">
	$("#customCheckbox1").click(function(){
		$('#checkAllBox input:checkbox').not(this).prop('checked', this.checked);
	});
</script>