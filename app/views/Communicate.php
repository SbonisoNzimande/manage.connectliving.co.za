<!-- content -->
<div id="content" class="app-content" role="main" ng-controller="CommunicateCtrl">
	<div class="box">
		<!-- Content Navbar -->
		<div class="navbar md-whiteframe-z1 no-radius yellow">
			<!-- Open side - Naviation on mobile -->
			<a md-ink-ripple  data-toggle="modal" data-target="#aside" class="navbar-item pull-left visible-xs visible-sm"><i class="mdi-navigation-menu i-24"></i></a>
			<!-- / -->
			<!-- Page title - Bind to $state's title -->
			<div class="navbar-item pull-left h4">Communicate</div>
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

			<!-- / -->
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
							<input type="hidden" id="company_id" name="company_id" value="<?=$data['company_id'];?>" />
							<input type="hidden" id="prop_name" name="prop_name" value="<?=$data['prop_name'];?>" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Send To</label>
								<div class="col-sm-9">
									<select class="form-control" id="SendTo" name="SendTo">
										<option></option>
										<option>All Residents</option>
										<option>All Trustees</option>
									</select>
								</div>
							</div>
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

		<div class="modal fade" id="BuyMoreCreditsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope" id="BuyCreditsForm" name="BuyCreditsForm" ng-submit="submit_purchase(BuyCreditsForm)">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Buy More SMS Credits</h4> 
						</div>

						<div class="modal-body">
							<div id="buy_credits_err"></div>
							<input type="hidden" name="company_id" value="<?=$data['company_id'];?>" />
							<input type="hidden" name="prop_id" ng-model="prop_id" ng-init="prop_id='<?=$data['prop_id'];?>'" />
							<input type="hidden" name="prop_name" ng-model="prop_name" ng-init="prop_name='<?=$data['prop_name'];?>'" />
							<div class="form-group">
								<label class="col-sm-3 control-label">Number of Credits</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="CreditNumber" name="CreditNumber" ng-model="CreditNumber" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">Amount Due</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" id="AmountDue" name="AmountDue" ng-model="AmountDue"  value="R {{ CreditNumber * CreditCost }}"  disabled="yes" />
								</div>
							</div>
							<div class="clearfix "></div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="BuySMS">Purchase</button>
						</div>
					</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="BuyMoreCreditsSucessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Buy More SMS Credits</h4> 
						</div>

						<div class="modal-body">
						<div class="alert alert-success"><p>Credit successfully bought, the administrator will active your credits momentarily</p></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</form>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="BuyMoreCreditsCanceledModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form class="form-horizontal p-h-xs ng-pristine ng-valid ng-scope">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel">Buy More SMS Credits</h4> 
						</div>

						<div class="modal-body">
						<div class="alert alert-danger"><p>Payment was canceled </p></div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
							<div class="form-group">
								<label class="col-sm-2 control-label">Send To</label>
								<div class="col-sm-10">
									<select class="form-control" id="SendTo" name="SendTo">
										<option></option>
										<option>All Residents</option>
										<option>All Trustees</option>
									</select>
								</div>
							</div>

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



		<div class="modal fade" id="PreviewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h2 class="font-thin ng-binding" id="message_title"></h2>
					</div>
					<div class="modal-body">
						<div class="ng-scope">
							<div class="p-v text-muted">
								<img class="img-circle w-32 m-r-sm" src="../public/images/a0.jpg">
								sent <span class="text-xs ng-binding">on <span id="msg_date"></span></span>
							</div>
							<div class="p-v ng-binding" id="msg_content">
								
							</div>
							<div class="p-v"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>

		<div class="modal fade" id="Delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<input type="hidden" name="deleteID" id="deleteID" />
					<input type="hidden" name="deleteType" id="deleteType" />
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Delete iterm</h4>
					</div>
					<div class="modal-body">
						<div id="delete_err"></div>
						<p>Do you want to delete this?</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="DeleteMsg">Yes</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Hidden -->
		<input type="hidden" id="payment_processed" value="<?=$_GET['payment_processed'];?>" />
		<input type="hidden" id="transaction_id" value="<?=$_GET['transaction_id'];?>" />
		<input type="hidden" id="company_id" value="<?=$_GET['company_id'];?>" />
		<input type="hidden" id="CreditNumber" value="<?=$_GET['CreditNumber'];?>" />
		<input type="hidden" id="prop_id" value="<?=$data['prop_id'];?>" />
		<input type="hidden" id="prop_name" value="<?=$data['prop_name'];?>" />

		<!-- Content -->
		<div class="box-row">
			<div class="box-cell">
				<div class="box-inner padding">
					<div class="card">
						<div class="panel panel-card">
							<div class="p">
								SMS
							</div>
							<div class="panel-body text-center">
								<div class="m-v-lg">
									SMS Balance
									<div class="h2 text-success font-bold sms-balance" >0</div>
								</div>
							</div>
							<div class="b-t b-light p">
								<button class="btn btn-sm btn-addon btn-info"  data-toggle="modal" data-title="Edit" data-target="#BuyMoreCreditsModal"><i class="fa fa-cloud-download"></i>Buy Credits</button>
							</div>
						</div>
					</div>
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
									<div class="ng-scope">
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
			</div>
		</div>
		<!-- / -->
	</div>

</div>
<!-- / content