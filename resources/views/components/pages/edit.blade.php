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
                <h3 class="card-title">Update Pages <small>{{$pages->value}}</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="POST" enctype="multipart/form-data" action="{{ route('pages.update', $pages->id) }}" >
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
                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $pages->title) }}" require>
                  </div>
                  <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="summernote" id="content" name="content">{{ old('content', $pages->content) }}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="seotitle">Seotitle</label>
                    <input type="text" name="seotitle"  class="form-control" id="seotitle" class="seotitle" value="{{ old('seotitle', $pages->seotitle) }}" require>
                  </div>
                  <div class="form-group">
                  @if(Storage::exists('public/images/'.$pages->picture))
                    <label for="content">Picture</label>
                    <div class="filtr-item col-sm-2" data-category="1" data-sort="white sample">
                      <a href="{{ Storage::url('public/images/').$pages->picture }}" data-toggle="lightbox" data-title="{{ $pages->picture }}">
                        <img src="{{ Storage::url('public/images/').$pages->picture }}" class="img-fluid mb-2" alt="{{ $pages->picture }}"/>
                      </a>
                    </div>
                  @else
                    <label for="content">No image exists on storage</label>
                  @endif
                  </div>
                  <div class="form-group">
                    <label for="picture">Select Images</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="picture" name="picture">
                        <label class="custom-file-label" for="picture">{{ old('picture', $pages->picture) }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="row">
                      <div class="col-md-2">
                        <label for="image">Active</label>
                      </div>
                      <div class="col-md-10">
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="radioPrimary1" value="Y" name="active" {{ $pages->active == 'Y' ? 'checked' : ''}}>
                          <label for="radioPrimary1">Y</label>
                        </div>
                        <div class="icheck-primary d-inline">
                          <input type="radio" id="radioPrimary2" value="N" name="active" {{ $pages->active == 'N' ? 'checked' : ''}}>
                          <label for="radioPrimary2">N</label>
                        </div>
                      </div>
                    </div>
                  </div>
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
    
</x-admin-layout>