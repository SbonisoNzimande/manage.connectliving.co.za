<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Contractors And Suppliers</div>
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


	<div class="modal fade" id="SupplierThumbnailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload Thumbnail</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadCompanyLogoForm" id="UploadCompanyLogoForm" method="POST" action="ContractorsAndSuppliers/UploadThumbnail">
						<div id="error_company_logo"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<input type="hidden" name="supplier_id" id="supplier_id"  />

						<div class="form-group">
							<label class="col-sm-3 control-label">File</label>
							<div class="col-sm-9">
								<input type="file" name="UploadLogoFile" id="UploadLogoFile" />
								<div id="progress-div"><div id="progress-bar"></div></div>
								<div id="targetLayer"></div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="UploadCLogo"><i class="fa fa-upload"></i>Upload</button>
						</div>
					</form>
					<!-- /Left Column -->
					<!-- Right Column -->
					<div class="col-md-6">
						<div id="scroll-wrap">
							<ul class="timeline ng-scope" ng-class="{'timeline-center': center}" id="timeline_area">
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

	<div class="modal fade" id="DuplicateContractorsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Duplicate Contractors</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="DuplicateContractorsForm" id="DuplicateContractorsForm" method="POST">
					<div class="modal-body">
						<div id="duplicate_error"></div>
						<input type="hidden" id="DuplicateContractorID" name="DuplicateContractorID" />
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

	<div class="modal fade" id="addNewContractorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Contractor</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateContractorForm" id="CreateContractorForm" method="POST">
					<div class="modal-body">
						<div id="create_res_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Service Type</label>
							<input type="hidden" name="PropertyID" value="<?=$data['prop_id'];?>" />
							<div class="col-sm-9">
								<select class="form-control" id="ServiceType" name="ServiceType">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Company Name</label>
							<div class="col-sm-9">
								<input type="text" name="CompanyName" id="CompanyName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Address</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Address" rows="5" id="Address"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone Number</label>
							<div class="col-sm-9">
								<input type="text" name="PhoneNumber" id="PhoneNumber" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
								<input type="text" name="Email" id="Email" class="form-control" />
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

	<div class="modal fade" id="EditContractorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Contractor</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditContractorForm" id="EditContractorForm" method="POST">
					<div class="modal-body">
						<div id="edit_contractor_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Service Type</label>
							<input type="hidden" name="ContractorID" id="ContractorID" />
							<div class="col-sm-9">
								<select class="form-control" id="ServiceType" name="ServiceType">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Company Name</label>
							<div class="col-sm-9">
								<input type="text" name="CompanyName" id="CompanyName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Address</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Address" rows="5" id="Address"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone Number</label>
							<div class="col-sm-9">
								<input type="text" name="PhoneNumber" id="PhoneNumber" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
								<input type="text" name="Email" id="Email" class="form-control" />
							</div>
						</div>

						<div class="clearfix "></div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="UpdateContractor">Update</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>

	<div class="modal fade" id="DeleteContractorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="deleteID" id="deleteID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Document</h4>
				</div>
				<div class="modal-body">
					<div id="delete_cont_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteContractor">Yes</button>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="AddSupplierTypesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Supplier Category</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateSupplierTypeForm" id="CreateSupplierTypeForm" method="POST" >
						<div id="error_save_doc_type"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="SupplierTypeName" class="col-sm-3 control-label">Supplier Type</label>
							<div class="col-sm-9">
								<input type="text" name="SupplierTypeName" id="SupplierTypeName" class="form-control" />
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="SaveCategory"><i class="fa fa-upload"></i>Save</button>
						</div>
					</form>

					<div class="clearfix"></div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>

	<div class="modal fade" id="EditSupplierTypesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Supplier Category</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditSupplierTypeForm" id="EditSupplierTypeForm" method="POST" >
						<div id="error_edit_doc_type"></div>
						<input type="hidden" name="CategoryID" id="CategoryID" />
						<div class="form-group">
							<label for="SupplierTypeName" class="col-sm-3 control-label">Supplier Type Name</label>
							<div class="col-sm-9">
								<input type="text" name="SupplierTypeName" id="SupplierTypeName" class="form-control" />
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-addon btn-info" id="SaveCategory"><i class="fa fa-upload"></i>Update</button>
						</div>
					</form>

					<div class="clearfix"></div>

				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
	</div>
	<div class="modal fade" id="DeleteSupplierTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="DelCategoryID" id="DelCategoryID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Supplier Type</h4>
				</div>
				<div class="modal-body">
					<div id="delete_doc_type_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteSupplierType">Yes</button>
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
						<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
						<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />
						<div class="md-whiteframe-z0 bg-white">

							<ul class="nav nav-lines nav-tabs nav-justified">
								<li class="active">
									<a href="" data-toggle="tab" data-target="#tab_suppliers" aria-expanded="true">Suppliers</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#tab_suppliers_categories" aria-expanded="true">Suppliers Categories</a>
								</li>

							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="tab_suppliers">

									<div id="toolbar">
										<div class="form-inline" role="form">
											<button id="add_new" type="button" class="btn btn-primary" data-target="#addNewContractorModal" data-toggle="modal" >Add New</button>
										</div>
									</div>
									<table 
									data-toggle="table"
									data-url="ContractorsAndSuppliers/GetTable"
									data-query-params="prop_id=<?=$data['prop_id'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar"

									data-pagination="true"
									id="contractors-table"
									class="display table table-striped"
									>
									<thead>
										<tr>
											<th data-field = "service_name">Service Name</th>
											<th data-field = "company_name">Company Name</th>
											<th data-field = "address">Address</th>
											<th data-field = "phone_number">Phone Number</th>
											<th data-field = "email">Email</th>
											

											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>

							</div>
							<div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_suppliers_categories">
								<div id="toolbar2">
									<div class="form-inline" role="form">
										<button id="add_new" type="button" class="btn btn-primary" data-target="#AddSupplierTypesModal" data-toggle="modal" >Add New</button>
									</div>
								</div>
								<table 
								data-toggle="table"
								data-url="ContractorsAndSuppliers/GetAllSupplierTypesTable"
								data-query-params	= "prop_id=<?=$data['prop_id'];?>"
								data-search="true"
								data-show-refresh="true"
								data-show-toggle="true"
								data-show-columns="true"
								data-toolbar="#toolbar2"
								data-sort-name="queryDate"
								data-sort-order="desc"
								id="table-category"
								class="display table table-striped"
								data-row-style="rowStyle"
								>
								<thead>
									<tr>
										<th data-field="document_name" data-sortable="true">Name</th>
										<th data-field="created" data-sortable="true">Created</th>
										<th data-field="buttons">Action</th>
									</tr>
								</thead>
							</table>
						</div>





					</div>
				</div>



				<!-- <div class="panel-heading b-b b-light">
					You are managing <?=$data['prop_name'];?>
				</div>
				<div class="panel-body"></div> -->
			</div>
		</div>
	</div>
</div>

</div>
<!-- /Content -->

</div>
<!-- /content