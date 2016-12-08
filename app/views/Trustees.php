<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Trustees</div>
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

	<!-- // Modals // -->

	<div class="modal fade" id="UploadMinutesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload Minutes</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadDocumentForm" id="UploadDocumentForm" method="POST" action="Trustees/UploadDocument">
						<div id="error_save_doc"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="MeetingDate" class="col-sm-3 control-label">Meeting Date</label>
							<div class="col-sm-9">

								<div class='input-group date' id='startdatepicker1'>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
									<input type="text" id="MeetingDate" class="form-control" name="MeetingDate" />
								</div>
								
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
							<button class="btn btn-addon btn-info" id="ButtonUpload"><i class="fa fa-upload"></i>Upload</button>
						</div>
					</form>

					<div class="clearfix"></div>

				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Documentation</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditUploadDocumentForm" id="EditUploadDocumentForm" method="POST" action="Documentation/EditUploadDocument">
						<div id="error_edit_doc"></div>
						<input type="hidden" name="editID" id="editID" />
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="DocumentType" class="col-sm-3 control-label">Document Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="DocumentType" name="DocumentType">
									<option></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">File</label>
							<div class="col-sm-9">
								<input type="file" name="UploadLogoFile" id="UploadLogoFile" />
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="ButtonUpload"><i class="fa fa-upload"></i>Upload</button>
						</div>
					</form>

					<div class="clearfix"></div>

				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
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
	<div class="modal fade" id="SendSMSModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SaveSMSForm" name="SaveSMSForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Send SMS</h4>
					</div>

					<div class="modal-body">

						<div id="send_sms_err"></div>
						<input type="hidden" id="prop_id" name="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" name="prop_name" value="<?=$data['prop_name'];?>" />
						<input type="hidden" id="SendTo" name="SendTo" value="All Trustees" />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Message</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="5" name="SMSMessage" id="SMSMessage"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mood</label>
							<div class="col-sm-9">
								<select class="form-control" id="SMSMood" name="SMSMood">
									<option></option>
									<option>Good</option>
									<option>Caution</option>
									<option>Bad</option>
								</select>
							</div>
						</div>
						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveSMS">Send</button>
					</div>
				</div>
			</form>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="AddEventModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SaveSMSForm" name="SaveSMSForm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Add New Event</h4>
					</div>

					<div class="modal-body">

						<div id="send_sms_err"></div>
						<input type="hidden" id="prop_id" name="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" name="prop_name" value="<?=$data['prop_name'];?>" />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Message</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="5" name="SMSMessage" id="SMSMessage"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mood</label>
							<div class="col-sm-9">
								<select class="form-control" id="SMSMood" name="SMSMood">
									<option></option>
									<option>Good</option>
									<option>Caution</option>
									<option>Bad</option>
								</select>
							</div>
						</div>
						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveSMS">Send</button>
					</div>
				</div>
			</form>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>


	<!-- Email -->
	<div class="modal fade" id="SendEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="SaveEmailForm" name="SaveEmailForm" method="POST" action="Communicate/SaveEmail">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Send Email</h4>
					</div>
					<div class="modal-body">

						<div id="send_email_query"></div>
						<input type="hidden" id="prop_id" name="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" name="prop_name" value="<?=$data['prop_name'];?>" />
						<input type="hidden" id="SendTo" name="SendTo" value="All Trustees" />
						

						<div class="form-group">
							<label class="col-sm-2 control-label">Subject</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="Subject" name="Subject" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Mood</label>
							<div class="col-sm-10">
								<select class="form-control" id="EmailMood" name="EmailMood">
									<option></option>
									<option>Good</option>
									<option>Caution</option>
									<option>Bad</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<textarea name="EmailText" id="EmailText"  class="form-control" ></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Attachment</label>
							<div class="col-sm-10">
								<input class="form-control" type="file" name="AttachementFile" id="AttachementFile" />

								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer2"></div>
							</div>
						</div>
						
						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="SaveEmail">Send</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- // Modals // -->

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

							<ul class="nav nav-lines nav-tabs nav-justified" id="tabls">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#comm_tab" aria-expanded="true">Communicate</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#minute_tab" aria-expanded="true">Minutes</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#cal_tab" aria-expanded="true">Calendar</a>
								</li>
							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">

								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="comm_tab">
									<div class="card">
										<!-- top buttons -->
										<div class="card-heading">
											<!-- <button class="btn btn-sm btn-primary font-bold" data-toggle="modal" data-title="Edit" data-target="#SendNotification">Send Notification</button> -->
											<button class="btn btn-sm btn-primary font-bold" data-toggle="modal" data-title="Edit" data-target="#SendSMSModal">Send SMS</button>
											<button class="btn btn-sm btn-primary font-bold" data-toggle="modal" data-title="Edit" data-target="#SendEmailModal">Send Email</button>
										</div>
										<!-- /top buttons -->

										<span id="note_err"></span>

										<div class="card-body bg-light lt">

											<div class="col-md-12">
												<div class="ng-scope">
													<div  class="ng-scope">
														<!-- header -->
														<div class="m-b">
															<div class="btn-group pull-right">
																<button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-left"></i></button>
																<button type="button" class="btn btn-sm btn-bg btn-default"><i class="fa fa-chevron-right"></i></button>
															</div>
															<div class="btn-toolbar">
																<div class="btn-group dropdown">
																	<button class="btn btn-default btn-sm btn-bg dropdown-toggle" data-toggle="dropdown">
																		<span class="dropdown-label">Filter</span>                    
																		<span class="caret"></span>
																	</button>
																	<ul class="dropdown-menu text-left text-sm">
																		<li><a ui-sref="app.inbox.list({fold:'starred'})" href="#/app/inbox/inbox/starred">SMSes</a></li>
																		<li><a ui-sref="app.inbox.list({fold:'starred'})" href="#/app/inbox/inbox/starred">Emails</a></li>
																	</ul>
																</div>
																<div class="btn-group">
																	<button class="btn btn-sm btn-bg btn-default" data-toggle="tooltip" data-placement="bottom" data-title="Refresh" data-original-title="" title="" id="Refresh"><i class="fa fa-refresh"></i></button>
																</div>
															</div>
														</div>
														<!-- / header -->

														<!-- list -->
														<div class="m-b-lg" id="inbox_list">

														</div>
														<!-- / list -->
													</div>
												</div>
											</div>

											<div class="clearfix"></div>
										</div>
									</div>

								</div>

								<div role="tabpanel" class="tab-pane animated fadeInDown" id="minute_tab">
									<div class="panel-body">
										<div id="toolbar">
											<div class="form-inline" role="form">
												<button id="add_new" type="button" class="btn btn-primary" data-target="#UploadMinutesModal" data-toggle="modal" >Add New</button>
											</div>
										</div>
										<table 
										data-toggle 		= "table"
										data-url 			= "Trustees/GetMinutes"
										data-query-params	= "prop_id=<?=$data['prop_id'];?>"
										data-search 		= "true"
										data-show-refresh 	= "true"
										data-show-toggle 	= "true"
										data-show-columns 	= "true"
										data-toolbar 		= "#toolbar"
										id 					= "table-documents"
										class 				= "display table table-striped"
										>
										<thead>
											<tr>
												<th data-field="doc_name">Document Name</th>
												<th data-field="meeting_date">Meeting Date</th>
												<th data-field="created">Created Date</th>
												<th data-field="buttons">Action</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>

							<div role="tabpanel" class="tab-pane animated fadeInDown" id="cal_tab">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="comm_tab">
									<div class="card">
										<!-- top buttons -->
										<div class="card-heading">
											
										</div>
										<!-- /top buttons -->

										<span id="note_err"></span>

										<div class="card-body bg-light lt">

											<div class="col-md-12">
												<div id='calendar'></div>
											</div>

											<div class="clearfix"></div>
										</div>
									</div>

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