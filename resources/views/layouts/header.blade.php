<div class="navbar navbar-expand-md navbar-light fixed-top">

		<!-- Header with logos -->
		<div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
			<div class="navbar-brand navbar-brand-md">
				<a href="../full/index.html" class="d-inline-block logo-text">
					{{ config('app.name') }}
				</a>
			</div>
			
			<div class="navbar-brand navbar-brand-xs">
				<a href="../full/index.html" class="d-inline-block logo-text">
					{{ config('app.name') }}
				</a>
			</div>
		</div>
		<!-- /header with logos -->
	

		<!-- Mobile controls -->
		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a href="../full/index.html" class="d-inline-block logo-text-dark">
					{{ config('app.name') }}
				</a>
			</div>	

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>

			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<!-- /mobile controls -->


		<!-- Navbar content -->
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

			</ul>

			<ul class="navbar-nav ml-auto">
				
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
						<img src="{{ (Session::get('user_img') ? Session::get('user_img'):asset('img/user-default.png')) }}" class="rounded-circle" alt="">
						<span>{{ strtoupper(Session::get('name')) }}</span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="{{ route('profile') }}" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
						<!--  
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
						-->
						@if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<')) 
							<a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" class="dropdown-item">
								<i class="icon-switch2"></i> Logout
							</a>
						@else
							<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
							<form id="logout-form" action="{{ url('ldaplogout') }}" method="POST" style="display: none;">
								@if(config('adminlte.logout_method'))
								{{ method_field(config('adminlte.logout_method')) }}
								@endif
								{{ csrf_field() }}
							</form>
						@endif
					</div>
				</li>
			</ul>
		</div>
		<!-- /navbar content -->

	</div>
	