<x-admin-layout>

  <!-- Content Wrapper. Contains posts content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Post</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">post</li>
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
                <h3 class="card-title ">List of posts </h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <div class="input-group-append">
                      <button onclick="location.href='{{ route('posts.create') }}'" type="button" class="btn btn-default bg-success">
                        Add <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Created</th>
                    <th>Headline</th>
                    <th>Active</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($posts as $key => $posts)
                  <tr>
                    <td>{{++$key}}</td>
                    <td>{{ $posts->categoryTitle }}</td>
                    <td>{{ $posts->title }}<br><a href="{{ config('app.url') }}/posts/{{ $posts->seotitle }}" target="_blank">{{ config('app.url') }}/post/{{ $posts->seotitle }}</td>
                    <td>{{ $posts->created_at }}</td>
                    <td>{{ $posts->headline }}</td>
                    <td>{{ $posts->active }}</td>
                    <td><a href="{{ route('posts.subscriber', $posts->id) }}" class="btn btn-sm btn-primary" onclick="event.preventDefault(); document.getElementById('subscriber').submit();">
                          <i class="fas fa-newspaper"></i>
                        </a>

                        <form id="subscriber" action="{{ route('posts.subscriber', $posts->id) }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <a href="{{ route('posts.edit', $posts->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" onclick="
                          event.preventDefault();
                          if (confirm('Do you want to remove this?')) {
                            document.getElementById('delete-row-{{ $posts->id }}').submit();
                          }">
                          <i class="fas fa-trash"></i>
                          </a>
                        <form id="delete-row-{{ $posts->id }}" action="{{ route('posts.destroy', ['id' => $posts->id]) }}" method="POST">
                          <input type="hidden" name="_method" value="DELETE">
                          @csrf
                        </form>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Created</th>
                    <th>Headline</th>
                    <th>Active</th>
                    <th>Actions</th>
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
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
    
</x-admin-layout>