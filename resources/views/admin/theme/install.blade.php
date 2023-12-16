<x-admin-layout>
@section('title', __('theme.install_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.themes') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('themes.index') }}">{{ __('general.themes') }}</a></li>
              <li class="breadcrumb-item active">{{ __('theme.install_title') }}</li>
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
                <h3 class="card-title">{{ __('theme.install_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('themes.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('themes.processInstall') }}">
                @csrf
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-12">
            		    <div class="form-group">
            		      <label>{{ __('theme.files') }}</label>
						  	<div class="custom-file">
								<input type="file" class="custom-file-input" id="customFile" name="files">
								<label class="custom-file-label" for="customFile">{{ __('general.select_file') }}</label>
							</div>
            		    </div>
            		    <!-- /.form-group -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
					<dl class="row">
						<dt class="col-sm-4">{{ __('theme.instruction_1') }}</dt>
						<dd class="col-sm-8">
						<ol>
							<li>{{ __('theme.instruction_2') }}</li>
							<li>{{ __('theme.instruction_3') }}</li>
							<li>{{ __('theme.instruction_4') }}</li>
							<li>{{ __('theme.instruction_5') }}</li>
						</ol></dd>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-cog"></i> {{ __('theme.install') }}</button>
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
<script type="text/javascript">
	$(function() {
		$('#customFile').on('change',function(){
			var fileName = document.getElementById("customFile").files[0].name;
			$(this).next('.custom-file-label').addClass("selected").html(fileName);
		});
	});
</script>