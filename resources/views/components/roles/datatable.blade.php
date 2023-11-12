<x-admin-layout>
@section('title', __('role.datatable_title'))
  <!-- Content Wrapper. Contains posts content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.permissions') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item active">{{ __('role.datatable_list') }}</li>
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
                <h3 class="card-title ">{{ __('role.datatable_list') }} </h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <div class="input-group-append">
                      <button onclick="location.href='{{ url('dashboard/roles/create') }}'" type="button" class="btn btn-default bg-success">
                      {{ __('general.add') }} <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="POST" action="{{ route('roles.deleteAll') }}">
                @csrf
                  <input type="hidden" name="totaldata" id="totaldata" value="0" />
                  <table id="roles-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="text-align:center;" width="15"></th>
                        <th style="text-align:center;" width="25">{{ __('general.id') }}</th>
                        <th>{{ __('role.name') }}</th>
                        <th>{{ __('role.guard') }}</th>
                        <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <td style="width:10px;" style="text-align:center;">
                          <input type="checkbox" id="titleCheck" data-toggle="tooltip" title="{{ __('general.check_all') }}" />
                        </td>
                        <td colspan="4">
                          <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#alertalldel"><i class="fa fa-trash"></i> {{ __('general.delete_selected') }}</button>
                        </td>
                        <td><button class="btn btn-xs btn-danger d-block d-sm-none" type="button" data-toggle="modal" data-target="#alertalldel">{{ __('general.delete') }}</button></td>
                      </tr>
                    </tfoot>
                  </table>
                </form>
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
  </div>

</x-admin-layout>


<script type="text/javascript">
	$(function() {
		'use strict'
		
		var table = $('#roles-table').DataTable({
			processing: true,
			serverSide: true,
			stateSave: true,
			responsive: {
				details: {
					type: 'column',
					target: -1
				}
			},
			ajax: '{{ url("dashboard/roles/data") }}',
			autoWidth: false,
			order: [[1, 'desc']],
			columnDefs: [{
				targets: 'no-sort',
				orderable: false
			},{
				className: 'control',
				orderable: false,
				targets:   -1
			}],
			columns: [
				{ data: 'check', name: 'check', orderable: false, searchable: false },
				{ data: 'id', name: 'roles.id' },
				{ data: 'name', name: 'roles.name' },
				{ data: 'guard_name', name: 'roles.guard_name' },
				{ data: 'action', name: 'action', orderable: false, searchable: false },
				{ data: 'control', name: 'control', orderable: false, searchable: false },
			],
			drawCallback: function(settings) {
				$("#titleCheck").on('click', function () {
					var checkedStatus = this.checked;
					$("table tbody tr td div:first-child input[type=checkbox]").each(function() {
						this.checked = checkedStatus;
						if (checkedStatus == this.checked) {
							$(this).closest('table tbody tr').removeClass('selected');
							$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
							$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
						}
						if (this.checked) {
							$(this).closest('table tbody tr').addClass('selected');
							$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
							$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
						}
					});
				});	
				$('table tbody tr td div:first-child input[type=checkbox]').on('click', function () {
					var checkedStatus = this.checked;
					this.checked = checkedStatus;
					if (checkedStatus == this.checked) {
						$(this).closest('table tbody tr').removeClass('selected');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
					}
					if (this.checked) {
						$(this).closest('table tbody tr').addClass('selected');
						$(this).closest('table tbody tr').find('input:hidden').attr('disabled', !this.checked);
						$('#totaldata').val($('table tbody input[type=checkbox]:checked').length);
					}
				});
				$('table tbody tr td div:first-child input[type=checkbox]').on('change', function () {
					$(this).closest('tr').toggleClass("selected", this.checked);
				});
				deleter.init();
				$('[data-toggle="tooltip"]').tooltip();
			},
			language: {
				searchPlaceholder: 'Search...',
				sSearch: '',
				lengthMenu: '_MENU_ items/page',
			}
		});
		
		$('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });
		
		$('.data-search').on('keyup', function() {
			table.search(this.value).draw();
		});
	});
</script>