<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Documentation</div>
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
	<!-- /Content Navbar Documentation -->

	<!-- Dublicate -->
	<div class="modal fade" id="DuplicateDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Duplicate Document</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="DuplicateDocumentForm" id="DuplicateDocumentForm" method="POST">
					<div class="modal-body">
						<div id="duplicate_error"></div>
						<input type="hidden" id="DuplicateDocumentID" name="DuplicateDocumentID" />
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
	<!-- /Dublicate -->

	<div class="modal fade" id="UploadDocumentationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload Documentation</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="UploadDocumentForm" id="UploadDocumentForm" method="POST" action="Documentation/UploadDocument">
						<div id="error_save_doc"></div>
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

	<div class="modal fade" id="AddDocumentationTypesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Document Category</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateDocumentTypeForm" id="CreateDocumentTypeForm" method="POST" >
						<div id="error_save_doc_type"></div>
						<input type="hidden" name="prop_id" id="PropertyID" value="<?=$data['prop_id'];?>" />
						<div class="form-group">
							<label for="DocumentTypeName" class="col-sm-3 control-label">Document Type Name</label>
							<div class="col-sm-9">
								<input type="text" name="DocumentTypeName" id="DocumentTypeName" class="form-control" />
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

	<div class="modal fade" id="EditDocumentationTypesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Document Category</h4>
				</div>
				<div class="modal-body">

					<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditDocumentTypeForm" id="EditDocumentTypeForm" method="POST" >
						<div id="error_edit_doc_type"></div>
						<input type="hidden" name="CategoryID" id="CategoryID" />
						<div class="form-group">
							<label for="DocumentTypeName" class="col-sm-3 control-label">Document Type Name</label>
							<div class="col-sm-9">
								<input type="text" name="DocumentTypeName" id="DocumentTypeName" class="form-control" />
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

	<div class="modal fade" id="DeleteDocumentTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="DelCategoryID" id="DelCategoryID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Document Type</h4>
				</div>
				<div class="modal-body">
					<div id="delete_doc_type_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteDocumentType">Yes</button>
				</div>
			</div>
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
									<a href="" data-toggle="tab" data-target="#tab_documents" aria-expanded="true">Documents</a>
								</li>
								<li class="">
									<a href="" data-toggle="tab" data-target="#tab_categories" aria-expanded="true">Documents Categories</a>
								</li>

							</ul>

							<div class="tab-content p m-b-md clear b-t b-t-2x">
								<div role="tabpanel" class="tab-pane animated fadeInDown active" id="tab_documents">

									<div id="toolbar">
										<div class="form-inline" role="form">
											<button id="add_new" type="button" class="btn btn-primary" data-target="#UploadDocumentationModal" data-toggle="modal" >Add New</button>
										</div>
									</div>
									<table 
									data-toggle="table"
									data-url="Documentation/GetTable"
									data-query-params	= "prop_id=<?=$data['prop_id'];?>"
									data-search="true"
									data-show-refresh="true"
									data-show-toggle="true"
									data-show-columns="true"
									data-toolbar="#toolbar"
									id="table-documents"
									class="display table table-striped"
									>
									<thead>
										<tr>
											<th data-field="document_type">Document Type</th>
											<th data-field="property_name">Property</th>
											<th data-field="doc_name">Document Name</th>
											<th data-field="created">created</th>
											<th data-field="buttons">Action</th>
										</tr>
									</thead>
								</table>

							</div>
							<div role="tabpanel" class="tab-pane animated fadeInDown" id="tab_categories">
								<div id="toolbar2">
									<div class="form-inline" role="form">
										<button id="add_new" type="button" class="btn btn-primary" data-target="#AddDocumentationTypesModal" data-toggle="modal" >Add New</button>
									</div>
								</div>
								<table 
								data-toggle="table"
								data-url="Documentation/GetAllDocumentTypesTable"
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
<!-- /Content -->

</div>
<!-- /content