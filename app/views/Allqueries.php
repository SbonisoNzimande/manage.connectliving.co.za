<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">All Queries</div>
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

	<div class="modal fade" id="SMSCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Query Comment/SMS</h4>
				</div>
				<div class="modal-body">

					<!-- Left Column -->
					<div class="col-md-6">

						<!-- Comment -->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CommentForm" id="CommentForm" method="POST" action="AllQueries/SaveComment">
							<div id="send_comment"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-comment"></i> <strong>Comment</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS" placeholder="Type your Comment/Update here"></textarea>

									<input type="hidden" name="QueryID" id="QueryID" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-camera"></i> <strong>Attach a File or Photo</strong></label>
								<div class="col-sm-9">
									<input type="file" name="UploadFile" id="UploadFile" />
									<div id="progress-div"><div id="progress-bar"></div></div>
									<div id="targetLayer"></div>
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-addon btn-info" id="SaveComment"><i class="fa fa-comment"></i>Save Comment</button>
							</div>
						</form>
						<!-- /Comment -->
						<!-- SMS -->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="SMSForm" id="SMSForm" method="POST">
							<div id="send_sms_query"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-send"></i> <strong>SMS the Client/Resident</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS" placeholder="Type your SMS message here"></textarea>

									<input type="hidden" name="QueryID" id="QueryID" />
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-addon btn-info" id="SendSMS" rel="tooltip" data-original-title="Sends a SMS to person who logged the query"><i class="fa fa-send"></i>Send SMS To Resident</button>
							</div>
						</form>
						<!-- /SMS -->

						<!-- Notificat -->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="NotificationForm" id="NotificationForm" method="POST">
							<div id="send_notification_query"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-eercast"></i> <strong>Send Notification</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="NotificationMessage" rows="5" id="NotificationMessage" placeholder="Type your message here"></textarea>

									<input type="hidden" name="QueryID" id="QueryID" />
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-addon btn-info" id="SendNotification" rel="tooltip" data-original-title="Sends a Notification to person who logged the query"><i class="fa fa-send"></i>Send Notification To Resident</button>
							</div>
						</form>
						<!-- /Notificat -->
						
					</div>
					<!-- /Left Column -->
					<!-- Right Column -->
					<div class="col-md-6">
						<div id="scroll-wrap">
							<ul class="timeline ng-scope" ng-class="{'timeline-center': center}" id="timeline_area">
							</ul>
						</div>
					</div>
					<!-- /Right Column -->

					<div class="clearfix"></div>



				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
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
									<option>General</option>
									<option>Cleaning</option>
									<option>Electrical</option>
									<option>Plumbing</option>
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

	<div class="modal fade" id="MarkDoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deletePermissionForm" id="deletePermissionForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Mark Done</h4>
						<input type="hidden" name="queryID" id="queryID" />
					</div>
					<div class="modal-body">
						<div id="mark_done_err"></div>
						<p>Do you want to perform this action?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="MarkDone">Yes</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>


	<div class="modal fade" id="MarkInsuranceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Mark As Insurance Claim</h4>
					<input type="hidden" name="queryinsID" id="queryinsID" />
				</div>
				<div class="modal-body">
					<div id="mark_insurance_err"></div>
					<p>Do you want to perform this action?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="MarkInsuranceClaim">Yes</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="MarkMaterialsRequiredModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deletePermissionForm" id="deletePermissionForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Mark Materials Required</h4>
						<input type="hidden" name="querymID" id="querymID" />
					</div>
					<div class="modal-body">
						<div id="mark_materials_err"></div>
						<p>Do you want to perform this action?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="MarkMaterialsRequired">Yes</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="AssignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="AssignForm" id="AssignForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Assign Query </h4>
						<input type="hidden" name="adminID" id="adminID" />
						<input type="hidden" name="queryID" id="queryID" />
					</div>
					<div class="modal-body">
						<div id="ressign_err"></div>
						<p>Are you sure you want to assign this query to <span id="assign_name"></span>?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary" id="AssignButton">Yes</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="DeleteQueryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deleteQueryForm" id="deleteQueryForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Delete Query</h4>
						<input type="hidden" name="deleteID" id="deleteID" />
					</div>
					<div class="modal-body">
						<div id="delete_query_err"></div>
						<p>Do you want to perform this action?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary" id="DeleteQuery">Yes</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>


	<!-- Creates the bootstrap modal where the image will appear -->
	<div class="modal fade" id="MaxImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Image preview</h4>
				</div>
				<div class="modal-body">
					<img src="" id="imagepreview" style="width: 100%;" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
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


	<div class="modal fade" id="CreateJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Jobs</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateJobForm" id="CreateJobForm" method="POST">
						<div id="error_save_job"></div>
						<input type="hidden" name="UserID" id="UserID" />
						<input type="hidden" name="JobQueryID" id="JobQueryID" />
						<input type="hidden" name="JobImageName" id="JobImageName" />
						<div class="form-group">
							<label for="JobProperty" class="col-sm-3 control-label">Property</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobProperty" name="JobProperty">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="JobSupplier" class="col-sm-3 control-label">Supplier</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobSupplier" name="JobSupplier">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="JobUnitNo" class="col-sm-3 control-label">Unit Number</label>
							<div class="col-sm-9">
								<input type="text" name="JobUnitNo" id="JobUnitNo" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label for="JobStatus" class="col-sm-3 control-label">Status</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobStatus" name="JobStatus">
									<option></option>
									<option >Quote Request</option>
									<option >Pre-approved</option>
									<option >Routine Maintenance</option>
									<option >Completed & Invoice Received</option>
									<option >Awaiting Invoice</option>
									<option >Quote Approval</option>
									<option >Materials Required</option>
									
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="JobDescription" class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="JobDescription" rows="5" id="JobDescription" ></textarea>
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="JobAssignee" class="col-sm-3 control-label">Job Assignee</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobAssignee" name="JobAssignee">
									<option></option>
								</select>
							</div>
						</div> -->

						<div class="form-group">
							<label for="JobPriority" class="col-sm-3 control-label">Priority</label>
							<div class="col-sm-9">
								<input id="JobPriority" name="JobPriority" class="form-control" class="rating-loading" data-size="sm">
							</div>
						</div>

						<div class="form-group">
							<label for="AuthorisedBy" class="col-sm-3 control-label">Authorised By</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="AuthorisedBy" name="AuthorisedBy">
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label">Date To Be Completed Date</label>
							<div class="col-sm-9">
								<div class='input-group date' id='datepicker1'>
									<input type='text' class="form-control" name="DateToBeCompleted" id="DateToBeCompleted" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="DateToBeCompleted" class="col-sm-3 control-label">Date To Be Completed Date</label>
							<div class="col-sm-9">
								<input type="text" name="DateToBeCompleted" id="DateToBeCompleted" class="form-control" />
							</div>
						</div> -->
						
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" type="submit"><i class="fa fa-save"></i>Save</button>
						</div>
					</form>

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

					<!-- Filter -->
					<form name="FilterForm" id="FilterForm" role="form">
						<div class="card">
							<div class="card-heading">
								<h2>Filter Queries</h2>
							</div>
							<div class="card-body bg-light lt">
								<div class='col-sm-3'>    
									<div class='form-group'>
										<label for="user_title" for="Status">Status</label>
										<div class="full-width">
											<select class="form-control input-normal" id="Status" name="Status" style="width:100%">
												<option value="">None selected</option>
												<option >Pending</option>
												<option >Done</option>
												<option >Materials Required</option>
												<option >Insurance Claim</option>
											</select>
										</div>
									</div>
								</div>
								<div class='col-sm-3'> 
									<div class='form-group'>
										<label for="user_title" for="QueryType">Query Type</label>
										<div class="full-width">
											
											<select class="form-control input-normal" id="QueryType" name="QueryType" style="width:100%">
												<option value="">None selected</option>
											</select>
										</div>


									</div>
								</div>

								<div class='col-sm-3'> 
									<div class='form-group'>
										<label for="user_title" for="DateRage">Date Rage</label>
										<div class="full-width">
											<input type="text" name="DateRage" id="DateRage" class="form-control input-normal" />
										</div>
									</div>
								</div>




								<div class='col-sm-3'>    
									<div class='form-group'>
										<label for="user_title"><span id="level_title">&nbsp;</span></label>
										<div class="full-width">
											<button class="btn btn-large btn-block btn-primary full-width input-normal" type="submit" id="FilterButton">Filter</button>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							
							
							<div class="clearfix"></div>

							
						</div>
					</form>
					<!-- /Filter -->
					<!-- Card  -->
					<input type="hidden" id="page-num" />
					<div id="card_area"></div>
					<div id="page-selection"></div>
					
					<div class="panel panel-default">
						
						<div class="md-whiteframe-z0 bg-white">
							
							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#tab_pending" aria-expanded="true">Pending</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#tab_done" aria-expanded="true">Done</a>
								</li>

							</ul>
							
							<div class="tab-content p m-b-md clear b-t b-t-2x">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="tab_pending">

									<table 
									data-toggle="table"
									data-url="AllQueries/GetAllQueries"
									data-query-params	= "query_type=pending&prop_id=<?=$data['prop_id'];?>&prop_name=<?=$data['prop_name'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar"
									data-sort-name="queryDate"
									data-sort-order="desc"
									id="table-pending"
									class="display table table-striped"
									data-row-style="rowStyle"
									data-show-pagination-switch="true"
									data-pagination="true"
									data-page-list="[10, 25, 50, 100, ALL]"
									data-show-export="true"
									data-export-options='
											       		{
											       			"fileName": "AllQueries",
											       			"worksheetName": "PendingQueries",
											       		'
									>
									<thead>
										<tr>
											<th data-field="queryType" data-sortable="true">Query Type</th>
											<th data-field="queryUsername" data-sortable="true">User</th>
											<th data-field="unitNo" data-sortable="true">Unit Number</th>
											<th data-field="queryInput" data-sortable="true">Query</th>
											<th data-field="queryDate" data-sortable="true" width="10%">Date</th>
											<th data-field="status" data-sortable="true">Status</th>
											<th data-field="comment" data-sortable="true">Comment</th>
											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>

							</div>
							<div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_done">
									<table 
										data-toggle="table"
										data-url="AllQueries/GetAllQueries"
										data-query-params	= "query_type=done&prop_id=<?=$data['prop_id'];?>&prop_name=<?=$data['prop_name'];?>"
										data-search="true"
										data-show-refresh="true"
										data-show-toggle="true"
										data-show-columns="true"
										data-toolbar="#toolbar"
										data-sort-name="queryDate"
										data-sort-order="desc"
										id="table-done"
										class="display table table-striped"
										data-row-style="rowStyle"

										data-show-pagination-switch="true"
										data-pagination="true"
										data-page-list="[10, 25, 50, 100, ALL]"
										data-show-export="true"
										data-export-options='
												       		{
												       			"fileName": "AllQueries",
												       			"worksheetName": "DoneQueries",
												       		}
												       '
									>
									<thead>
										<tr>
											<th data-field="queryType" data-sortable="true">Query Type</th>
											<th data-field="queryUsername" data-sortable="true">User</th>
											<th data-field="unitNo" data-sortable="true">Unit Number</th>
											<th data-field="queryInput" data-sortable="true">Query</th>
											<th data-field="queryDate" data-sortable="true" width="10%">Date</th>
											<th data-field="status" data-sortable="true">Status</th>
											<th data-field="queryDoneTime" data-sortable="true">Date Done</th>
											<th data-field="comment" data-sortable="true">Comment</th>
											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>
							</div>


						</div>
					</div>						
				</div>
			</div>
		</div>
	</div>

</div>
<!-- /Content -->

</div>
<!-- /content