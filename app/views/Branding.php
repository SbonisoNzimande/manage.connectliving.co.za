<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Branding</div>
		<!-- / -->
		<!-- Common tools -->
		<ul class="nav navbar-tool pull-right">
			<li>
				<a md-ink-ripple ui-toggle-class="show" target="#search">
					<i class="mdi-action-search i-24"></i>
				</a>
			</li>
			<li>
				<a md-ink-ripple data-toggle="modal" data-target="#user">
					<i class="mdi-social-people-outline i-24"></i>
				</a>
			</li>
			<li class="dropdown">
				<a md-ink-ripple data-toggle="dropdown">
					<i class="mdi-navigation-more-vert i-24"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-scale pull-right pull-up text-color">
					<li><a href>Single-column view</a></li>
					<li><a href>Sort by date</a></li>
					<li><a href>Sort by name</a></li>
					<li class="divider"></li>
					<li><a href>Help &amp; feedback</a></li>
				</ul>
			</li>
		</ul>
		<div class="pull-right" ui-view="navbar@"></div>
		<!-- / -->
		<!-- Search form -->
		<div id="search" class="pos-abt w-full h-full indigo hide">
			<div class="box">
				<div class="box-col w-56 text-center">
					<!-- hide search form -->
					<a md-ink-ripple class="navbar-item inline"  ui-toggle-class="show" target="#search"><i class="mdi-navigation-arrow-back i-24"></i></a>
				</div>
				<div class="box-col v-m">
					<!-- bind to app.search.content -->
					<input class="form-control input-lg no-bg no-border" placeholder="Search" ng-model="app.search.content">
				</div>
				<div class="box-col w-56 text-center">
					<a md-ink-ripple class="navbar-item inline"><i class="mdi-av-mic i-24"></i></a>
				</div>
			</div>
		</div>
		<!-- / -->
	</div>
	<!-- /Content Navbar -->

	<div class="modal fade" id="UploadCompanyLogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload Company Logo</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadCompanyLogoForm" id="UploadCompanyLogoForm" method="POST" action="Branding/UploadCompanyLogo">
						<div id="error_company_logo"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />

						<div class="form-group">
							<label class="col-sm-3 control-label">File</label>
							<div class="col-sm-9">
								<input type="file" name="UploadLogoFile" id="UploadLogoFile" />
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="UploadCLogo"><i class="fa fa-upload"></i>Upload</button>
						</div>
					</form>
					<!-- /Left Column -->
					<!-- Right Column -->
					<div class="col-md-6">
						<div id="scroll-wrap">
							<ul class="timeline ng-scope" ng-class="{'timeline-center': center}" id="timeline_area">
							</ul>
						</div>
					</div>

					<div class="clearfix"></div>



				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="UpdateMarketingLink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Save Marketing Link</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadEstateLogoForm" id="UpdateMarketingLinkForm" method="POST" action="Branding/UploadEstateLogo">
						<div id="error_marketin_link"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />

						<div class="form-group">
							<label class="col-sm-3 control-label">URL</label>
							<div class="col-sm-9">
								<input type="text" name="MarketingURL" id="MarketingURL" />
								
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="SaveMarketLink"><i class="fa fa-upload"></i>Save</button>
						</div>
					</form>
					<div class="clearfix"></div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="UploadEstateLogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload Marketing Image</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadEstateLogoForm" id="UploadEstateLogoForm" method="POST" action="Branding/UploadEstateLogo">
						<div id="error_estate_logo"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />

						<div class="form-group">
							<label class="col-sm-3 control-label">File</label>
							<div class="col-sm-9">
								<input type="file" name="UploadLogoFile" id="UploadLogoFile" />
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="UploadELogo"><i class="fa fa-upload"></i>Upload</button>
						</div>
					</form>
					<!-- /Left Column -->
					<!-- Right Column -->
					

					<div class="clearfix"></div>



				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<!-- Content -->
	<div class="box-row">
		<div class="box-cell">
			<div class="box-inner padding">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />
						<div class="panel-heading b-b b-light">
							You are managing <?=$data['prop_name'];?>
						</div>
						<div class="panel-body b-t b-t-2x">
							<!-- Logo Card -->
							<div class="col-sm-6">
								<div class="card">
									<img src="public/images/connect_living_logo.png" class="w-full r-t" alt="Company Logo" id="imgcompany">
									
									<div class="card-block">
										<h3>Company Logo</h3>
										<p>
											File Name: logo.jpg
										</p>
									</div>

									<div class="card-footer">
										<a href="#" class="btn btn-success btn-sm card-link" data-title="Edit" data-toggle="modal" data-target="#UploadCompanyLogo" data-query-id="206" aria-expanded="false"><span class="mdi-file-file-upload"></span></a>
									</div>
								</div>
							</div>
							<!-- /Logo Card -->

							<!-- Estate Card -->
							<div class="col-sm-6">
								<div class="card">
									<img src="public/images/connect_living_logo.png" class="w-full r-t" alt="Estate Image" id="imgestate">
									
									<div class="card-block">
									<h3>Marketing Image</h3>
										<p>
											File Name: homeImage.jpg
										</p>
										<p>
											Marketing Link: <span id="marketing_link"></span>
										</p>
									</div>

									<div class="card-footer">
										<a href="#" class="btn btn-success btn-sm card-link" data-title="Edit" data-toggle="modal" data-target="#UploadEstateLogo" data-query-id="206" aria-expanded="false"><span class="mdi-file-file-upload"></span></a>
										<a href="#" class="btn btn-info btn-sm card-link" data-title="Edit" data-toggle="modal" data-target="#UploadEstateLogo" data-query-id="206" aria-expanded="false"><span class="mdi-file-file-download"></span></a>

										<a href="#" class="btn btn-warning btn-sm card-link" data-title="Update" data-toggle="modal" data-target="#UpdateMarketingLink" data-query-id="206" aria-expanded="false"><span class="mdi-content-link"></span></a>
									</div>
								</div>
							</div>
							<!-- /Estate Card -->
						</div>
					</div>
				</div>
			</div>

		</div>
		<!-- /Content -->

	</div>
<!-- /content