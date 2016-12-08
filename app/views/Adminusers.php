<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Naviation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Admin Users</div>
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

	<div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create User</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope">
						<div id="save_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="FirstName" name="FirstName">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Surname</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="Surname" name="Surname">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
								<input type='text' class="form-control" name="Email" id="Email" placeholder="Email" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Cell Number</label>
							<div class="col-sm-9">
								<input type='text' class="form-control" name="CellNumber" id="CellNumber" placeholder="Cell Number" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
								<input type='password' class="form-control" name="Password1" id="Password1" placeholder="Enter password" />
							</div>
							
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label">Re-Enter Password</label>
							<div class="col-sm-9">
								<input type='password' class="form-control" name="Password2" id="Password2" placeholder="Re-Enter Password" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">User Type</label>
							<div class="col-sm-9">
								<select id="UserType" class="form-control">
									
								</select>
							</div>
							
						</div>
						

						<div class="clearfix "></div>

					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="SaveUser">Save</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="deleteID" id="deleteID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete User</h4>
				</div>
				<div class="modal-body">
					<div id="delete_err"></div>
					<p>Do you want to perform this action?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" id="DeleteSurvey">Yes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Assign users</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SavePermissionForm">
					<div class="modal-body">

						<div id="ass_user_err"></div>
						<input type="hidden"id="company_id" name="company_id">
						<input type="hidden"id="email" name="email">
						<input type="hidden"id="admin_id" name="admin_id">
						<div class="form-group">
							<label class="col-sm-3 control-label">Permission Type</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="PermissionType" name="PermissionType">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Properties</label>
							<div class="col-sm-9">
								<div id="prop_list">
									
								</div>
							</div>
						</div>

						<div class="clearfix "></div>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="EditUserPermission">Save</button>
					</div>
				</form>
			</div>
			
			<!-- /.modal-content -->
		</div>
		
		<!-- /.modal-dialog -->
	</div>



	<!-- Content -->
	<div class="box-row">
		<div class="box-cell">
			<div class="box-inner padding">
				<div class="col-lg-12">
					<div class="panel panel-default">
						
						<div class="panel-heading b-b b-light">
							&nbsp;
						</div>
						<div class="panel-body">
							<table id="users-table" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>User ID</th>
										<th>User Type</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>Cell Number</th>
										<th width="13%">Action</th>
									</tr>
								</thead>
							</table>
						</div>
						

					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- /Content -->

</div>
<!-- /content