
<x-admin-layout>
@section('title', __('401.401_title'))
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-warning">  {{ __('401.401_title') }}</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-warning"></i> {{ __('404.404_text_1') }}</h3>

          <p>
          {{ __('401.401_text_2') }}
          {{ __('401.401_text_3') }}
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
</x-admin-layout>