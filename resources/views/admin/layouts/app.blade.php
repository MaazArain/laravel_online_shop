<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title' , 'Admin Dashboard')</title>
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Google Font: Source Sans Pro -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/fontawesome-free/css/all.min.css')}}">
		{{-- Dropzone CSS --}}
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.css')}}">
		{{-- SummerNote css --}}
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/summernote/summernote-bs4.min.css')}}">
		<!-- Theme style -->
		<link rel="stylesheet" href="{{asset('admin-assets/css/adminlte.min.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/datetimepicker.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/css/custom.css')}}">
		<link rel="stylesheet" href="{{asset('admin-assets/plugins/select2/css/select2.min.css')}}">
		@yield('customCSS')
	</head>
	<body class="hold-transition sidebar-mini">
		<!-- Site wrapper -->
		<div class="wrapper">
			@include('admin.layouts.navbar')
			<!-- Main Sidebar Container -->
			@include('admin.layouts.sidebar')
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				@yield('content')
			</div>
			<!-- /.content-wrapper -->
            @include('admin.layouts.footer')

		</div>
		<!-- ./wrapper -->
		<!-- jQuery -->
		<script src="{{asset('admin-assets/plugins/jquery/jquery.min.js')}}"></script>
		<!-- Bootstrap 4 -->
        <script src="{{asset('admin-assets/js/datetimepicker.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('admin-assets/plugins/summernote/summernote-bs4.min.js')}}"></script>

		<!-- Bootstrap 4 Select js-->
		<script src="{{asset('admin-assets/plugins/select2/js/select2.min.js')}}"></script>

		<!--  Dropzone -->
		<script src="{{asset('admin-assets/plugins/dropzone/min/dropzone.min.js')}}"></script>

		<!-- AdminLTE App -->
		<script src="{{asset('admin-assets/js/adminlte.min.js')}}"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="{{asset('admin-assets/js/demo.js')}}"></script>
		<script type="text/javascript">
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
        $(document).ready(function(){
            $('#starts_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
             $('#expires_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });
	</script>
		@yield('customJs')
	</body>
</html>
