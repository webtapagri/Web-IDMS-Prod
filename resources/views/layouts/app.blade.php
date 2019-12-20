<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title') - {{ env('APP_NAME') }}</title>

	<!-- Global stylesheets -->
	<link href="{{ asset('limitless/global_assets/css/css.css?family=Roboto:400,300,100,500,700,900') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/global_assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('limitless/global_assets/css/icons/fontawesome/styles.min.css') }}" rel="stylesheet" type="text/css">

	@yield('theme_css')
	
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	@if(!@$data['alt'])
	<script src="{{ asset('limitless/global_assets/js/main/jquery.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<script src="{{ asset('limitless/global_assets/js/plugins/ui/ripple.min.js') }}"></script>
	@endif
	
	<!-- /core JS files -->

	<!-- Theme JS files -->
	
	@if(!@$data['alt'])
	<script src="{{ asset('limitless/assets/js/app.js') }}"></script>
	@endif
	<!-- /theme JS files -->
</head>

<body class="navbar-top">

	<!-- Main navbar -->
	@include('layouts.header')
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		@include('layouts.sidebar')
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4>@yield('title')</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				@yield('content')
				
			</div>
			<!-- /content area -->


			<!-- Footer -->
			@include('layouts.footer')
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	@yield('theme_js')
	
	@yield('my_script')
	
	@include('layouts.global_script')
</body>
</html>
