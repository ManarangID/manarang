<x-admin-layout>

  <!-- Content Wrapper. Contains album content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Pages</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
              <li class="breadcrumb-item active">pages</li>
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
                <h3 class="card-title ">Please customize your website settings </h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm">
                    <div class="input-group-append">
                      <button onclick="location.href='{{ route('albums.create') }}'" type="button" class="btn btn-default bg-success">
                        Album <i class="fas fa-plus"></i>
                      </button>
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
                    <th>No</th>
                    <th>Title</th>
                    <th>Active</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($albums as $key => $album)
                  <tr>
                    <td>{{++$key}}</td>
                    <td>{{ $album->title }}</td>
                    <td>{{ $album->active }}</td>
                    <td><a href="{{ route('albums.show', $album->seotitle) }}"  target="_blank" class="btn btn-sm btn-primary"><i class="far fa-newspaper"></i></a>
                        <a href="{{ route('albums.edit', $album->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                        <a href="#" class="btn btn-sm btn-danger" onclick="
                          event.preventDefault();
                          if (confirm('Do you want to remove this?')) {
                            document.getElementById('delete-row-{{ $album->id }}').submit();
                          }">
                          <i class="fas fa-trash"></i>
                          </a>
                        <form id="delete-row-{{ $album->id }}" action="{{ route('albums.destroy', ['id' => $album->id]) }}" method="POST">
                          <input type="hidden" name="_method" value="DELETE">
                          @csrf
                        </form>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>No</th>
                    <th>Title</th>
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