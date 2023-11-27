<x-admin-layout>
@section('title', __('component.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.components') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('components.index') }}">{{ __('general.component') }}</a></li>
              <li class="breadcrumb-item active">{{ __('components.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('component.datatable_list') }}</h3>
				<div class="card-tools">
					<button onclick="location.href='{{ route('components.create') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('component.title') }}</th>
                      <th>{{ __('component.author') }}</th>
                      <th>{{ __('component.folder') }}</th>
                      <th>{{ __('component.type') }}</th>
                      <th>{{ __('component.active') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                        @if($components->count())
                  @foreach ($components as $key => $component)
                    <tr id="tr_{{ $component->id }}">
                      <td><input type="checkbox" class="checkbox" data-id="{{ $component->id }}"></td>
                      <td>{{ ++$key }}</td>
                      <td>{{ $component->title }}</td>
                      <td>{{ $component->author }}</td>
                      <td>{{ $component->folder }}</td>
                      <td>{{ $component->type }}</td>
                      <td>{{ $component->active }}</td>
                      <td>
                          <form method="POST" action="{{ route('components.destroy', Hashids::encode($component->id)) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal{{$component->id}}"><i class="fas fa-eye"></i></button>
                            <a href="{{ route('components.edit', Hashids::encode($component->id)) }}" class="btn btn-sm btn-info"><i class="fas fa-pen"></i></a>
                            <button type="submit" class="btn btn-sm btn-danger show_confirm" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
                          </form>
                      </td>
                    </tr>
                  @endforeach
                  @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="width:10px;" style="text-align:center;"><input type="checkbox" id="checkboxesMain"></td>
                      <td colspan="7">
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
		@foreach($components as $component)
      	<div class="modal fade"id="exampleModal{{$component->id}}">
      	  <div class="modal-dialog modal-dialog-scrollable">
      	    <div class="modal-content">
				<div class="modal-body">
					<!-- Profile Image -->
					<div class="card card-primary card-outline">
						<div class="card-body box-profile">
							<h3 class="profile-username text-center">{{ $component->title }}</h3>
							<p class="text-muted text-center">{{ $component->author }}</p>
                			<hr>
							<strong><i class="fas fa-envelope mr-1"></i> {{ $component->folder }}</strong>
							<hr>
							<strong><i class="fas fa-mobile-alt mr-1"></i> {{ $component->type }}</strong>
                			<hr>
							<strong><i class="fas fa-user-shield mr-1"></i> {{ $component->active }}</strong>
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
                        url: "{{url('deleteallcomponents')}}",
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
