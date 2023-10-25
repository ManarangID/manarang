<x-admin-layout>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Settings <small>{{$setting->value}}</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="POST"  enctype="multipart/form-data" action="{{ route('settings.update', $setting->id) }}">
              @method('PUT')
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="groups">Groups</label>
                    <input type="text" name="groups" class="form-control" id="groups" value="{{ old('groups', $setting->groups) }}">
                  </div>
                  <div class="form-group">
                    <label for="options">Options</label>
                    <input type="text" name="options" class="form-control" id="options" value="{{ old('options', $setting->options) }}">
                  </div>
                  @if ($setting->id == 15 || $setting->id == 16)
                  <div class="form-group">
                    <label for="value">Value</label>
                    <input type="text" name="value" class="form-control" id="value" value="{{ old('value', $setting->value) }}" readonly>
                  </div>
                  <div class="form-group">
                    <label for="image">Select Images</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image">
                        <label class="custom-file-label" for="image">{{ old('value', $setting->value) }}</label>
                      </div>
                    </div>
                  </div>
                  @else
                  <div class="form-group">
                    <label for="value">Value</label>
                    <input type="text" name="value" class="form-control" id="value" value="{{ old('value', $setting->value) }}">
                  </div>
                  @endif
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-6">

          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>


    <!-- Page specific script -->
<script>
$(function () {
  $.validator.setDefaults({
    submitHandler: function () {
      alert( "Form successful submitted!" );
    }
  });
  $('#quickForm').validate({
    rules: {
      groups: {
        required: true
      },
      options: {
        required: true
      },
      terms: {
        required: true
      },
    },
    messages: {
      groups: {
        required: "Please enter a email address",
        email: "Please enter a valid email address"
      },
      options: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      terms: "Please accept our terms"
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
    
</x-admin-layout>