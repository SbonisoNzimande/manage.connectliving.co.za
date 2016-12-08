<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">Assets</div>
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

	<div class="modal fade" id="addNewAssetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Create Asset</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="CreateAssetForm" id="CreateAssetForm" method="POST">
					<div class="modal-body">
						<div id="create_res_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Supplier / Constructor </label>
							<input type="hidden" name="PropertyID" id="PropertyID" value="<?=$data['prop_id'];?>" />
							<div class="col-sm-9">
								<select class="form-control" id="ContructorID" name="ContructorID">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Asset Name</label>
							<div class="col-sm-9">
								<input type="text" name="AssetName" id="AssetName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Description" rows="5" id="Description"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Make</label>
							<div class="col-sm-9">
								<input type="text" name="Make" id="Make" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Location</label>
							<div class="col-sm-9">
								<input type="text" name="Location" id="Location" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Serial Number</label>
							<div class="col-sm-9">
								<input type="text" name="SerialNumber" id="SerialNumber" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Cost Of Asset</label>
							<div class="col-sm-9">
								<input type="text" name="CostOfAsset" id="CostOfAsset" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Inspected</label>
							<div class="col-sm-9">
								<div class='input-group date' id='lastinspecteddatepicker'>
									<input type='text' class="form-control" id="LastInspected" name="LastInspected" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Inspection Due Date</label>
							<div class="col-sm-9">
								<div class='input-group date' id='inspectionduedatepicker'>
									<input type='text' class="form-control" id="InspectionDueDate" name="InspectionDueDate" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
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

	<div class="modal fade" id="EditAssetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Edit Asset</h4>
				</div>
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" name="EditAssetForm" id="EditAssetForm" method="POST">
					<div class="modal-body">
						<div id="edit_res_err"></div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Supplier / Constructor </label>
							<input type="hidden" name="AssetID" id="AssetID" />
							<div class="col-sm-9">
								<select class="form-control" id="ContructorID" name="ContructorID">
									<option></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Asset Name</label>
							<div class="col-sm-9">
								<input type="text" name="AssetName" id="AssetName" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="Description" rows="5" id="Description"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Make</label>
							<div class="col-sm-9">
								<input type="text" name="Make" id="Make" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Location</label>
							<div class="col-sm-9">
								<input type="text" name="Location" id="Location" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Serial Number</label>
							<div class="col-sm-9">
								<input type="text" name="SerialNumber" id="SerialNumber" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Cost Of Asset</label>
							<div class="col-sm-9">
								<input type="text" name="CostOfAsset" id="CostOfAsset" class="form-control" />
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Inspected</label>
							<div class="col-sm-9">
								<div class='input-group date' id='lastinspecteddatepicker'>
									<input type='text' class="form-control" id="LastInspected" name="LastInspected" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Inspection Due Date</label>
							<div class="col-sm-9">
								<div class='input-group date' id='inspectionduedatepicker'>
									<input type='text' class="form-control" id="InspectionDueDate" name="InspectionDueDate" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
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

	<div class="modal fade" id="QRCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="archive_id" id="archive_id" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">QR Code For This Asset</h4>
				</div>
				<div class="modal-body center-align">
					<div class="row" id="qr-print">

						<div class="col-lg-6 col-lg-offset-3 text-center">
							<img src="" id="QRCodArea" class="img-responsive img-center" width="50%"/>
							<h4 id="ass_name">Asset Name</h4>
							<h4 id="ass_loc">Location</h4>
							<h4 id="ass_ser">Serial Number</h4>

						</div>
					</div>
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="PrintQR"><span class="glyphicon glyphicon-print"></span>&nbsp;Print</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="DeleteAssetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<input type="hidden" name="deleteID" id="deleteID" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Delete Document</h4>
				</div>
				<div class="modal-body">
					<div id="delete_ass_err"></div>
					<p>Do you want to perform this action ?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
					<button type="button" class="btn btn-primary" id="DeleteAsset">Yes</button>
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
						<div class="panel-heading b-b b-light">
							You are managing <?=$data['prop_name'];?>
						</div>
						<div class="panel-body">
							<div id="toolbar">
								<div class="form-inline" role="form">
									<button id="add_new" type="button" class="btn btn-primary" data-target="#addNewAssetModal" data-toggle="modal" >Add New</button>
								</div>
							</div>

							<table 
							data-toggle			= "table"
							data-url			= "Assets/GetTable"
							data-query-params	= "prop_id=<?=$data['prop_id'];?>"
							data-search			= "true"
							data-show-refresh	= "true"
							data-show-toggle	= "true"
							data-show-columns	= "true"
							data-toolbar		= "#toolbar"

							data-pagination		= "true"
							id 					= "contractors-table"
							class 				= "display table table-striped"
							>
							<thead>
								<tr>
									<th data-field = "supplier_name">Supplier/Contractor</th>
									<th data-field = "asset_name">Asset Name</th>
									<th data-field = "description">Description</th>
									<th data-field = "make">Make</th>
									<th data-field = "location">Location</th>
									<th data-field = "serial_number">Serial Number</th>
									<th data-field = "cost_of_asset">Cost of Asset</th>
									<th data-field = "last_inspected">Last Inspected</th>

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
<!-- /Content -->

</div>
<!-- /content