<x-admin-layout>
@section('title', __('component.edit_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.component') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('components.index') }}">{{ __('general.components') }}</a></li>
              <li class="breadcrumb-item active">{{ __('component.edit_title') }}</li>
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
                <h3 class="card-title">{{ __('component.edit_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('components.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('components.update', Hashids::encode($component->id)) }}">
              	@method('PUT')
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('component.title') }} *</label>
            		      <input type="text" class="form-control" id="title" name="title" value="{{ $component->title }}" disabled>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-6">
            		    <div class="form-group">
            		      <label>{{ __('component.author') }} *</label>
            		      <input type="text" class="form-control" id="author" name="author" value="{{ $component->author }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            		<div class="row">
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('component.folder') }} *</label>
            		      <input type="email" class="form-control" id="folder" name="folder" value="{{ $component->folder }}">
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('component.type') }}</label>
							<select id="type" name="type" class="form-control select2bs4" style="width: 100%;">
								<option value="component" {{ $component->type == 'component' ? 'selected' : '' }}>{{ __('component.type_component') }}</option>
								<option value="type" {{ $component->type == 'type' ? 'selected' : '' }}>{{ __('component.type_widget') }}</option>
							</select>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		  <div class="col-md-4">
            		    <div class="form-group">
            		      <label>{{ __('component.active') }} *</label>
							<select id="active" name="active" class="form-control select2bs4" style="width: 100%;">
								<option value="Y" {{ $component->active == 'Y' ? 'selected' : '' }}>{{ __('component.active') }}</option>
								<option value="N" {{ $component->active == 'N' ? 'selected' : '' }}>{{ __('component.deactive') }}</option>
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