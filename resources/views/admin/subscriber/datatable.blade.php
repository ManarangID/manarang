<x-admin-layout>
@section('title', __('subscribe.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.subscribes') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('subscribers.index') }}">{{ __('general.subscribes') }}</a></li>
              <li class="breadcrumb-item active">{{ __('subscribe.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('subscribe.datatable_list') }}</h3>
                <div class="card-tools">
                  <button onclick="location.href='{{ route('subscribers.create') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                  <button onclick="location.href='{{ route('gallerys.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-images"></i> {{ __('general.gallerys') }}</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>SL</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                    @if($subscribers->count())
                    @foreach ($subscribers as $key => $subscriber)
                    <tr id="tr_{{ $subscriber->id }}">
                      <td><input type="checkbox" class="checkbox" data-id="{{ $subscriber->id }}"></td>
                      <td>{{ ++$key }}</td>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $subscriber->email }}</td>
                      <td>{{ $subscriber->status }}</td>
                      <td>
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-xl-{{ $subscriber->id }}" title="{{ __('general.show') }}"><i class="fas fa-envelope"></i></button>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="width:10px;" style="text-align:center;"><input type="checkbox" id="checkboxesMain"></td>
                      <td colspan="5">
                        <!-- <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#alertalldel"><i class="fa fa-trash"></i> {{ __('general.delete_selected') }}</button> -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-default btn-sm removeAll" id="removeAll">
                            <i class="far fa-trash-alt"></i>
                          </button>
                          <a href="" class="btn btn-default btn-sm removeAll" id="removeAll"><i class="far fa-trash-alt"></i></a>
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-reply"></i>
                          </button>
                          <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-share"></i>
                          </button>
                        </div>
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
		  @foreach($subscribers as $subscriber)
      <div class="modal fade" id="modal-xl-{{ $subscriber->id }}">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ __('subscribe.compose') }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <!-- form start -->
            <form id="composeMail" method="POST" enctype="multipart/form-data" action="{{ route('subscribers.compose') }}">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <input class="form-control" value="{{ $subscriber->email }}">
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Subject:" name="subject">
                </div>
                <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" style="height: 300px" name="message"></textarea>
                </div>
                <div class="form-group">
                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Attachment
                    <input type="file" name="attachment">
                  </div>
                  <p class="help-block">Max. 32MB</p>
                </div>
              </div>
              <!-- /.card-body -->
            </form>
            </div>
            <div class="card-footer">
              <div class="float-right">
                <button type="submit" class="btn btn-primary" form="composeMail"><i class="far fa-envelope"></i> Send</button>
              </div>
              <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Discard</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
    	@endforeach
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
                        url: "{{url('dashboard/deleteallsubscribers')}}",
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
