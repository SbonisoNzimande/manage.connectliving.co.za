<!-- aside -->
<aside id="aside" class="app-aside modal fade " role="menu">
	<div class="left">
		<div class="box bg-white">
			<div class="navbar md-whiteframe-z1 no-radius yellow ">
				<!-- brand -->
				<a class="navbar-brand">
					<img src="../public/images/connect_logo.png" style="width: 180px; height: 45px; margin-top:10px;">
					<span class="hidden-folded m-l inline"></span>
				</a>
				<!-- / brand -->
			</div>
			<div class="box-row">
				<div class="box-cell scrollable hover">
					<div class="box-inner">
						<div class="p hidden-folded yellow-50" style="background-image:url(../public/images/bg.png); background-size:cover">
							<div class="rounded w-64 bg-white inline pos-rlt">
								<img src="../public/images/a0.jpg" class="img-responsive rounded">
							</div>
							<a class="block m-t-sm" ui-toggle-class="hide, show" target="#nav, #account">
								<span class="block font-bold"><?=$data['full_name'];?> </span>
								<span class="pull-right auto">
									<i class="fa inline fa-caret-down"></i>
									<i class="fa none fa-caret-up"></i>
								</span>
								<?=$data['email'];?>
							</a>
						</div>
						<div id="nav">
							<div class="input-group m-b">
								<span class="input-group-btn">
									<button class="btn btn-default sort" type="button">Sort</button>
								</span>
								<input type="text" class="form-control search" placeholder="search" data-sort="name" />
							</div>
							<nav ui-nav>
								<ul class="nav list">
									<?=$data['aside_menu'];?>
								</ul>
							</nav>
						</div>
						<div id="account" class="hide m-v-xs">
							<nav>
								<ul class="nav">
									<li>
										<a md-ink-ripple href="Logout">
											<i class="icon mdi-action-exit-to-app i-20"></i>
											<span>Logout</span>
										</a>
									</li>
									<li class="m-v-sm b-b b"></li>
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
			<nav>
				<ul class="nav b-t b">
					<li>
						<a href="http://www.google.com" target="_blank" md-ink-ripple>
							<i class="icon mdi-action-help i-20"></i>
							<span>Help &amp; Feedback</span>
						</a>
					</li>
				</ul>
			</nav>
		</div>

	</div>
</aside>
<!-- / aside -->