<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Maintenance</div>
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
					<h4 class="modal-title" id="myModalLabel">Create Query</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateQueryForm" id="CreateQueryForm" method="POST">
						<div id="create_q_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Query Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="QueryType" name="QueryType">
									<option></option>
									<option>General</option>
									<option>Electrical</option>
									<option>Plumbing</option>
									<option>Security</option>
									<option>Cleaning</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Property</label>
							<div class="col-sm-9">
								<select class="form-control" id="PropertyList" name="PropertyList">
									<option></option>
									
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit</label>
							<div class="col-sm-9">
								<input type="text" name="Unit" id="Unit" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">User</label>
							<div class="col-sm-9">
								<select class="form-control" id="UsersList" name="UsersList">
									<option></option>
									
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Assign To</label>
							<div class="col-sm-9">
								<select class="form-control" id="AssignTo" name="AssignTo">
									<option></option>
									
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Query</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Query" rows="5" id="Query"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Image</label>
							<div class="col-sm-9">
								<input type="file"  class="form-control" name="Image" id="Image" />
							</div>
						</div>

						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveUser">Save</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Query</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditQueryForm" id="EditQueryForm" method="POST">
						<div id="edit_q_err"></div>
						<input type="hidden" name="EditID" id="EditID" />
						<div class="form-group">
							<label class="col-sm-3 control-label">Query Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="QueryTypeedt" name="QueryTypeedt">
									<option></option>
									<option>general</option>
									<option>electrical</option>
									<option>plumbing</option>
									<option>security</option>
									<option>cleaning</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Property</label>
							<div class="col-sm-9">
								<select class="form-control" id="PropertyListedt" name="PropertyListedt">
									<option></option>
									
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit</label>
							<div class="col-sm-9">
								<input type="text" name="Unitedt" id="Unitedt" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">User</label>
							<div class="col-sm-9">
								<select class="form-control" id="UsersListedt" name="UsersListedt">
									<option></option>
									
								</select>
							</div>
						</div>

						

						<div class="form-group">
							<label class="col-sm-3 control-label">Query</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Queryedt" rows="5" id="Queryedt"></textarea>
							</div>
						</div>


						<div class="clearfix "></div>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="UpdateQuery">Save</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
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

	<div class="modal fade" id="ImageArea" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel"><span id="store_name"></span></h4>
				</div>
				<div class="modal-body">
					<div class="panel-body">
						<span id="image_area"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" id="rotateIMG"><i class="glyphicon glyphicon-refresh gly-spin"></i></button>
					<button type="button" class="btn btn-default" data-dismiss="modal" id="CloseComp">Close</button>
				</div>
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
						<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />
						<div class="panel-heading b-b b-light">
							<button class="btn btn-primary" data-toggle="modal" data-title="Edit" data-target="#CreateModal">Create Query</button>
						</div>
						<div class="panel-body">
							<table id="query-table" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Query Type</th>
										<th>User</th>
										<th>Unit</th>
										<th>Query</th>
										<th>Date Logged</th>
										<th>Status</th>
										<th>Comment</th>
										<th>Action</th>
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