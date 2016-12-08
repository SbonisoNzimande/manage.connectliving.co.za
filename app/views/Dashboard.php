<!-- content -->
<div id="content" class="app-content" role="main">
	<div class="box">
		<!-- Content Navbar -->
		<div class="navbar md-whiteframe-z1 no-radius yellow">
			<!-- Open side - Naviation on mobile -->
			<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
			<!-- / -->
			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h4">Dashboard</div>
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

		<div class="modal fade" id="MarkDone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Mark Done</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope">
							<input type="hidden" name="QID" id="QID" />
							<div id="save_err"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Comment</label>
								<div class="col-sm-9">
									<textarea class="form-control" name="comment" rows="5" id="comment"></textarea>
								</div>
							</div>

							<div class="clearfix "></div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="SaveComment">Save</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="AssignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Assign User</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope">
							<input type="hidden" name="AID" id="AID" />
							<div id="ass_err"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Assign To</label>
								<div class="col-sm-9">
									<select class="form-control" id="AssignTo" name="AssignTo">
										<option></option>
									</select>
								</div>
							</div>

							<div class="clearfix "></div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="AssignUser">Save</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<input type="hidden" name="company_id" id="company_id" value="<?=$data['company_id'];?>" />
		<!-- Content -->
		<div class="box-row">
			<div class="box-cell">
				<div class="box-inner padding">
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<span id="create_q_err"></span>
								<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateQueryForm" id="CreateQueryForm" method="POST">
									<textarea class="form-control no-border p-md col-sm-12" name="Query" id="Query" rows="1" placeholder="Create query..."></textarea>

									<div class="lt p">
										<button md-ink-ripple class="md-btn md-raised pull-right p-h-md blue" id="CreateQueryForm">Save</button>
										<ul class="nav nav-pills nav-sm">
											<input name="Image" id="Image" type="file" style="visibility:hidden" />
											<li><a href id="upload_image" onclick="$('#Image').click();"><i class="fa fa-camera"></i></a></li>
											<li>
												<select class="form-control selectpicker input-sm" id="QueryType" name="QueryType" data-live-search="true" data-live-search-style="begins" title="Query Type">
													<option>General</option>
													<option>Cleaning</option>
													<option>Electrical</option>
													<option>Plumbing</option>
													

													<!-- <option>General</option>
													<option>Balance</option>
													<option>Occupation</option>
													<option>Operational</option>
													<option>Parking</option>
													<option>Repairs</option>
													<option>Storage</option>
													<option>Vat</option>
													<option>Gardens</option>
													<option>Laundry</option>
													<option>Health & Safety</option>
													<option>Events</option> -->
												</select>
											</li>
											<li>
												<select class="form-control input-sm" id="PropertyList" name="PropertyList">
													<option>Property</option>
												</select>
											</li>
											<li>
												<input type="text" name="Unit" id="Unit" class="form-control" style="width:100px;" placeholder="Unit" />
											</li>
											<input type="hidden" name="AssignTo" id="AssignTo" value="<?=$data['user_id'];?>" />
											<input type="hidden" id="UsersList" name="UsersList" value="Admin" />

										</ul>
									</div>
								</form>
							</div>

							<div class="card">
								<div class="card-heading">
									<h2>Queries</h2>
								</div>

								<div class="card-body">
									<div class="row" >
										<div class="col-sm-12">
											<div class="p-md">
												<div id="bargraph" style="height:190px" ></div>
											</div>
										</div>

									</div>
								</div>
							</div>

							<div class="row" id="maintenance-cards"></div>
							<div class="clearfix"></div>

						</div>

						<!-- <div class="col-sm-4">
							<div class="card">
								<div class="card-heading">
									<h2>Activities</h2>
									<small></small>
								</div>
								<div class="card-body" >
									<div class="streamline b-l b-accent m-b" id="activities">

									</div>
								</div>
							</div>
						</div> -->
					</div>

					


				</div>
			</div>
		</div>
		<!-- / -->
	</div>

</div>
<!-- / content -->




