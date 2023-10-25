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
                <h3 class="card-title">Edit posts <small></small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm" method="POST" enctype="multipart/form-data" action="{{ route('posts.update', $posts->id) }}">
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

                    @if (session('success'))
                      <div class="alert alert-success alert-dismissible">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                      <div class="alert alert-danger alert-dismissible">{{ session('error') }}</div>
                    @endif
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group">
                        <label for="Title">Title</label>
                        <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $posts->title) }}" require>
                      </div>
                      <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="summernote" rows="6" name="content">{{ old('content', $posts->content) }}</textarea>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="seotitle">Seotitle</label>
                        <input type="text" name="seotitle"  class="form-control" id="seotitle" class="seotitle" value="{{ old('seotitle', $posts->seotitle) }}" require>
                      </div>
                      <div class="form-group">
                        <label for="tag">Category</label>
                        <select class="form-control" name="category_id">
                        @foreach ($categories as $category)
                            <option @selected($category->id == $posts->category_id) value="{{ $category->id }}">{{ strtoupper($category->title) }}</option>
                        @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" rows="3" name="meta_description" id="meta_description">{{ old('meta_description', $posts->meta_description) }}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="tag">Tag</label>
                        <input type="text" name="tag"  class="form-control" id="tag" value="{{ old('tag', $posts->tag) }}">
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tag">Type</label>
                            <select class="form-control" name="type">
                              <option id="general" value="general" @selected($posts->type == 'general')>General</option>
                              <option id="pagination" value="pagination" @selected($posts->type == 'pagination')>Pagination</option>
                              <option id="picture" value="picture" @selected($posts->type == 'picture')>Picture</option>
                              <option id="video" value="video" @selected($posts->type == 'picture')>Video</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tag">Active</label>
                            <select class="form-control" name="active">
                              <option id="activeY" value="Y" @selected($posts->active == 'Y')>Active</option>
                              <option id="activeN" value="N" @selected($posts->active == 'N')>Draft</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tag">Headline</label>
                            <select class="form-control" name="headline">
                              <option id="headlineY" value="Y" @selected($posts->headline == 'Y')>Yes</option>
                              <option id="headlineN" value="N" @selected($posts->headline == 'N')>No</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tag">Comment</label>
                            <select class="form-control" name="comment">
                              <option id="commentY" value="Y" @selected($posts->comment == 'Y')>Active</option>
                              <option id="commentN" value="N" @selected($posts->comment == 'N')>Deactive</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="picture">Picture</label>
                        <div class="input-group">
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="picture" name="picture">
                            <label class="custom-file-label" for="picture">Choose file</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="picture_description">Picture Description</label>
                        <textarea class="form-control" rows="3" id="picture_description" name="picture_description">{{ old('picture_description', $posts->picture_description) }}</textarea>
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
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
    
</x-admin-layout>