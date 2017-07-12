<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Emergency Contacts</div>
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
	<div class="modal fade" id="DuplicateContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Duplicate Contact</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="DuplicateContactForm" id="DuplicateContactForm" method="POST">
					<div class="modal-body">
						<div id="duplicate_error"></div>
						<input type="hidden" id="ContactID" name="ContactID" />
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

	<div class="modal fade" id="CreateContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Contact</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateContactForm" id="CreateContactForm" method="POST">
						<div id="error_save_contact"></div>
						<input type="hidden" name="PropID" id="PropID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="ContactName" class="col-sm-3 control-label">ContactName</label>
							<div class="col-sm-9">
								<input type="text" name="ContactName" id="ContactName" class="form-control" />
							</div>
						</div>


						<div class="form-group">
							<label for="ContactType" class="col-sm-3 control-label">Contact Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="ContactType" name="ContactType">
									<option></option>
									<option value="ambulance">Ambulance</option>
									<option value="fire">Fire</option>
									<option value="hospital">Hospital</option>
									<option value="police">Police</option>
									<option value="information">Information</option>
									<option value="manager">Manager</option>
									<option value="pool">Pool</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="ContactPhone" class="col-sm-3 control-label">Contact Phone</label>
							<div class="col-sm-9">
								<input type="text" name="ContactPhone" id="ContactPhone" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label for="ContactIcon" class="col-sm-3 control-label">Contact Icon</label>
							<div class="col-sm-9">
								

								<div class="input-group">
                                    <input data-placement="bottomRight" name="ContactIcon" id="ContactIcon" class="form-control icp icp-auto" value="fa-archive" type="text" />
                                    <span class="input-group-addon"></span>
                                </div>
							</div>
						</div>

						<div class="form-group">
							<label for="ContactColor" class="col-sm-3 control-label">Contact Color</label>
							<div class="col-sm-9">
								<div id="cp2" class="input-group colorpicker-component">
                                    <input type="text" value="#00AABB" name="ContactColor" id="ContactColor" class="form-control" />
                                 	<span class="input-group-addon"><i></i></span>
                                </div>
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

	<div class="modal fade" id="EditContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Contact</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditContactForm" id="EditContactForm" method="POST">
						<div id="error_edit_contact"></div>
						<input type="hidden" name="EditID" id="EditID" />

						<div class="form-group">
							<label for="ContactName" class="col-sm-3 control-label">ContactName</label>
							<div class="col-sm-9">
								<input type="text" name="ContactName" id="ContactName" class="form-control" />
							</div>
						</div>


						<div class="form-group">
							<label for="ContactType" class="col-sm-3 control-label">Contact Type</label>
							<div class="col-sm-9">
								<select class="form-control" id="ContactType" name="ContactType">
									<option></option>
									<option value="ambulance">Ambulance</option>
									<option value="fire">Fire</option>
									<option value="hospital">Hospital</option>
									<option value="police">Police</option>
									<option value="information">Information</option>
									<option value="manager">Manager</option>
									<option value="pool">Pool</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="ContactPhone" class="col-sm-3 control-label">Contact Phone</label>
							<div class="col-sm-9">
								<input type="text" name="ContactPhone" id="ContactPhone" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label for="ContactIcon" class="col-sm-3 control-label">Contact Icon</label>
							<div class="col-sm-9">
								

								<div class="input-group">
                                    <input data-placement="bottomRight" name="ContactIcon" id="ContactIcon" class="form-control icp icp-auto" value="fa-archive" type="text" />
                                    <span class="input-group-addon"></span>
                                </div>
							</div>
						</div>

						<div class="form-group">
							<label for="ContactColor" class="col-sm-3 control-label">Contact Color</label>
							<div class="col-sm-9">
								<div id="cp2" class="input-group colorpicker-component">
                                    <input type="text" value="#00AABB" name="ContactColor" id="ContactColor" class="form-control" />
                                 	<span class="input-group-addon"><i></i></span>
                                </div>
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


	<div class="modal fade" id="DeleteContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="DeleteContactForm" id="DeleteContactForm">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Delete Contact</h4>
						<input type="hidden" name="DeleteID" id="DeleteID" />
					</div>
					<div class="modal-body">
						<div id="error_delete_contact"></div>
						<p>Do you want to perform this action?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="submit" class="btn btn-primary" id="DeleteThis">Yes</button>
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
					<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
					<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />

					<div class="panel panel-default">

						<div class="md-whiteframe-z0 bg-white">
							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#billing" aria-expanded="true">Contacts</a>
								</li>
							</ul>

							
							<div class="panel-body">
								<div id="toolbar">
									<div class="form-inline" role="form">
										<button id="add_new" type="button" class="btn btn-primary" data-target="#CreateContactModal" data-toggle="modal" >Add Contact</button>
									</div>
								</div>
								<table 
								data-toggle			= "table"
								data-url			= "EmergencyContacts/GetPropertyContact"
								data-query-params	= "prop_id=<?=$data['prop_id'];?>"
								data-search			= "true"
								data-show-refresh	= "true"
								data-show-toggle	= "true"
								data-show-columns	= "true"
								data-toolbar		= "#toolbar"
								id					= "table-emergecy"
								class				= "display table table-striped"
								>
								<thead>
									<tr>
										<th data-field="contact_name">Contact Name</th>
										<th data-field="contact_type">Contact Type</th>
										<th data-field="contact_phone">Contact Color</th>
										<th data-field="contact_icon">Contact Icon</th>
										<th data-field="contact_color" data-cell-style="cellStyle">Contact Color</th>
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