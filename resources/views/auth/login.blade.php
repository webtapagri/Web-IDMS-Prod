<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Login - {{ env('APP_NAME') }}</title>

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
	
	<style>
	.login-cover{
		background: url({{ asset('limitless/global_assets/images/backgrounds/user_bg1.png') }}) no-repeat;
		background-size: cover;
	}
	span.help-block {
		color: red !important;
	}
	</style>
	
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

<body>

	<!-- Page content -->
	<div class="page-content login-cover">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login form -->
				<form class="login-form wmin-sm-400" action="{{ url('ldaplogin') }}" method="post">
					{!! csrf_field() !!}
					<div class="card mb-0">
						
						<div class="tab-content card-body">
							<div class="tab-pane fade show active" id="login-tab1">
								<div class="text-center mb-3">
									<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
									<h5 class="mb-0">IDMS Login</h5>
									<span class="d-block text-muted">Login menggunakan user dan password LDAP</span>
								</div>
								
								@if (\Session::has('error'))
									<div class="alert alert-warning no-border">
										<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
										<span class="text-semibold">Error!</span> {{ \Session::get('error') }}
									</div>
								@endif
	
								<div class="form-group form-group-feedback form-group-feedback-left">
									<input type="text" name="username" class="form-control" value="{{ old('email') }}" placeholder="Username">
									<div class="form-control-feedback">
										<i class="icon-user text-muted"></i>
									</div>
									@if ($errors->has('email'))
										<span class="help-block">
											{{ $errors->first('email') }}
										</span>
									@endif
								</div>

								<div class="form-group form-group-feedback form-group-feedback-left">
									<input type="password" name="password" class="form-control" placeholder="Password">
									<div class="form-control-feedback">
										<i class="icon-lock2 text-muted"></i>
									</div>
									@if ($errors->has('password'))
										<span class="help-block">
											{{ $errors->first('password') }}
										</span>
									@endif
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-block">Sign in</button>
								</div>

								
								<span class="form-text text-center text-muted">Recomended Browser Google Chrome / Mozilla Firefox</span>
							</div>
</div>
					</div>
				</form>
				<!-- /login form -->

			</div>
			<!-- /content area -->

		</div>
		<!-- /main content -->

	</div>
	
	<!-- /page content -->
	@yield('theme_js')
	
	@yield('my_script')
	
	@include('layouts.global_script')
</body>
</html>
