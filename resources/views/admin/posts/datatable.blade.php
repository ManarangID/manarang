<x-admin-layout>
@section('title', __('post.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.posts') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">{{ __('general.posts') }}</a></li>
              <li class="breadcrumb-item active">{{ __('post.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('post.datatable_list') }}</h3>
				<div class="card-tools">
					<button onclick="location.href='{{ route('posts.create') }}'" type="button" class="btn btn-primary"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th style="text-align:center;" width="25">{{ __('general.no') }}</th>
                      <th>{{ __('post.category') }}</th>
                      <th>{{ __('post.title') }}</th>
                      <th>{{ __('post.type') }}</th>
                      <th>{{ __('post.headline') }}</th>
                      <th>{{ __('post.active') }}</th>
                      <th style="text-align:center;" width="140">{{ __('general.actions') }}</th>
                    </tr>
                  </thead>
                  <!-- Table content -->
                  <tbody>
                    @if($posts->count())
                    @foreach ($posts as $key => $post)
                    <tr id="tr_{{ $post->id }}">
                      <td><input type="checkbox" class="checkbox" data-id="{{ $post->id }}"></td>
                      <td>{{ ++$key }}</td>
                      <td>{{ $post->ctitle }}</td>
                      <td>{{ $post->title }}<br /><p><code><a href="{{ url('/detailpost/'.$post->seotitle)}}" target="_blank">{{ url('/detailpost/'.$post->seotitle)}}</a></code></p></td>
                      <td>{{ $post->type }}</td>
                      <td>{{ $post->headline }}</td>
                      <td>{{ $post->active }}</td>
                      <td>
                          <form action="{{ route('posts.sendSubscriber', Hashids::encode($post->id)) }}" method="POST" id="delete-form-{{ $post->id }}" style="display: none;">
                                {{csrf_field()}}
                                <input type="hidden" value="{{ $post->id }}" name="id">
                          </form>
                          <form method="POST" action="{{ route('posts.destroy', Hashids::encode($post->id)) }}">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-xl-{{ $post->id }}" title="{{ __('general.show') }}"><i class="fas fa-eye"></i></button>
                            <button type="submit" class="btn btn-sm btn-warning" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $post->id }}').submit();" title="{{ __('general.subscribes') }}" {{ $post->subscribe == 'Y' ? 'disabled' : '' }}><i class="fas fa-envelope"></i></button>
                            <a href="{{ route('posts.edit', Hashids::encode($post->id)) }}" class="btn btn-sm btn-info" title="{{ __('general.edit') }}"><i class="fas fa-pen"></i></a>
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
		  @foreach($posts as $post)
      <div class="modal fade" id="modal-xl-{{ $post->id }}">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">{{ $post->title }}</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Post -->
              <div class="post">
                <div class="user-block">
                  @if ($post->profile != null)
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('public/profile-photos/'.$post->profile) }}" alt="{{ $post->uname }}">
                  @else
                  <img class="img-circle img-bordered-sm" src="{{ Storage::url('images/logo.png') }}" alt="{{ $post->uname }}">
                  @endif
                  <span class="username">
                    <a href="#">{{ $post->uname }}</a>
                  </span>
                  <span class="description">
                  {{ date('d F y H:i', strtotime($post->created_at)) }}
                  /
                  {{ __('post.category') }} : {{ $post->category->title }}
                  /
                  {{ $post->active == 'Y' ? __('post.active') : __('post.deactive') }}
                  /
                  {{ __('post.headline') }} : {{ $post->headline == 'Y' ? __('general.yes') : __('general.no') }}
                  /
                  {{ __('post.comment') }} : {{ $post->comment == 'Y' ? __('post.active') : __('post.deactive') }}
                  </span>
                </div>
                <!-- /.user-block -->
                @if($post->picture != '')
                <div class="col-sm-12">
                  <img class="img-fluid" src="{{ getPicturepost($post->picture, '', $post->updated_by) }}" alt="{{ $post->picture_description }}">
                </div>
                <!-- /.col -->
                @endif
                {!! $post->content !!}
                <p>
                {{ __('general.tags') }} : {{ str_replace(',', ', ', $post->tag) }}
                </p>
              </div>
              <!-- /.Post -->
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
                        url: "{{url('dashboard/deleteallposts')}}",
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
