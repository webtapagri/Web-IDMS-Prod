<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body">
						<div class="card-body text-center">
							<a href="#">
								<img src="{{ (Session::get('user_img') ? Session::get('user_img'):asset('img/user-default.png')) }}" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
							</a>
							<h6 class="mb-0 text-white text-shadow-dark">{{ strtoupper(Session::get('name')) }}</h6>
							<span class="font-size-sm text-white text-shadow-dark">{{ strtoupper(Session::get('role')) }}</span>
						</div>
													
						<div class="sidebar-user-material-footer">
							<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse"><span>Navigation</span></a>
						</div>
					</div>

					<div class="collapse" id="user-nav">
						<ul class="nav nav-sidebar">
							<li class="nav-item">
								<a href="{{ route('profile') }}" class="nav-link">
									<i class="icon-user-plus"></i>
									<span>My profile</span>
								</a>
							</li>
							
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="icon-help"></i>
									<span>Help</span>
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
						<li class="nav-item">
							<a href="../full/index.html" class="nav-link">
								<i class="icon-home4"></i>
								<span>Dashboard</span>
							</a>
						</li>
						@foreach(session('menus') as $row)
							<?php
								$cls = '';
								foreach($row["menu"] as $menu){
									if(@$data['ctree'] == $menu->url){
										$cls = 'nav-item-expanded nav-item-open';
									}
								}
							?>
						<li class="nav-item nav-item-submenu {{ $cls }}">
							<a href="#" class="nav-link"><i class="{{ $row['module_icon'] }}"></i> <span>{{ $row['module'] }}</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Starter kit">
								@foreach($row["menu"] as $menu)
								<li class="nav-item ">
									<a href="{{ url($menu->url) }}" class="nav-link {{ ( @$data['ctree'] == $menu->url ? 'active':'' ) }}">
										<i class="icon-arrow-right5"></i>	{{ $menu->name }}
									</a>
								</li>
								@endforeach
							</ul>
						</li>
						@endforeach
						<!-- /main -->

					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
			
		</div>
		