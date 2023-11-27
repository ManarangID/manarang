<x-admin-layout>
@section('title', __('setting.datatable_title'))

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
              <li class="breadcrumb-item active">{{ __('setting.datatable_list') }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
			<div class="card-header">
                <h3 class="card-title">{{ __('setting.datatable_list') }}</h3>
                  <div class="card-tools">
                    <button onclick="location.href='{{ route('settings.create') }}'" type="button" class="btn btn-app"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                  	<button onclick="location.href='{{ route('settings.group') }}'" type="button" class="btn btn-app"> <i class="fas fa-table"></i> {{ __('setting.table') }}</button>
					<button onclick="location.href='{{ route('backups.index') }}'" type="button" class="btn btn-app"> <i class="fas fa-database"></i> {{ __('setting.backup') }}</button>
                  </div>
              </div>
              <div class="card-body">
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
</x-admin-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>