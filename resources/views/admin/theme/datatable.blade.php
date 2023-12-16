<x-admin-layout>
@section('title', __('theme.datatable_title'))

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
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.themes') }}</a></li>
              <li class="breadcrumb-item active">{{ __('theme.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('theme.datatable_list') }}</h3>
                <div class="card-tools">
                  <button onclick="location.href='{{ route('themes.install') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
				          @foreach($themes as $theme)
                  <div class="col-sm-4">
                    <div class="position-relative" style="min-height: 180px;">
                      <img src="{{ asset('frontend/'.$theme->folder.'/preview.jpg') }}" alt="Photo 3" class="img-fluid">
                      <div class="ribbon-wrapper ribbon-xl">
                        <div class="ribbon bg-danger text-xl">
      									{{ $theme->title }}
                        </div>
                      </div>
                    </div>
                    <div class="card-body text-center">
                      <form method="POST" action="{{ route('themes.destroy', Hashids::encode($theme->id)) }}">@csrf  
                        @if($theme->active == 'N')
                        <a href="{{ route('themes.active', Hashids::encode($theme->id)) }}" class="btn btn-sm btn-info"><i class="fas fa-check"></i></a>
                        @endif
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#exampleModal{{$theme->id}}"><i class="fas fa-eye"></i></button>
                        <a href="{{ route('themes.edit', Hashids::encode($theme->id)) }}" class="btn btn-sm btn-warning"><i class="fas fa-pen"></i></a>
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="title" type="hidden" value="{{ $theme->title }}">
                        <button type="submit" class="btn btn-sm btn-danger delete_confirm" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
                      </form>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
		  @foreach($themes as $theme)
      	<div class="modal fade"id="exampleModal{{$theme->id}}">
      	  <div class="modal-dialog modal-dialog-scrollable">
      	    <div class="modal-content">
              <div class="modal-body">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                  <div class="card-body box-profile">
                    <strong><i class="fas fa-spell-check mr-1"></i> {{ $theme->title }}</strong>
                    <hr>
                    <strong><i class="fas fa-user-edit mr-1"></i> {{ $theme->author }}</strong>
                    <hr>
                    <strong><i class="fas fa-folder mr-1"></i> {{ $theme->folder }}</strong>
                    <hr>
                    <strong><i class="fas fa-check mr-1"></i> {{ $theme->active }}</strong>
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
                        url: "{{url('dashboard/deleteallusers')}}",
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
     $('.delete_confirm').click(function(event) {
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
