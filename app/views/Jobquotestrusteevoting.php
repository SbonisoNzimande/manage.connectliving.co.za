<div id="content" class="app-content" role="main" ng-controller="JobsCtrl">
	<!-- Content Navbar -->
	<div class="navbar md-whiteframe-z1 no-radius yellow">
		<!-- Open side - Navigation on mobile -->
		<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
		<!-- / -->
		<!-- Page title - Bind to $state's title -->

		<div class="navbar-item pull-left h4"><img src="../public/images/connect_logo.png" style="width: 180px; height: 45px; margin-top:10px;margin-bottom: 10px;margin-right: 50px;"><span style="text-align: center;">Quotation Voting</span></div>
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
					<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
					<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />

					<div class="card">
						<div class="card-heading">
							<h2>Job Quotes - <?=$data['property_name'];?></h2>
						</div>
						<div class="card-body bg-light lt">

							<div class="col-md-12">
								<div class="panel panel-card">
									<div class="item" style="padding: 10px">
										<p><strong>Trustee Name : <?=$data['trustee_name'];?></strong></p>
										<p><strong>Property Name : <?=$data['property_name'];?></strong></p>
										<p><strong>Property Address : <?=$data['property_address'];?></strong></p>
										<p><strong>Job ID: <?=$data['job_id'];?></strong></p>
										<p><strong>Job Description: <?=$data['job_description'];?></strong></p>
									</div>
								</div>
							</div>
							<!-- Vote Button -->
							<!-- <div class="col-md-12">
								<div class="panel panel-card">
									<div class="item" style="padding: 10px">
										<button class="btn btn-addon btn-info" id="EmailToTrustees" ng-click="email_trustee(q.company_id, q.prop_id, q.job_id, q.quote_id, q.file_name)"><i class="fa fa-thumbs-up" ></i>Vote Up</button>
										<button class="btn btn-addon btn-danger" id="EmailToTrustees" ng-click="email_trustee(q.company_id, q.prop_id, q.job_id, q.quote_id, q.file_name)"><i class="fa fa-thumbs-down" ></i>Vote Down</button>
										
									</div>
								</div>
							</div> -->
							<!-- /Vote Button -->

							<div class="col-md-6">
								<input type="hidden" id="prop_id"  value="<?=$data['prop_id'];?>">
								<input type="hidden" id="user_id" name="user_id" ng-model="UserID" ng-init="UserID = <?=$data['user_id'];?>" >
								<input type="hidden" id="job_id" value="<?=$data['job_id'];?>">
								<input type="hidden" id="full_name"  value="<?=$data['trustee_name'];?>">
								

								<div class="panel panel-card" ng-repeat="q in quote_list">
									<div class="item">
										
										<img ng-src="{{q.file_name | trusted}}" class="w-full r-t" ng-show="q.file_extention == 'png' || q.file_extention == 'jpg'">

										<!-- <div class="some-pdf-container" ng-show="q.file_extention == 'pdf'">
											<pdfjs-viewer ng-src="{{q.file_name | trusted}}"></pdfjs-viewer>
										</div> -->
										<iframe ng-show="q.file_extention === 'pdf'" ng-src="{{google_url + q.file_name | trusted}}" frameborder="no" style="width:100%;height:500px"></iframe>
									</div>
									<div class="p">
										<p>
											<div class="modal-footer">
												<button class="btn btn-addon btn-info" id="EmailToTrustees" ng-click="vote_up (q.quote_id)"><i class="fa fa-thumbs-up" ></i>Accept ({{q.vote_up}})</button>
												<button class="btn btn-addon btn-danger" id="EmailToTrustees" ng-click="vote_down (q.quote_id)"><i class="fa fa-thumbs-down" ></i>Decline ({{q.vote_down}})</button>
											</div>
										</p>
									</div>
								</div>


							</div>

							<!-- Right Column -->
							<div class="col-md-6">
								<div id="scroll-wrap100">
									<ul class="timeline ng-scope" ng-class="{'timeline-center': center}" id="timeline_area">
										<li><div class="content-chat" id="sb_chat"></div></li>
									</ul>
								</div>
							</div>
							<!-- /Right Column -->

							<div class="clearfix"></div>


						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>

	</div>
	<!-- /Content -->

</div>
