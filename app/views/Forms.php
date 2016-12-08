<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Forms</div>
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

	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="SendFormModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="EmailForm" id="EmailForm">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h4 class="modal-title">Send Form For Approval</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" name="prop_id" id="prop_id" />
						<input type="hidden" name="submission_id" id="submission_id" />
						
						<div id="email_place_error"></div>
						<div id="email_place"></div>
					</div>
					<div class="modal-footer">
						<div class="col-md-12 clear">
							<button type="submit" class="changeDateDoneButton btn btn-success" id="saveDevice">Send</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addNewFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Form</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateNewForm" id="CreateNewForm" method="POST">
					<div class="modal-body">
						<div id="create_form_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Form Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="FormName" name="FormName" placeholder="Enter name of form">
								<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Form Instructions</label>
							<div class="col-sm-9">
								<textarea name="FormInstructions" id="FormInstructions"  class="form-control"  ></textarea>
							</div>
						</div>

						<table class="table" id="questions_table">
							
							<thead>
								<tr>
									<th width="1%">#</th>
									<th width="20%">Question Text</th>
									<th>Question Options</th>
									<th>Question Type</th>
									<th>Mandatory</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td >
										<input type="text" class="form-control" id="QuestionNumber" name="QuestionNumber[]" placeholder="Question Number" style="width:50px">
									</td>
									<td >
										<!-- <input type="text" class="form-control" id="QuestionText" name="QuestionText[]" placeholder="Question Text"> -->

										<textarea class="form-control" id="QuestionText" name="QuestionText[]" placeholder="Question Text" cols="50" rows="5"></textarea>
									</td>

									<td>
										<input type="text" class="form-control" id="QuestionOptions" name="QuestionOptions[]" placeholder="Question Options" data-role="tagsinput">
									</td>

									<td>
										<select class="form-control" name="QuestionType[]" id="QuestionType" placeholder="Question Type">
											<option value="" disabled="true">Question Type</option>
											<option value="number_text">Number Text</option>
											<option value="file_upload">File Upload</option>
											<option value="free_text">Free Text</option>
											<option value="checkbox">Checkbox</option>
											<option value="radio">Radio</option>
											<option value="select">Select</option>
											<option value="date">Date</option>
											<option value="signature">Signature</option>
											<option value="star_rating">Star Rating</option>
										</select>
									</td>
									<td>
										<select class="form-control" name="QuestionMandatory[]" id="QuestionMandatory" placeholder="Question Mandatory">
											<option value="false" selected="true">No</option>
											<option value="true">Yes</option>
										</select>
									</td>
									<td>
										<button type="button" class="btn btn-default" id="addmore">Add More</button>
									</td>
								</tr>

							</tbody>
						</table>

						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveResident">Save</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="EditFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Form</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditFormForm" id="EditFormForm" method="POST">
					<div class="modal-body">
						
						<input type="hidden" name="EditFormID" id="EditFormID"  />
						<div class="form-group">
							<label class="col-sm-3 control-label">Form Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="FormName" name="FormName" placeholder="Enter name of form">
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Form Instructions</label>
							<div class="col-sm-9">
								<textarea name="FormInstructions" id="FormInstructions"  class="form-control" ></textarea>
							</div>
						</div>

						<table class="table" id="questions_table_edt">
							
							<thead>
								<tr>
									<th class="col-md-2">#</th>
									<th>Question Text</th>
									<th>Question Options</th>
									<th>Question Type</th>
									<th>Mandatory</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								

							</tbody>
						</table>

						<div class="clearfix "></div>

						<div id="edit_form_err"></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveResident">Save</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="DeleteFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="DeleteFormID" id="DeleteFormID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Form</h4>
				</div>
				<div class="modal-body">
					<div id="delete_form_err"></div>
					<p>Are you sure you want to delete this form?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteForm">Yes</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="ViewSubmissionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">View Submission</h4>
				</div>
				<div class="modal-body">
					<table data-toggle="table" class="display table table-striped" id="responces_table" >
						<thead>
							<tr>
								<th data-field = "q_num">#</th>
								<th data-field = "q_text">Question</th>
								<th data-field = "responce">Response</th>
								<th data-field = "created">Date</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<div class="clearfix"></div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="LinkToResidentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Link To Resident</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="LinkResidentForm" id="LinkResidentForm" method="POST">
					<div class="modal-body">
						<div id="link_error"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit Number</label>
							<div class="col-sm-9">
								<input type="text" name="UnitNumber" id="UnitNumber" class="form-control" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Resident </label>
							<div class="col-sm-9">
								<input type="hidden" name="SubmissionID" id="SubmissionID" />
								<select class="form-control" name="ResidentList" id="ResidentList">	
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="LinkResponces">Save</button>
					</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="DuplicateFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Duplicate Form</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="DuplicateFormFrom" id="DuplicateFormFrom" method="POST">
					<div class="modal-body">
						<div id="duplicate_error"></div>
						<input type="hidden" id="DuplicateFormID" name="DuplicateFormID" />
						<div class="form-group">
							<label class="col-sm-3 control-label">Property Name</label>
							<div class="col-sm-9">
								<select class="form-control" name="PropertyName" id="PropertyName">	
								</select>
							</div>
						</div>
						
						<div class="clearfix"></div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-addon btn-info" type="submit"><i class="fa fa-save"></i>Save</button>
					</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="FillFormModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Fill Form</h4>
				</div>
				<div class="modal-body">
					<iframe id="submit-place" src="" style="zoom:0.60" width="99.6%" height="900" frameborder="0"></iframe>
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
						<div class="md-whiteframe-z0 bg-white">

							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#form_tab" aria-expanded="true">Forms</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#submit_tab" aria-expanded="true">Submission</a>
								</li>
							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">

								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="form_tab">

									<div id="toolbar">
										<div class="form-inline" role="form">
											<button id="add_new" type="button" class="btn btn-primary" data-target="#addNewFormModal" data-toggle="modal" >Add New</button>
										</div>
									</div>

									<table data-toggle="table"
									data-url="http://manage.connectliving.co.za/Forms/GetTable"
									data-query-params="prop_id=<?=$data['prop_id'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar"
									data-show-export="true"
									data-pagination="true"
									data-detail-view="true"
									data-detail-formatter="get_question_detail"
									id="form-table"
									class="display table table-striped"
									>
									<thead>
										<tr>
											<th data-field = "id">Form ID</th>
											<th data-field = "name">Form Name</th>
											<th data-field = "created">Created</th>
											<th data-field = "buttons">Action</th>
										</tr>
									</thead>
								</table>


							</div>

							<div role="tabpanel" class="tab-pane animated fadeInDown" id="submit_tab">

								<table data-toggle="table"
								data-url="http://manage.connectliving.co.za/Forms/GetResponcesByGroup"
								data-query-params="prop_id=<?=$data['prop_id'];?>"
								data-search="true"
								data-show-refresh="true"
								data-show-toggle="true"
								data-show-columns="true"
								data-show-export="true"
								data-pagination="true"
								data-detail-view="true"
								data-detail-formatter="get_responses_for_group"
								id="form-table2"
								class="display table table-striped"
								>
								<thead>
									<tr>
										<th data-field = "form_id">From ID</th>
										<th data-field = "form_name">Form Name</th>
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