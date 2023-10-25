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
                <h3 class="card-title">Update album <small>{{$albums->title}}</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="POST" enctype="multipart/form-data" action="{{ route('albums.update', $albums->id) }}" >
              @method('PUT')
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
                  <div class="form-group">
                    <label for="Title">Title</label>
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $albums->title) }}" require>
                  </div>
                  <div class="form-group">
                    <label for="seotitle">Seotitle</label>
                    <input type="text" name="seotitle"  class="form-control" id="seotitle" class="seotitle" value="{{ old('seotitle', $albums->seotitle) }}" require>
                  </div>
                  <div class="form-group clearfix">
                    <div class="row">
                      <div class="col-md-2">
                        <label for="image">Active</label>
                      </div>
                      <div class="col-md-10">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="radioPrimary1" value="Y" name="active" {{ $albums->active == 'Y' ? 'checked' : ''}}>
                          <label for="radioPrimary1">Y</label>
                        </div>
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="radioPrimary2" value="N" name="active" {{ $albums->active == 'N' ? 'checked' : ''}}>
                          <label for="radioPrimary2">N</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update</button>
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
    
</x-admin-layout>