<x-admin-layout>
@section('title', __('contact.reply_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.contacts') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">{{ __('general.contacts') }}</a></li>
              <li class="breadcrumb-item active">{{ __('contact.reply_title') }}</li>
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
                <h3 class="card-title">{{ __('contact.reply_title') }}</h3>
				<div class="card-tools">
					<button  onclick="location.href='{{ route('contacts.index') }}'" type="button" class="btn btn-primary"> <i class="fas fa-arrow-left"></i> {{ __('general.back') }}</button>
				</div>
              </div>
              <!-- /.card-header -->
            	<!-- form start -->
            	<form id="editForm" method="POST" enctype="multipart/form-data" action="{{ route('contacts.postReply', Hashids::encode($contact->id)) }}">
                {{csrf_field()}}
            	  <div class="card-body">
            		<div class="row">
            		  <div class="col-md-12">
					  	<div class="card">
            			  <div class="card-body table-responsive p-0">
                                <input type="hidden" value="{{ $contact->id }}" name="id">
            			    <table class="table table-striped table-valign-middle">
            			      <tbody>
            			      <tr>
            			        <td>{{ __('contact.name') }}</td>
            			        <td>{{ $contact->name }}</td>
            			      </tr>
            			      <tr>
            			        <td>{{ __('contact.email') }}</td>
            			        <td>{{ $contact->email }}</td>
            			      </tr>
            			      <tr>
            			        <td>{{ __('contact.subject') }}</td>
            			        <td>{{ $contact->subject }}</td>
            			      </tr>
            			      <tr>
            			        <td>{{ __('contact.created_at') }}</td>
            			        <td>{{ $contact->created_at->diffForHumans() }}</td>
            			      </tr>
            			      <tr>
            			        <td>{{ __('contact.status') }}</td>
            			        <td>{{ $contact->status == 'Y' ? __('contact.read') : __('contact.unread') }}</td>
            			      </tr>
            			      <tr>
            			        <td>{{ __('contact.message') }}</td>
            			        <td>{{ $contact->message }}</td>
            			      </tr>
            			      <tr>
            			        <td colspan="2">{{ __('contact.reply') }}*<br /><br />
									<div class="form-group">
									<textarea class="p-3" id="content" name="message" require></textarea>
									</div>
									<!-- /.form-group -->
								</td>
            			      </tr>
            			      </tbody>
            			    </table>
            			  </div>
            			</div>
            			<!-- /.card -->
            		  </div>
            		  <!-- /.col -->
            		</div>
            		<!-- /.row -->
            	  </div>
            	  <!-- /.card-body -->
            	  <div class="card-footer">
            	    <button type="submit" class="btn btn-primary"> <i class="fas fa-reply"></i> {{ __('contact.reply') }}</button>
            	  </div>
            	</form>
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

<!-- Page specific script -->
<script>
	$(function () {
	  //Initialize Select2 Elements
	  $('.select2').select2()	

	  //Initialize Select2 Elements
	  $('.select2bs4').select2({
	    theme: 'bootstrap4'
	  })
	})
</script>

<script>
	$(function () {
	  $('#editForm').validate({
	    rules: {
	      title: {
	        required: true,
	      },
	      seotitle: {
	        required: true,
	      },
	    },
	    messages: {
	      title: "Please enter category title",
	      seotitle: "Please enter category seotitle"
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

<script>
    function showImage() {
        return {
            showPreview(event) {
                if (event.target.files.length > 0) {
                    var src = URL.createObjectURL(event.target.files[0]);
                    var preview = document.getElementById("preview");
                    preview.src = src;
                    preview.style.display = "block";
                }
            }
        }
    }
</script>