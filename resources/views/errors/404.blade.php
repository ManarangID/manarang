
<x-admin-layout>
@section('title', __('404.404_title'))
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-warning">  {{ __('404.404_title') }}</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> {{ __('404.404_text_1') }}</h3>

          <p>
          {{ __('404.404_text_2') }}
          {{ __('404.404_text_3') }}
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
</x-admin-layout>