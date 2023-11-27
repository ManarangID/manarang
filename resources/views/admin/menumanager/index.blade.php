<x-admin-layout>
@section('title', __('menumanager.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.menu_manager') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.menu_manager') }}</a></li>
              <li class="breadcrumb-item active">{{ __('menumanager.datatable_list') }}</li>
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
                <h3 class="card-title">{{ __('menumanager.datatable_list') }} </h3> 
                  <div class="card-tools">
                    <button onclick="location.href='{{ route('menumanager.menutable') }}'" type="button" class="btn btn-outline-primary btn-block"> <i class="fas fa-table"></i> {{ __('menumanager.table') }}</button>
                  </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <p><code>{{ __('menumanager.info_text') }}</code></p>
                <div class="row">
                  <div class="col-md-8">
                    <div class="mg-b-10" id="success-indicator" style="display:none;">
                      <div class="alert alert-success" role="alert">
                        <strong>{{ __('menumanager.info') }}: </strong> {{ __('menumanager.success') }}
                      </div>
                    </div>
                    <div>
                      <input type="hidden" id="nestable-output" />
                      <div class="dd" id="nestable">
                        <ol class="dd-list">
                          @if(count($menu) > 0)
                            @each('admin.menumanager.menu', $menu, 'menu', 'admin.menumanager.menu')
                          @endif
                        </ol>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="card mg-t-20">
                      <div class="card-header bg-gray-200 pd-t-10 pd-b-10">{{ __('menumanager.create_title') }}</div>
                        <form id="createForm" method="POST" enctype="multipart/form-data" action="{{ route('menumanager.store') }}">
                          @csrf
                          <div class="card-body">
                            
                            <div class="form-group">
                              <label>{{ __('menumanager.title') }}</label>
                              <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                              <label>{{ __('menumanager.url') }}</label>
                              <input type="text" class="form-control" id="url" name="url">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                              <label>{{ __('menumanager.class') }}</label>
                              <input type="text" class="form-control" id="class" name="class">
                            </div>
                            <!-- /.form-group -->
                            <div class="form-group">
                            <label>{{ __('menumanager.target') }}</label>
                            <select id="target" name="target" class="form-control select2bs4" style="width: 100%;">
                              <option value="none">none</option>
                              <option value="_blank">blank</option>
                            </select>
                            </div>
                            <!-- /.form-group -->
                          </div>
                          <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block"><i data-feather="send" class="wd-10 mg-r-5"></i> {{ __('general.create') }}</button>
                          </div>
                        </form>
                    </div>
                  </div>
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
	
</x-admin-layout>

<script>
	$(function () {
	  $('#createForm').validate({
	    rules: {
	      title: {
	        required: true,
	      },
	      url: {
	        required: true,
	      },
	      target: {
	        required: true,
	      },
	    },
	    messages: {
	      title:  "Please enter menu title",
	      url:  "Please fill in the url address",
	      target: "Please specify the target menu"
	    },
	    errorElement: 'span',
	    errorPlacement: function (error, element) {
	      error.addClass('invalid-feedback');
	      element.closest('.form-group').append(error);
	    },
	    highlight: function (element, errorClass, validClass) {
	      $(element).addClass('is-invalid');
	    },
	    unhighlight: function (element, errorClass, validClass) {
	      $(element).removeClass('is-invalid');
	    }
	  });
	});
</script>

<script type="text/javascript">
	$(function() {
	    var updateOutput = function(e){
			var list   = e.length ? e : $(e.target),
				output = list.data('output');
			if (window.JSON) {
				output.val(window.JSON.stringify(list.nestable('serialize')));
			} else {
				output.val('JSON browser support required for this demo.');
			}
		};
		
		$('#nestable').nestable().on('change', updateOutput);
		
		updateOutput($('#nestable').data('output', $('#nestable-output')));
		
		$('.dd').on('change', function() {
			$("#success-indicator").hide();
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
			var dataString = { 
				data : $("#nestable-output").val(),
				_token : CSRF_TOKEN
			};
			$.ajax({
				type: 'POST',
				url: '{{ url("menumanager/menusort") }}',
				data: dataString,
				cache : false,
				success: function(data){
          toastr.success('{{ __('menumanager.success') }}');
				}
			});
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