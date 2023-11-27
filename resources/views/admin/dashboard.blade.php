<x-admin-layout>
@section('title', __('general.dashboard'))
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ __('general.dashboard') }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">{{ __('dashboard.welcome_text') }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <!-- Notification & Welcome content -->
        <div class="row">
          <div class="col-12">
            <div class="callout callout-info">
              <h5><i class="fas fa-home"></i> {{ __('dashboard.welcome') }}</h5>
              {{ __('dashboard.welcome_to') }} {{ config('app.name') }}. {{ __('dashboard.please_click') }}
            </div>
          </div>

          @if($commentunread > 0)
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-none">
              <span class="info-box-icon bg-info"><i class="fas fa-comments"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('dashboard.notifications') }}</span>
                <span class="info-box-number">{{ __('dashboard.notif_comment', ['count' => $commentunread]) }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          @endif

          @if($contactunread > 0)
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box shadow-none">
              <span class="info-box-icon bg-info"><i class="fas fa-comments"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">{{ __('dashboard.notifications') }}</span>
                <span class="info-box-number">{{ __('dashboard.notif_contact', ['count' => $contactunread]) }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          @endif
        </div>

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  {{ __('dashboard.items') }}
                </h3>
              </div><!-- /.card-header -->
              <div class="card-body ">
                <div class="tab-content p-0">
                  <div class="row">
                    <!-- Left col -->
                    <div class="col-md-6">
                    
                      <div class="col-12">
                        <div class="info-box shadow-none">
                          <span class="info-box-icon bg-info"><i class="fas fa-file"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_pages') }}</span>
                            <span class="info-box-number">{{ $pages }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow-sm">
                          <span class="info-box-icon bg-success"><i class="fas fa-box-open"></i></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_components') }}</span>
                            <span class="info-box-number">{{ $components }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow">
                          <span class="info-box-icon bg-warning"><i class="fab fa-themeco"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_themes') }}</span>
                            <span class="info-box-number">{{ $theme }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow-lg">
                          <span class="info-box-icon bg-danger"><i class="fas fa-user-cog"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_users') }}</span>
                            <span class="info-box-number">{{ $user }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>

                    <!-- Right col -->
                    <div class="col-md-6">
                    
                      <div class="col-12">
                        <div class="info-box shadow-none">
                          <span class="info-box-icon bg-info"><i class="fas fa-newspaper"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_posts') }}</span>
                            <span class="info-box-number">{{ $post }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow-sm">
                          <span class="info-box-icon bg-success"><i class="fas fa-folder-open"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_categories') }}</span>
                            <span class="info-box-number">{{ $category }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow">
                          <span class="info-box-icon bg-warning"><i class="fas fa-tags"></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_tags') }}</span>
                            <span class="info-box-number">{{ $tag }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                      <div class="col-12">
                        <div class="info-box shadow-lg">
                          <span class="info-box-icon bg-danger"><i class="fas fa-comments"></i></i></span>

                          <div class="info-box-content">
                            <span class="info-box-text">{{ __('dashboard.total_comments') }}</span>
                            <span class="info-box-number">{{ $comment }} {{ __('dashboard.items') }}</span>
                          </div>
                          <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                      </div>
                      <!-- /.col -->
                    </div>
                  
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-6 connectedSortable">

            <!-- Map card -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-grin-stars mr-1"></i>
                  {{ __('dashboard.popular_posts') }}
                </h3>
                <!-- card tools -->
              </div>
              <div class="card-body">
                <ul class="products-list product-list-in-card pl-2 pr-2">
                  @foreach($populars as $popular)
                  <li class="item">
                    @if($popular->picture != '')
                    <div class="product-img">
                      <a href="{{ url('detailpost/'.$popular->seotitle) }}"><img src="{{ Storage::url('post/'.$popular->picture) }}" alt="{{ $popular->title }}" class="img-size-50"></a>
                    </div>
                    @endif
                    <div class="product-info">
                      <a href="{{ url('category/'.$popular->category->seotitle) }}" class="product-title">{{ $popular->category->title }}
                        <span class="badge badge-warning float-right">{{ date('d F y H:i', strtotime($popular->created_at)) }} - ({{ $popular->hits.' '.__('post.seen') }})</span></a>
                      <span class="product-description">
                        {{ $popular->title }}
                      </span>
                      <p class="tx-color-03 tx-13 mg-b-0"></p>
                    </div>
                  </li>
                  <!-- /.item -->
                  @endforeach
                </ul>
              </div>
              <!-- /.card-body-->
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</x-admin-layout>