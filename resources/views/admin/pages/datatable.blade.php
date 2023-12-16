<x-admin-layout>
@section('title', __('pages.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.pages') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('pages.index') }}">{{ __('general.pages') }}</a></li>
              <li class="breadcrumb-item active">{{ __('pages.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('pages.datatable_list') }}</h3>
                <div class="card-tools">
                  <button onclick="location.href='{{ route('pages.create') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('pages.title') }}</th>
                      <th>{{ __('pages.active') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                    @if($pages->count())
                    @foreach ($pages as $key => $page)
                    <tr id="tr_{{ $page->id }}">
                      <td><input type="checkbox" class="checkbox" data-id="{{ $page->id }}"></td>
                      <td>{{ ++$key }}</td>
                      <td>{{ $page->title }}<br /><p><code><a href="{{ url('/pages/'.$page->seotitle)}}" target="_blank">{{ url('/pages/'.$page->seotitle)}}</a></code></p></td>
                      <td>{{ $page->active }}</td>
                      <td>
                          <form method="POST" action="{{ route('pages.destroy', Hashids::encode($page->id)) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-xl-{{ $page->id }}" title="{{ __('general.show') }}"><i class="fas fa-eye"></i></button>
                            <a href="{{ route('pages.edit', Hashids::encode($page->id)) }}" class="btn btn-sm btn-info" title="{{ __('general.edit') }}"><i class="fas fa-pen"></i></a>
                            <button type="submit" class="btn btn-sm btn-danger delete_confirm" data-toggle="tooltip" title="{{ __('general.delete') }}"><i class="fas fa-trash"></i></button>
                          </form>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="width:10px;" style="text-align:center;"><input type="checkbox" id="checkboxesMain"></td>
                      <td colspan="4">
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
		  @foreach($pages as $page)
      <div class="modal fade" id="modal-xl-{{ $page->id }}">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ $page->title }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- pages -->
              <div class="post">
                <div class="user-block">
                  @if ($page->profile != null)
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('public/profile-photos/'.$page->profile) }}" alt="{{ $page->uname }}">
                  @else
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('images/logo.png') }}" alt="{{ $page->uname }}">
                  @endif
                  <span class="username">
                    <a href="#">{{ $page->uname }}</a>
                  </span>
                  <span class="description">
                  {{ date('d F y H:i', strtotime($page->created_at)) }}
                  /
                  {{ $page->active == 'Y' ? __('pages.active') : __('pages.deactive') }}
                  </span>
                </div>
                <!-- /.user-block -->
                @if($page->picture != '')
                <div class="col-sm-12">
                  <img class="img-fluid" src="{{ getPicturepages($page->picture, '', $page->updated_by) }}" alt="{{ $page->title }}">
                </div>
                <!-- /.col -->
                @endif
                {!! $page->content !!}
              </div>
              <!-- /.pages -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                        url: "{{url('dashboard/deleteallpages')}}",
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
