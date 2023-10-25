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
                <h3 class="card-title">Create a new pages <small></small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="POST" enctype="multipart/form-data" action="{{ route('gallerys.store') }}">
                @csrf
                <div class="card-body">
                  @if ($errors->any())
                      <div class="alert alert-danger alert-dismissible">
                        <div class="alert-title"><h4>Whoops!</h4></div>
                          There are some problems with your input.
                          <ul>
                            @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                          </ul>
                      </div> 
                    @endif

                    @if (session('success'))
                      <div class="alert alert-success alert-dismissible">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                      <div class="alert alert-danger alert-dismissible">{{ session('error') }}</div>
                    @endif
                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label for="tag">Category</label>
                        <select class="form-control" name="album_id">
                          @foreach ($albums as $album)
                            <option value="{{ $album->id }}">{{ $album->title }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="Title">Title</label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" require>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="picture">Picture</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="picture" name="picture">
                            <label class="custom-file-label" for="picture">Choose file</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="summernote" name="content">{{ old('content') }}</textarea>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Create</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
    
</x-admin-layout>