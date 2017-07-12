<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">App Registrations</div>
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

	<div class="modal fade" id="ConvertUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="BConvertkUserID" id="ConvertUserID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Convert User To Resident</h4>
				</div>
				<div class="modal-body">
					<div id="convert_user_err"></div>
					<p>Are You Sure You Want to Convert This User?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="ConvertUserButton">Yes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="BlockUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="BlockUserID" id="BlockUserID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Block User</h4>
				</div>
				<div class="modal-body">
					<div id="block_user_err"></div>
					<p>Are You Sure You Want to Block This User?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="BlockUserButton">Yes</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="EditUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Change User Type</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="ChangeUserTypeForm" id="ChangeUserTypeForm">
						<div id="update_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">User Type</label>
							<input type="hidden" name="UserID" id="UserID" />
							<div class="col-sm-9">
								<select id="UserType" name="UserType" class="form-control">
									<option></option>
									<option value="tenant">Tenant</option>
									<option value="owner">Owner</option>
									<option value="manager">Manager</option>
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

	<div class="modal fade" id="UnBlockUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="UnBlockUserID" id="UnBlockUserID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Unblock User</h4>
				</div>
				<div class="modal-body">
					<div id="unblock_user_err"></div>
					<p>Are You Sure You Want to Unblock This User?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="UnBlockUserButton">Yes</button>
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
						
						<div class="panel-heading b-b b-light">
							<!-- <button class="btn btn-primary" data-toggle="modal" data-title="Edit" data-target="#CreateModal">Create Query</button> -->
						</div>
						<div class="panel-body">
							<table id ="queries-table"
							       data-toggle ="table"
							       data-show-export ="true"
							       data-search ="true"
							       data-show-refresh ="true"
							       data-show-toggle ="true"
							       data-show-columns ="true"
							       data-url ="AppRegistrations/GetAllAppUsers"
							       data-query-params	= "prop_id=<?=$data['prop_id'];?>&prop_name=<?=$data['prop_name'];?>"
							       class ="display table table-striped"
							       data-pagination="false"
							       data-sort-name="queryDate"
							       data-sort-order="desc"
							       data-export-options='
							       		{
							       			"fileName": "App Users",
							       			"worksheetName": "<?=$data['prop_name'];?>",
							       		}
							       '
							>
							    <thead>
							    <tr>
							        <th data-field="property_name" data-sortable="true">Property Name</th>
							        <th data-field="userFullname" data-sortable="true">Full Name</th>
							        <th data-field="userCellphone" data-sortable="true">Cellphone</th>
							        <th data-field="registeredDate" data-sortable="true">Registration Date</th>
							        <th data-field="userType" data-sortable="true">User Type</th>
							        <th data-field="unitNo" data-sortable="true">Unit Number</th>
							        <th data-field="userDeviceToken" data-sortable="true">Device Token</th>
							        <th data-field="userStatus" data-sortable="true">Status</th>
							        
							        <th data-field="action">Action</th>
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