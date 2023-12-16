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
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ __('setting.datatable_list') }}</h3>
                  <div class="card-tools">
                    <button onclick="location.href='{{ route('settings.create') }}'" type="button" class="btn btn-app"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                  	<button onclick="location.href='{{ route('settings.group') }}'" type="button" class="btn btn-app"> <i class="fas fa-table"></i> {{ __('setting.table') }}</button>
					          <button onclick="location.href='{{ route('backups.index') }}'" type="button" class="btn btn-app"> <i class="fas fa-database"></i> {{ __('setting.backup') }}</button>
                    </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('setting.group') }}</th>
                      <th>{{ __('setting.options') }}</th>
                      <th>{{ __('setting.value') }}</th>
                      <th>{{ __('setting.create_by') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                  @foreach ($settings as $key => $setting)
                    <tr id="tr_{{ Hashids::encode($setting->id) }}">
                      <td>{{ ++$key }}</td>
                      <td>{{ $setting->groups }}</td>
                      <td>{{ $setting->options }}</td>
                      <td>{{ $setting->value }}</td>
                      <td>{{ $setting->uname }}</td>
                      <td>
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal{{$setting->id}}"><i class="fas fa-eye"></i></button>
                          <a href="{{ route('settings.edit', Hashids::encode($setting->id)) }}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('setting.group') }}</th>
                      <th>{{ __('setting.options') }}</th>
                      <th>{{ __('setting.value') }}</th>
                      <th>{{ __('setting.create_by') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
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
		@foreach($settings as $setting)
      	<div class="modal fade"id="exampleModal{{$setting->id}}">
      	  <div class="modal-dialog modal-dialog-scrollable">
      	    <div class="modal-content">
				<div class="modal-body">
					<!-- Profile Image -->
					<div class="card card-primary card-outline">
						<div class="card-body box-profile">
							<h3 class="profile-username text-center">{{ $setting->value }}</h3>
							<p class="text-muted text-center">{{ $setting->groups }}</p>
                			<hr>
							<strong><i class="fas fa-list mr-1"></i> {{ $setting->options }}</strong>
							<hr>
							<strong><i class="fas fa-user mr-1"></i> {{ $setting->uname }}</strong>
                			<hr>
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.card -->
      	    	</div>   	    
			</div>
      	    <!-- /.modal-content -->
      	  </div>
      	  <!-- /.modal-dialog -->
      	</div>
    	@endforeach
      <!-- /.modal -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
</x-admin-layout><script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<script type = "text/javascript" >
    $(document).ready(function() {
        $('#checkboxesMain').on('click', function(e) {
            if ($(this).is(':checked', true)) {
                $(".checkbox").prop('checked', true);
            } else {
                $(".checkbox").prop('checked', false);
            }
        });
        $('.checkbox').on('click', function() {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#checkboxesMain').prop('checked', true);
            } else {
                $('#checkboxesMain').prop('checked', false);
            }
        });
        $('.removeAll').on('click', function(e) {
            var studentIdArr = [];
            $(".checkbox:checked").each(function() {
                studentIdArr.push($(this).attr('data-id'));
            });
            if (studentIdArr.length <= 0) {
                alert("Choose min one item to remove.");
            } else {
                if (confirm("Are you sure?")) {
                    var stuId = studentIdArr.join(",");
                    $.ajax({
                        url: "{{url('dashboard/deleteallsettings')}}",
                        type: 'DELETE',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: 'ids=' + stuId,
                        success: function(data) {
                            if (data['status'] == true) {
                                $(".checkbox:checked").each(function() {
                                    $(this).parents("tr").remove();
                                });
                                alert(data['message']);
                            } else {
                                alert('Error occured.');
                            }
                        },
                        error: function(data) {
                            alert(data.responseText);
                        }
                    });
                }
            }
        });
    }); 
 </script>


<script type="text/javascript">
      
    $(document).ready(function () {
       
       /* When click show user */
        $('body').on('click', '#editCompany', function () {
          var userURL = $(this).data('url');
          $.get(userURL, function (data) {
              $('#umodal-default').modal('show');
              $('#user-id').text(data.id);
              $('#user-name').text(data.name);
              $('#user-email').text(data.email);
          })
       });
       
    });
</script>

<script type="text/javascript">
 
     $('.show_confirm').click(function(event) {
          var form =  $(this).closest("form");
          var name = $(this).data("name");
          event.preventDefault();
          swal({
              title: `{{ __('general.delete_1') }}`,
              text: "{{ __('general.delete_2') }}",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              form.submit();
            }
          });
      });
  
</script>
