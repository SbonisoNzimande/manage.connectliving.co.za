<!-- content -->
<div id="content" class="app-content" role="main">
	<div class="box">
		<!-- Content Navbar -->
		<div class="navbar md-whiteframe-z1 no-radius yellow">
			<!-- Open side - Naviation on mobile -->
			<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
			<!-- / -->
			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h4">Notifications</div>
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
			
			<!-- / -->
		</div>
		<div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Create Notification</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SaveForm">
							<div id="save_err"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Property</label>
								<div class="col-sm-9">
									<select class="form-control" id="PropertyList" name="PropertyList">
										<option></option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Message</label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="5" name="Message" id="Message"></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Start Date</label>
								<div class="col-sm-9">
									<div class='input-group date' id='startdatepicker1'>
										<input type='text' class="form-control" name="StartDate" id="StartDate" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">End Date</label>
								<div class="col-sm-9">
									<div class='input-group date' id='enddatepicker1'>
										<input type='text' class="form-control" id="EndDate"  name="EndDate" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Mood</label>
								<div class="col-sm-9">
									<select class="form-control" id="Mood" name="Mood">
										<option></option>
										<option>good</option>
										<option>caution</option>
										<option>bad</option>
									</select>
								</div>
							</div>


							<div class="clearfix "></div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="SaveNotification">Save</button>
					</div>
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
						<h4 class="modal-title" id="myModalLabel">Edit Notification</h4>
					</div>
					<div class="modal-body">
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SaveForm">
							<div id="edit_err"></div>
							<input type="hidden" name="edtID" id="edtID" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Property</label>
								<div class="col-sm-9">
									<select class="form-control" id="PropertyListedt" name="PropertyListedt">
										<option></option>
										
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Message</label>
								<div class="col-sm-9">
									<textarea class="form-control" rows="5" name="Messageedt" id="Messageedt"></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Start Date</label>
								<div class="col-sm-9">
									<div class='input-group date' id='startdatepicker2'>
										<input type='text' class="form-control" name="StartDateedt" id="StartDateedt" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">End Date</label>
								<div class="col-sm-9">
									<div class='input-group date' id='enddatepicker2'>
										<input type='text' class="form-control" id="EndDateedt"  name="EndDateedt" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Mood</label>
								<div class="col-sm-9">
									<select class="form-control" id="Moodedt" name="Moodedt">
										<option></option>
										<option>good</option>
										<option>caution</option>
										<option>bad</option>
									</select>
								</div>
							</div>


							<div class="clearfix "></div>

						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="EditNotification">Save</button>
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
						<h4 class="modal-title" id="myModalLabel">Delete Notification</h4>
					</div>
					<div class="modal-body">
						<div id="delete_err"></div>
						<p>Do you want to delete this notification?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" id="DeleteNote">Yes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Content -->
		<div class="box-row">
			<div class="box-cell">
				<div class="box-inner padding">
					<div class="card">
						<div class="card-heading">
						  <button class="btn btn-primary" data-toggle="modal" data-title="Edit" data-target="#CreateModal">Create Notification</button>

						</div>

						<span id="note_err"></span>

						<div class="card-body bg-light lt" id="note_list">

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- / -->
	</div>

</div>
<!-- / content -->