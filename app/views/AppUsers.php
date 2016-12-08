<!--content -->
<div id="content" class="app-content" role="main">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->
		<div class="navbar-item pull-left h4">App Registrations</div>
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


	<!-- Content -->
	<div class="box-row">
		<div class="box-cell">
			<div class="box-inner padding">
				<div class="col-lg-12">
					<div class="panel panel-default">
						
						<div class="panel-heading b-b b-light">
							<!-- <button class="btn btn-primary" data-toggle="modal" data-title="Edit" data-target="#CreateModal">Create Query</button> -->
						</div>
						<div class="panel-body">
							<table id="queries-table"
							       data-toggle="table"
							       data-show-export="true"
							       data-search="true"
							       data-show-refresh="true"
							       data-show-toggle="true"
							       data-show-columns="true"
							       data-url="Queries/GetAllQueries"
							       data-query-params	= "prop_id=<?=$data['prop_id'];?>&prop_name=<?=$data['prop_name'];?>"
							       class="display table table-striped"
							       data-pagination="false"
							       data-sort-name="queryDate"
							       data-sort-order="desc"
							       data-export-options='
							       		{
							       			"fileName": "Queries",
							       			"worksheetName": "<?=$data['prop_name'];?>",
							       		}
							       '
							>
							    <thead>
							    <tr>
							        <th data-field="queryType" data-sortable="true">Query Type</th>
							        <th data-field="queryUsername" data-sortable="true">User</th>
							        <th data-field="unitNo" data-sortable="true">Unit</th>
							        <th data-field="queryInput" data-sortable="true">Query</th>
							        <th data-field="queryDate" data-sortable="true">Date Logged</th>
							        <th data-field="status" data-sortable="true">Status</th>
							        <th data-field="comment" data-sortable="true">Comment</th>
							        <th data-field="action">Action</th>
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