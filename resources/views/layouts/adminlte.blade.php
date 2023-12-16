<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - {{ config('app.name') }}</title>
        <link rel="icon" href="{{ Storage::url('images/favicon.png') }}">
          <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/fontawesome-free/css/all.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('AdminLTE/dist')}}/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/toastr/toastr.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/summernote/summernote-bs4.min.css">  <!-- CodeMirror -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/codemirror/codemirror.css">
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/codemirror/theme/monokai.css">
        <!-- SimpleMDE -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/simplemde/simplemde.min.css">
        <!-- Ekko Lightbox -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/ekko-lightbox/ekko-lightbox.css">
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
        <!-- daterange picker -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/daterangepicker/daterangepicker.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/select2/css/select2.min.css">
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <!-- BS Stepper -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/bs-stepper/css/bs-stepper.min.css">
        <!-- dropzonejs -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/dropzone/min/dropzone.min.css">
        <!-- Toastr -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet" />
        <!-- flag-icon-css -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/flag-icon-css/css/flag-icon.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Nestable -->
        <link rel="stylesheet" href="{{asset('AdminLTE/plugins')}}/nestable/jquery.nestable.css"/>
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">


            <!-- Main Sidebar Container -->
            <livewire:admin-menu />
            <!-- End Main Sidebar Container -->
			
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
            {{ $slot }}

			</div>
  			<!-- /.content-wrapper -->

            <livewire:admin-footer />

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="{{asset('AdminLTE/plugins')}}/jquery/jquery.min.js"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('AdminLTE/plugins')}}/jquery-ui/jquery-ui.min.js"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
        $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('AdminLTE/plugins')}}/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('AdminLTE/plugins')}}/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('AdminLTE/dist')}}/js/adminlte.js"></script>
        <!-- SweetAlert2 -->
        <script src="{{asset('AdminLTE/dist')}}/sweetalert2/sweetalert2.min.js"></script>
        <!-- Toastr -->
        <script src="{{asset('AdminLTE/dist')}}/toastr/toastr.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="{{asset('AdminLTE/plugins')}}/datatables/jquery.dataTables.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/jszip/jszip.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/pdfmake/pdfmake.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/pdfmake/vfs_fonts.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- bs-custom-file-input -->
        <script src="{{asset('AdminLTE/plugins')}}/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!-- Summernote -->
        <script src="{{asset('AdminLTE/plugins')}}/summernote/summernote-bs4.min.js"></script>
        <!-- Ekko Lightbox -->
        <script src="{{asset('AdminLTE/plugins')}}/ekko-lightbox/ekko-lightbox.min.js"></script>
        <!-- Filterizr-->
        <script src="{{asset('AdminLTE/plugins')}}/filterizr/jquery.filterizr.min.js"></script>
        <!-- Select2 -->
        <script src="{{asset('AdminLTE/plugins')}}/select2/js/select2.full.min.js"></script>
        <!-- Bootstrap4 Duallistbox -->
        <script src="{{asset('AdminLTE/plugins')}}/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
        <!-- InputMask -->
        <script src="{{asset('AdminLTE/plugins')}}/moment/moment.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/inputmask/jquery.inputmask.min.js"></script>
        <!-- date-range-picker -->
        <script src="{{asset('AdminLTE/plugins')}}/daterangepicker/daterangepicker.js"></script>
        <!-- bootstrap color picker -->
        <script src="{{asset('AdminLTE/plugins')}}/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{asset('AdminLTE/plugins')}}/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
        <!-- Bootstrap Switch -->
        <script src="{{asset('AdminLTE/plugins')}}/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <!-- BS-Stepper -->
        <script src="{{asset('AdminLTE/plugins')}}/bs-stepper/js/bs-stepper.min.js"></script>
        <!-- dropzonejs -->
        <script src="{{asset('AdminLTE/plugins')}}/dropzone/min/dropzone.min.js"></script>
        <!-- jquery-validation -->
        <script src="{{asset('AdminLTE/plugins')}}/jquery-validation/jquery.validate.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/jquery-validation/additional-methods.min.js"></script>
        <script src="{{asset('AdminLTE/plugins')}}/nestable/jquery.nestable.js"></script>
        <!-- Toastr -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<script>
		$.widget.bridge('uibutton', $.ui.button)
		</script>
		<!-- ChartJS -->
		<script src="{{asset('AdminLTE/plugins')}}/chart.js/Chart.min.js"></script>
		<!-- Sparkline -->
		<script src="{{asset('AdminLTE/plugins')}}/sparklines/sparkline.js"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="{{asset('AdminLTE/dist')}}/js/pages/dashboard.js"></script>
        @stack('scripts')
        <script>
			$(document).ready(function() {
				toastr.options.timeOut = 10000;
				@if (Session::has('error'))
					toastr.error('{{ Session::get('error') }}');
				@elseif(Session::has('success'))
					toastr.success('{{ Session::get('success') }}');
				@endif
			});

		</script>
        <script>
        $(function () {
        bsCustomFileInput.init();
        });
        </script>
        
        <script>
        $(function () {
            // Summernote
            $('#content').summernote({
            height: 500,
            focus: true
            })

            // CodeMirror
            CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
            });
        })
        </script>

        <script>
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
            });

            $('.filter-container').filterizr({gutterPixels: 3});
            $('.btn[data-filter]').on('click', function() {
            $('.btn[data-filter]').removeClass('active');
            $(this).addClass('active');
            });
        })
        </script>
        <script>
        $(function () {
            $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            });
        });
        </script>
        <!-- Page specific script -->
        <script type="text/javascript">
            $('#title').on('input', function() {
                var permalink;
                permalink = $.trim($(this).val());
                permalink = permalink.replace(/\s+/g,' ');
                $('#seotitle').val(permalink.toLowerCase());
                $('#seotitle').val($('#seotitle').val().replace(/\W/g, ' '));
                $('#seotitle').val($.trim($('#seotitle').val()));
                $('#seotitle').val($('#seotitle').val().replace(/\s+/g, '-'));
                var gappermalink = $('#seotitle').val();
                $('#permalink').html(gappermalink);
            });

            $('#seotitle').on('input', function() {
            var permalink;
            permalink = $(this).val();
            permalink = permalink.replace(/\s+/g,' ');
            $('#seotitle').val(permalink.toLowerCase());
            $('#seotitle').val($('#seotitle').val().replace(/\W/g, ' '));
            $('#seotitle').val($('#seotitle').val().replace(/\s+/g, '-'));
            var gappermalink = $('#seotitle').val();
            $('#permalink').html(gappermalink);
            });
        </script>
    </body>
</html>
