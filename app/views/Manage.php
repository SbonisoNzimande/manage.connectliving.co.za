<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Manage</div>
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

	<div class="modal fade" id="addNewResidentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Resident</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateResidentForm" id="CreateResidentForm" method="POST">
					<div class="modal-body">
						<div id="create_res_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit Number</label>
							<input type="hidden" name="PropertyID" value="<?=$data['prop_id'];?>" />
							<div class="col-sm-9">
								<input type="text" name="UnitNumber" id="UnitNumber" class="form-control" />
							</div>
							
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentName" id="ResidentName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentPhone" id="ResidentPhone" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Cellphone</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentCellphone" id="ResidentCellphone" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Notify Email</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentNotifyEmail" id="ResidentNotifyEmail" class="form-control" />
								<span class="help-block m-b-none"><i>Enter multiple email addresses separated by ; with no spaces</i></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="ResidentType" name="ResidentType">
									<option></option>
									<option value="owner">Owner</option>
									<option value="tenant">Tenant</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Trustee</label>
							<div class="col-sm-9">
								<select class="form-control" id="ResidentTrustee" name="ResidentTrustee">
									<option></option>
									<option value="yes">Yes</option>
									<option value="no">No</option>
								</select>
							</div>
						</div>


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

	<div class="modal fade" id="EditResidentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Resident</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditResidentForm" id="EditResidentForm" method="POST">
					<div class="modal-body">
						<div id="update_res_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Unit Number</label>
							<input type="hidden" name="ResidentID" id="ResidentID" />
							<div class="col-sm-9">
								<input type="text" name="UnitNumber" id="UnitNumber" class="form-control UnitNumber" />
							</div>
							
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentName" id="ResidentName" class="form-control ResidentName" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentPhone" id="ResidentPhone" class="form-control ResidentPhone" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Cellphone</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentCellphone" id="ResidentCellphone" class="form-control ResidentCellphone" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Notify Email</label>
							<div class="col-sm-9">
								<input type="text" name="ResidentNotifyEmail" id="ResidentNotifyEmail" class="form-control ResidentNotifyEmail" />
								<span class="help-block m-b-none"><i>Enter multiple email addresses separated by ; with no spaces</i></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Type</label>
							<div class="col-sm-9">
								<select class="form-control ResidentType" id="ResidentType" name="ResidentType">
									<option></option>
									<option value="owner">Owner</option>
									<option value="tenant">Tenant</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Trustee</label>
							<div class="col-sm-9">
								<select class="form-control ResidentTrustee" id="ResidentTrustee" name="ResidentTrustee">
									<option></option>
									<option value="yes">Yes</option>
									<option value="no">No</option>
								</select>
							</div>
						</div>


						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="UpdateResident">Update</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="ArchiveResidentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="archive_id" id="archive_id" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Archive Unit</h4>
				</div>
				<div class="modal-body">
					<div id="archive_err"></div>
					<p>Are you sure you want to archive this unit?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="ArchiveUnit">Yes</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="CommunicateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Communicate for Resident: <span id="resName"></span></h4>
				</div>
					<div class="modal-body">

					<!-- Tabs -->

							
					<!-- /Tabs -->

						<!-- Left Column -->
						<div class="col-md-7">

							<div class="md-whiteframe-z0 bg-white">
								<ul class="nav nav-lines nav-tabs nav-justified">
									<li class="active">
									<a href="" data-toggle="tab" data-target="#tab_comment" aria-expanded="true">Comment</a>
									</li>
									<li class="">
										<a href="" data-toggle="tab" data-target="#tab_sms" aria-expanded="true">SMS</a>
									</li>

									<li class="">
										<a href="" data-toggle="tab" data-target="#tab_email" aria-expanded="true">Email</a>
									</li>
								</ul>

								<div class="tab-content p m-b-md clear b-t b-t-2x">
									<div role="tabpanel" class="tab-pane animated fadeInDown active" id="tab_comment">
										<!-- Comment -->
										<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CommentForm" id="CommentForm" method="POST"  action="Manage/SaveComment">
											<div id="send_comment"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label">Comment</label>
												<div class="col-sm-9">
													<textarea class="form-control" name="CommentText" rows="5" id="CommentText"></textarea>
													<input type="hidden" name="ResID" id="ResID" />
													<input type="hidden" name="PropertyID" id="PropertyID" value="<?=$data['prop_id'];?>" />
												</div>
											</div>

											<div class="form-group">
												<label class="col-sm-3 control-label">File</label>
												<div class="col-sm-9">
													<input type="file" name="UploadFile" id="UploadFile" />
													<div id="progress-div"><div id="progress-bar"></div></div>
													<div id="targetLayer"></div>
												</div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-addon btn-info" id="SaveComment"><i class="fa fa-comment"></i>Comment</button>
											</div>
										</form>
										<!-- /Comment -->
									</div>

									<!--  -->
									<div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_sms">
										<!-- SMS -->
										<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="SMSForm" id="SMSForm" method="POST">
											<div id="send_sms_query"></div>
											<div class="form-group">
												<label class="col-sm-3 control-label">SMS</label>
												<div class="col-sm-9">
													<textarea class="form-control" name="CommentSMS" rows="5" id="CommentSMS"></textarea>

													<input type="hidden" name="ResID" id="ResID" />
													<input type="hidden" name="PropertyID" id="PropertyID" value="<?=$data['prop_id'];?>" />
												</div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-addon btn-info" id="SendSMS"><i class="fa fa-send"></i>Send SMS</button>
											</div>
										</form>
										<!-- /SMS -->
									</div>

									<div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_email">
										<!-- Email -->
										<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EmailForm" id="EmailForm" method="POST" action="Manage/SaveEmail">
											<div id="send_email_query"></div>
											<div class="form-group">
												<textarea name="EmailText" id="editor"  class="form-control" ></textarea>
												<input type="hidden" name="ResID" id="ResID" />
												<input type="hidden" name="PropertyID" id="PropertyID" value="<?=$data['prop_id'];?>" />
											</div>
											<div class="form-group">
												<label class="control-label">Attachment</label>
												<input type="file" name="AttachementFile" id="AttachementFile" />
											</div>
											<div id="progress-div"><div id="progress-bar"></div></div>
											<div id="targetLayer2"></div>
											<div class="modal-footer">
												<button class="btn btn-addon btn-info" id="SendEmail"><i class="fa fa-send"></i>Send Email</button>
											</div>
										</form>
										<!-- /Email -->
									</div>
								</div>
							</div>

						</div>
						<!-- /Left Column -->
						<!-- Right Column -->
						<div class="col-md-5">
							<div id="scroll-wrap2">
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

	<div class="modal fade" id="ArchivedComHistoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Communicate for Resident: <span id="resName"></span></h4>
				</div>
				<div class="modal-body">

					<!-- Tabs -->

					<!-- /Tabs -->

					<div class="col-md-12">
						<div id="scroll-wrap2">
						<ul class="timeline ng-scope" ng-class="{'timeline-center': center}" id="timeline_area_archived">
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

	<!-- Content -->
	<div class="box-row">
		<div class="box-cell">
			<div class="box-inner padding">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />
						<div class="panel-heading b-b panel-primary">
							<i class="fa fa-building"></i> <strong><?=$data['prop_name'];?></strong>
						</div>
						<div class="md-whiteframe-z0 bg-white">
							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#current_res" aria-expanded="true"><i class="fa fa-users"></i> Current Residents</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#archive_res" aria-expanded="true"><i class="fa fa-suitcase"></i> Archived Residents</a>
								</li>

								<li class="">
									<a href="" data-toggle="tab" data-target="#trustees" aria-expanded="true"><i class="fa fa-university"></i> Trustees</a>
								</li>
							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="current_res">
									
									<div id="toolbar">
										<div class="form-inline" role="form">
											<button id="add_new" type="button" class="btn btn-primary" data-target="#addNewResidentModal" data-toggle="modal"><i class="fa fa-plus"></i> Add New Resident</button>
										</div>
									</div>

									<table data-toggle="table"
									data-url="Manage/GetTable"
									data-query-params="prop_id=<?=$data['prop_id'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar"
									data-sort-name="unitNumber"
									data-sort-order="asc"
									data-show-pagination-switch="true"
									data-pagination="true"
									data-page-list="[10, 25, 50, 100, ALL]"
									data-show-export="true"
									data-export-options='
											       		{
											       			"fileName": "Residents",
											       			"worksheetName": "Current Residents",
											       		'
									data-pagination="true"
									id="dynamic-table-residents"
									class="display table table-striped"
									>
									<thead>
										<tr>
											<th data-field = "unitNumber" data-sortable="true">Unit Number</th>
											<th data-field = "residentName" data-sortable="true">Resident Name</th>
											<th data-field = "residentPhone" data-sortable="true">Phone</th>
											<th data-field = "residentCellphone" data-sortable="true">Cellphone</th>
											<th data-field = "residentNotifyEmail" data-sortable="true">Notify Email</th>
											<th data-field = "residentType" data-sortable="true">Type</th>
											<th data-field = "residentTrustee" data-sortable="true">Trustee</th>

											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>



							</div>

							<!--  -->
							<div role="tabpanel" class="tab-pane animated fadeInDown" id="archive_res">
								<table data-toggle="table"
								data-url="Manage/GetTableArchived"
								data-query-params="prop_id=<?=$data['prop_id'];?>"
								data-search="true"
								data-show-refresh="true"
								data-show-toggle="true"
								data-show-columns="true"

								data-sort-name="unitNumber"
								data-sort-order="asc"
								data-show-pagination-switch="true"
								data-pagination="true"
								data-page-list="[10, 25, 50, 100, ALL]"
								data-show-export="true"
								data-export-options='
										       		{
										       			"fileName": "Residents",
										       			"worksheetName": "Current Residents",
										       		'
								id="archived-residents"
								class="display table table-striped"
								>
								<thead>
									<tr>
										<th data-field = "unitNumber" data-sortable="true">Unit Number</th>
										<th data-field = "residentName" data-sortable="true">Resident Name</th>
										<th data-field = "residentPhone" data-sortable="true">Phone</th>
										<th data-field = "residentCellphone" data-sortable="true">Cellphone</th>
										<th data-field = "residentNotifyEmail" data-sortable="true">Notify Email</th>
										<th data-field = "residentType" data-sortable="true">Type</th>
										<th data-field = "residentTrustee" data-sortable="true">Trustee</th>
										<th data-field = "buttons">Action</th>
									</tr>
								</thead>
							</table>
						</div>

						<div role="tabpanel" class="tab-pane animated fadeInDown" id="trustees">
								<table data-toggle="table"
								data-url="Manage/GetTableTrustees"
								data-query-params="prop_id=<?=$data['prop_id'];?>"
								data-search="true"
								data-show-refresh="true"
								data-show-toggle="true"
								data-show-columns="true"

								data-sort-name="unitNumber"
								data-sort-order="asc"
								data-show-pagination-switch="true"
								data-pagination="true"
								data-page-list="[10, 25, 50, 100, ALL]"
								data-show-export="true"
								data-export-options='
										       		{
										       			"fileName": "Residents",
										       			"worksheetName": "Current Residents",
										       		'
								id="trustee-residents"
								class="display table table-striped"
								>
								<thead>
									<tr>
										<th data-field = "unitNumber">Unit Number</th>
										<th data-field = "residentName">Resident Name</th>
										<th data-field = "residentPhone">Phone</th>
										<th data-field = "residentCellphone">Cellphone</th>
										<th data-field = "residentNotifyEmail">Notify Email</th>
										<th data-field = "residentType">Type</th>
										<th data-field = "residentTrustee">Trustee</th>
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