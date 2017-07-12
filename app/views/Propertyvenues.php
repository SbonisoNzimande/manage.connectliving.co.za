<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Property Venues</div>
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
	<!-- /Content Navbar Documentation -->


	

	<div class="modal fade" id="AddVenueModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Venue</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateVenueForm" id="CreateVenueForm" method="POST" action="PropertyVenues/SaveNewVenue">
						<div id="error_save_venue"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="VenueName" class="col-sm-3 control-label">Venue Name</label>
							<div class="col-sm-9">
								<input type="text" name="VenueName" id="VenueName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Days Open</label>
							<div class="col-sm-9">
								<div class="row row-sm">
									<div class="col-sm-4">
										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Monday" name="DaysOpen[]">
												<i></i>
												Monday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Tuesday" name="DaysOpen[]"">
												<i></i>
												Tuesday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Wednesday" name="DaysOpen[]"">
												<i></i>
												Wednesday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Thursday" name="DaysOpen[]"">
												<i></i>
												Thursday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Friday" name="DaysOpen[]"">
												<i></i>
												Friday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Saturday" name="DaysOpen[]"">
												<i></i>
												Saturday
											</label>
										</p>

										<p>
											<label class="ui-checks ui-checks-md">
												<input type="checkbox" value="Sunday" name="DaysOpen[]"">
												<i></i>
												Sunday
											</label>
										</p>
										
									</div>
									

									<div class="col-sm-2">
										<p> <input type="text" name="TimeFrom[Monday]" placeholder="open"  style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Tuesday]" placeholder="open" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Wednesday]" placeholder="open" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Thursday]" placeholder="open" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Friday]" placeholder="open" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Saturday]" placeholder="open" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeFrom[Sunday]" placeholder="open" style="width: 70px;"> </p>
									</div> 
									<div class="col-sm-2">
										<p style="text-align: center; vertical-align: middle;"> - </p>
										<p style="text-align: center;vertical-align: middle;"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
										<p style="text-align: center;vertical-align: middle"> - </p>
									</div> 


									<div class="col-sm-2">
										<p> <input type="text" name="TimeTo[Monday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Tuesday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Wednesday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Thursday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Friday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Saturday][]" placeholder="closed" style="width: 70px;"> </p>
										<p> <input type="text" name="TimeTo[Sunday][]" placeholder="closed" style="width: 70px;"> </p>
									</div> 
								</div>


								

								
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Image</label>
							<div class="col-sm-9">
								<input type="file" name="UploadVenueImageFile" id="UploadVenueImageFile" />
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="SaveVenue"><i class="fa fa-upload"></i>Save</button>
						</div>
					</form>

					<div class="clearfix"></div>

				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	

	

	<div class="modal fade" id="DeleteDocumentTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="DelCategoryID" id="DelCategoryID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Document Type</h4>
				</div>
				<div class="modal-body">
					<div id="delete_doc_type_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteDocumentType">Yes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="deleteID" id="deleteID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Document</h4>
				</div>
				<div class="modal-body">
					<div id="delete_doc_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteDocument">Yes</button>
				</div>
			</div>
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
						<div class="md-whiteframe-z0 bg-white">

							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#tab_venue_list" aria-expanded="true">Property Venue</a>
								</li>
								<!-- <li class="">
									<a href="" data-toggle="tab" data-target="#tab_booking_list" aria-expanded="true">Venue Bookings</a>
								</li> -->

							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="tab_venue_list">

									<div id="toolbar">
										<div class="form-inline" role="form">
											<button id="add_new" type="button" class="btn btn-primary" data-target="#AddVenueModal" data-toggle="modal" >Add New</button>
										</div>
									</div>
									<table 
										data-toggle="table"
										data-url="PropertyVenues/GetAllVenuesTable"
										data-query-params	= "prop_id=<?=$data['prop_id'];?>"
										data-search="true"
										data-show-refresh="true"
										data-show-toggle="true"
										data-show-columns="true"
										data-toolbar="#toolbar"
										id="table-documents"
										class="display table table-striped"
									>
									<thead>
										<tr>
											<th data-field="name" data-sortable="true">Name</th>
											<th data-field="days_open" data-sortable="true">Days Open</th>
											<th data-field="image" >Image</th>
											<th data-field="created" data-sortable="true">Created</th>
											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>

							</div>
							<!-- <div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_booking_list">
								<div id="toolbar2">
									<div class="form-inline" role="form">
										<button id="add_new" type="button" class="btn btn-primary" data-target="#AddDocumentationTypesModal" data-toggle="modal" >Add New</button>
									</div>
								</div>
								<table 
									data-toggle="table"
									data-url	="PropertyVenues/GetAllVenuesTable"
									data-query-params	= "prop_id=<?=$data['prop_id'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar2"
									data-sort-name="queryDate"
									data-sort-order="desc"
									id="table-category"
									class="display table table-striped"
									data-row-style="rowStyle"
								>
								<thead>
									<tr>
										<th data-field="name" data-sortable="true">Name</th>
										<th data-field="days_open" data-sortable="true">Days Open</th>
										<th data-field="image" data-sortable="true" data-formatter="imageFormatter">Image</th>
										<th data-field="created" data-sortable="true">Created</th>
										<th data-field="buttons">Action</th>
									</tr>
								</thead>
							</table>
						</div> -->


					</div>
				</div>

			</div>
		</div>
	</div>

</div>
<!-- /Content -->

</div>
<!-- /content