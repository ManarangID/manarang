<x-admin-layout>
@section('title', __('gallery.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.gallerys') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('gallerys.index') }}">{{ __('general.gallerys') }}</a></li>
              <li class="breadcrumb-item active">{{ __('gallery.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('gallery.datatable_list') }}</h3>
                <div class="card-tools">
                  <button onclick="location.href='{{ route('albums.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-images"></i> {{ __('general.albums') }}</button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <button style="margin-bottom: 10px" class="btn btn-primary delete_all" data-url="{{ url('deleteallgallerys') }}">Delete All Selected</button>
    
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="50px"><input type="checkbox" id="master"></th>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('gallery.album') }}</th>
                      <th>{{ __('gallery.title') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                    @if($gallerys->count())
                    @foreach ($gallerys as $key => $gallery)
                    <tr id="tr_{{ $gallery->id }}">
                      <td><input type="checkbox" class="sub_chk" data-id="{{$gallery->id}}"></td>
                      <td>{{ ++$key }}</td>
                      <td>{{ $gallery->atitle }}</td>
                      <td>{{ $gallery->title }}</td>
                      <td>
                          <form method="POST" action="{{ route('gallerys.destroy', Hashids::encode($gallery->id)) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-xl-{{ $gallery->id }}" title="{{ __('general.show') }}"><i class="fas fa-eye"></i></button>
                            <a href="{{ route('gallerys.edit', Hashids::encode($gallery->id)) }}" class="btn btn-sm btn-info" title="{{ __('general.edit') }}"><i class="fas fa-pen"></i></a>
                            <button type="submit" class="btn btn-sm btn-danger delete_confirm" data-toggle="tooltip" title="{{ __('general.delete') }}"><i class="fas fa-trash"></i></button>
                          </form>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
		  @foreach($gallerys as $gallery)
      <div class="modal fade" id="modal-xl-{{ $gallery->id }}">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ $gallery->title }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- gallery -->
              <div class="post">
                <div class="user-block">
                  @if ($gallery->profile != null)
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('public/profile-photos/'.$gallery->profile) }}" alt="{{ $gallery->uname }}">
                  @else
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('images/logo.png') }}" alt="{{ $gallery->uname }}">
                  @endif
                  <span class="username">
                    <a href="#">{{ $gallery->uname }}</a>
                  </span>
                  <span class="description">
                  {{ date('d F y H:i', strtotime($gallery->created_at)) }}
                  /
                  {{ $gallery->active == 'Y' ? __('gallery.active') : __('gallery.deactive') }}
                  </span>
                </div>
                <!-- /.user-block -->
                @if($gallery->picture != '')
                <div class="col-sm-12">
                  <img class="img-fluid" src="{{ getPicturegallery($gallery->picture, '', $gallery->updated_by) }}" alt="{{ $gallery->title }}">
                </div>
                <!-- /.col -->
                @endif
                {!! $gallery->content !!}
              </div>
              <!-- /.gallery -->
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
<script type="text/javascript">
    $(document).ready(function () {


        $('#master').on('click', function(e) {
         if($(this).is(':checked',true))  
         {
            $(".sub_chk").prop('checked', true);  
         } else {  
            $(".sub_chk").prop('checked',false);  
         }  
        });


        $('.delete_all').on('click', function(e) {


            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  


            if(allVals.length <=0)  
            {  
                alert("Please select row.");  
            }  else {  


                var check = confirm("Are you sure you want to delete this row?");  
                if(check == true){  


                    var join_selected_values = allVals.join(","); 


                    $.ajax({
                        url: $(this).data('url'),
                        type: 'DELETE',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: 'ids='+join_selected_values,
                        success: function (data) {
                            if (data['success']) {
                                $(".sub_chk:checked").each(function() {  
                                    $(this).parents("tr").remove();
                                });
                                alert(data['success']);
                            } else if (data['error']) {
                                alert(data['error']);
                            } else {
                                alert('Whoops Something went wrong!!');
                            }
                        },
                        error: function (data) {
                            alert(data.responseText);
                        }
                    });


                  $.each(allVals, function( index, value ) {
                      $('table tr').filter("[data-row-id='" + value + "']").remove();
                  });
                }  
            }  
        });


        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            onConfirm: function (event, element) {
                element.trigger('confirm');
            }
        });


        $(document).on('confirm', function (e) {
            var ele = e.target;
            e.preventDefault();


            $.ajax({
                url: ele.href,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    if (data['success']) {
                        $("#" + data['tr']).slideUp("slow");
                        alert(data['success']);
                    } else if (data['error']) {
                        alert(data['error']);
                    } else {
                        alert('Whoops Something went wrong!!');
                    }
                },
                error: function (data) {
                    alert(data.responseText);
                }
            });


            return false;
        });
    });
</script>