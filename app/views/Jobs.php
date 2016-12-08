<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Jobs</div>
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

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="PrintJobModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="PrintForm" id="PrintForm">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h4 class="modal-title">Print Job</h4>
					</div>
					<div class="modal-body">
						<div id="print_place"></div>
					</div>
					<div class="modal-footer">
						<div class="col-md-12 clear">
							<button type="submit" class="btn btn-success" id="PrintJob">Print</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>


	<div class="modal fade" id="SMSCommentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Job Comment/SMS</h4>
				</div>
				<div class="modal-body">

					<!-- Left Column -->
					<div class="col-md-6">

						<!-- Comment -->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CommentForm" id="CommentForm" method="POST" action="Jobs/SaveComment">
							<div id="send_comment"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-comment"></i> <strong>Comment</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS" placeholder="Type your Comment"></textarea>

									<input type="hidden" name="JobID" id="JobID" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-camera"></i> <strong>Attach a Quote, Invoice or Photo</strong></label>
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
						<!-- SMS Supplier-->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="SMSSupplierForm" id="SMSSupplierForm" method="POST">
							<div id="send_supplier_sms_query"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-send"></i> <strong>SMS Supplier</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS" placeholder="Type your SMS message here"></textarea>

									<input type="hidden" name="JobID" id="JobID" />
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-addon btn-info" id="SendSMS" rel="tooltip" data-original-title="Sends a SMS to the supplier"><i class="fa fa-send"></i>Send SMS To Supplier</button>
							</div>
						</form>
						<!-- /SMS Supplier-->

						<!-- SMS -->
						<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="SMSForm" id="SMSForm" method="POST">
							<div id="send_sms_query"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><i class="fa fa-send"></i> <strong>SMS the Client/Resident</strong></label>
								<div class="col-sm-9">
									<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS" placeholder="Type your SMS message here"></textarea>

									<input type="hidden" name="JobID" id="JobID" />
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-addon btn-info" id="SendSMS" rel="tooltip" data-original-title="Sends a SMS to person who logged the job"><i class="fa fa-send"></i>Send SMS To Resident</button>
							</div>
						</form>
						<!-- /SMS -->
						
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
						<div id="mark_done2_err"></div>
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
						<div id="mark_done_err"></div>
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
						<h4 class="modal-title" id="myModalLabel">Delete Job</h4>
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
					<h4 class="modal-title" id="myModalLabel">Create Job</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateJobForm" id="CreateJobForm" method="POST">
						<div id="error_save_job"></div>
						<input type="hidden" name="JobQueryID" id="JobQueryID" />
						<input type="hidden" name="JobProperty" value="<?=$data['prop_id'];?>" />
						

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

						<div class="form-group">
							<label for="JobAssignee" class="col-sm-3 control-label">Job Assignee</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobAssignee" name="JobAssignee">
									<option></option>
								</select>
							</div>
						</div>

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
							<label for="DateToBeCompleted" class="col-sm-3 control-label">Date To Be Completed Date</label>
							<div class="col-sm-9">
								<input type="text" name="DateToBeCompleted" id="DateToBeCompleted" class="form-control" />
							</div>
						</div>
						
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

	<div class="modal fade" id="EditJobModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Update Job</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UpdateJobForm" id="UpdateJobForm" method="POST">
						<div id="error_edit_job"></div>
						<input type="hidden" name="JobID" id="JobID" />						

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

						<div class="form-group">
							<label for="JobAssignee" class="col-sm-3 control-label">Job Assignee</label>
							<div class="col-sm-9">
								<select class="form-control" id="JobAssignee" name="JobAssignee">
									<option></option>
								</select>
							</div>
						</div>

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
							<label for="DateToBeCompleted" class="col-sm-3 control-label">Date To Be Completed Date</label>
							<div class="col-sm-9">
								<input type="text" name="DateToBeCompleted" id="DateToBeCompleted" class="form-control" />
							</div>
						</div>
						
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

	<div class="modal fade" id="MarkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="MarkJobForm" id="MarkJobForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Mark Job</h4>
						<input type="hidden" name="jobID" id="jobID" />
					</div>
					<div class="modal-body">
						<div id="mark_job_err"></div>
						<div class="form-group">
							<label for="JobAssignee" class="col-sm-3 control-label">Mark Job As</label>
							<div class="col-sm-9">
								<select class="form-control" id="MarkJobAs" name="MarkJobAs">
									<option></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<br />
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="MarkJobButton">Mark</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="EmailSupplierModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="EmailForm" id="EmailForm">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h4 class="modal-title">Email Supplier</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="job_id" id="job_id" />
						<input type="hidden" name="supplier_id" id="supplier_id" />
						<input type="hidden" name="supplier_name" id="supplier_name" />
						<input type="hidden" name="property_name" id="property_name" />
						<input type="hidden" name="supplier_email" id="supplier_email" />
						<input type="hidden" name="supplier_phone_number" id="supplier_phone_number" />
						<input type="hidden" name="supplier_unit_number" id="supplier_unit_number" />
						<input type="hidden" name="supplier_priority" id="supplier_priority" />
						<input type="hidden" name="supplier_description" id="supplier_description" />
						<input type="hidden" name="supplier_authorised_by" id="supplier_authorised_by" />
						<input type="hidden" name="supplier_date_tobe_completed" id="supplier_date_tobe_completed" />
						<input type="hidden" name="supplier_job_status" id="supplier_job_status" />
						
						<div id="email_place_error"></div>
						<div id="email_place"></div>
					</div>
					<div class="modal-footer">
						<div class="col-md-12 clear">
							<button type="submit" class="btn btn-success" id="saveDevice">Send</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Content -->
	<div class="box-row">
		<div class="box-cell">
			<div class="box-inner padding">
				<div class="col-lg-12">
					<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
					<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />

					<form name="FilterForm" id="FilterForm" role="form">
						<div class="card">
							<div class="card-heading">
								<h2>Filter Queries</h2>
							</div>
							<div class="card-body bg-light lt">
								
								<div class='col-sm-8'> 
									<div class='form-group'>
										<label for="user_title" for="JobStatus">Job Status</label>
										<div class="full-width">
											<select class="form-control input-normal" id="JobStatus" name="JobStatus" style="width:100%">
												<option value="">None selected</option>
											</select>
										</div>
									</div>
								</div>


								<div class='col-sm-4'>    
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
					<!-- Card  -->
					<div id="card_area">
					</div>
					
					<div class="panel panel-default">

						<div class="md-whiteframe-z0 bg-white">
							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#billing" aria-expanded="true">Jobs</a>
								</li>
							</ul>
						
							
							<div class="panel-body">
							<div id="toolbar">
								<div class="form-inline" role="form">
									<button id="add_new" type="button" class="btn btn-primary" data-target="#CreateJobModal" data-toggle="modal" >Add Job</button>
								</div>
							</div>
							<table 
								data-toggle="table"
								data-url="Jobs/GetAllJobs"
								data-query-params	= "prop_id=<?=$data['prop_id'];?>"
								data-search="true"
								data-show-refresh="true"
								data-show-toggle="true"
								data-show-columns="true"
								data-toolbar="#toolbar"
								id="table-jobs"
								class="display table table-striped"
								data-show-export="true"
								data-export-options='
									       		{
									       			"fileName": "<?=$data['property_name'];?> Job",
									       			"worksheetName": "<?=$data['property_name'];?> Job",
									       		}
										       		'
							>
							<thead>
								<tr>
									<th data-field="job_id">Job ID</th>
									<th data-field="query">Query Name</th>
									<th data-field="supplier">Supplier</th>
									<th data-field="unit_number">Unit Number</th>
									<th data-field="status">Status</th>
									<th data-field="description">Description</th>
									<th data-field="priority">Priority</th>
									<th data-field="date_tobe_completed">Date To Be Completed</th>
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
	<!-- /Content -->

</div>
<!-- /content