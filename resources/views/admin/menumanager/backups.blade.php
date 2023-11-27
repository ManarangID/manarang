<x-admin-layout>
@section('title', __('backup.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.backup') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.backup') }}</a></li>
              <li class="breadcrumb-item active">{{ __('backup.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('backup.datatable_list') }}</h3>
				<div class="card-tools">
					<button onclick="location.href='{{ route('backups.create') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('backup.file') }}</th>
                      <th>{{ __('backup.size') }}</th>
                      <th>{{ __('backup.date') }}</th>
                      <th>{{ __('backup.age') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                  @foreach ($backups as $key => $backup)
                    <tr>
                      <td>{{ ++$key }}</td>
                      <td>{{ $backup['file_name'] }}</td>
                      <td>{{ $backup['file_size'] }}</td>
                      <td>{{ date('d/M/Y, g:ia', strtotime($backup['last_modified'])) }}</td>
                      <td>{{ diff_date_for_humans($backup['last_modified']) }}</td>
                      <td>
                          <form method="POST" action="{{ route('backups.destroy', $backup['file_name']) }}">
                            @csrf
                            @method('delete')
                            <a class="btn btn-primary" href="{{ Storage::url('public/ManarangID/'.$backup['file_name']) }}">
                                <i class="fas fa-download"></i></a>
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="width:10px;" style="text-align:center;">
                        <input type="checkbox" id="titleCheck" data-toggle="tooltip" title="{{ __('general.check_all') }}" />
                      </td>
                      <td colspan="6">
                      <!-- <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#alertalldel"><i class="fa fa-trash"></i> {{ __('general.delete_selected') }}</button> -->
                      <a href="" class="btn btn-xs btn-danger removeAll" id="removeAll"><i class="fa fa-trash"></i> {{ __('general.delete_selected') }}</a>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
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
	
</x-admin-layout><script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

