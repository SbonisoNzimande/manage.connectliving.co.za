$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;

	var page_name 	= location.pathname.split('/').slice(-1)[0];

	var appId 		= '911FEBB5-2A00-484D-BE22-9B9C4F7868DD';
	// var channelUrl 	= 'sendbird_open_channel_16580_c2b41bec121cf024e56add940f6093b916ee3d1c'; // channel: text_chat_test

	window.show_loader  = function(div) {
	    var loader      = '<div class="loading"> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> </div>';
	     div.html(loader);
	};


	get_date_picker($('#datepicker1'));
	get_date_picker($('#datepicker2'));

	
	function get_date_picker (d) {
		d.datetimepicker({
			format:'YYYY-MM-DD'
		});
	};


	show_cards(prop_id);
	
	// var queries_table 	= $('#queries-table').DataTable({
	// 					"dom": 'T<"clear">lfrtip',
	// 					"tableTools": {
	// 					            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
	// 					        },
						
	//     				"ajax":'Jobs/GetAllJobs?' + data,
	    				
	//     				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	//     				                    if ( aData[5] == "Done" ){
	//     				                        $('td', nRow).addClass('light-green-100');
	//     				                    }
	//     				               }
	// 				});
	get_suppliers ('#EditJobModal [id="JobSupplier"]', prop_id);

	$('#PrintJobModal').on('show.bs.modal', function(e) {// on modal open
		var job_id 	= $(e.relatedTarget).data('job-id'); 

		var data 	= 'prop_id=' + prop_id + '&job_id=' + job_id;

		$.get('Jobs/GetPrintJob', data, function(response){
			$("#print_place").html(response.html);
		});
	});

	

	$("#PrintJob").click(function(){
		$("#print_place").printThis({

				debug: false,              
				importCSS: true,           
				printContainer: true,      
				loadCSS: "../public/styles/print_job.css", 
				importStyle: true,
				pageTitle: "Job Details",             
				removeInline: false   

				}     
			);
	});

	window.assign_billing_user = function(assinee_id, id) {

		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get('Billing/AssignUser', data, function(response){
			$("#" +id).closest('tr').addClass('amber-200').fadeIn('slow');
		});
	};

	window.assign_maintanance_user = function(assinee_id, id) {

		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get('Queries/AssignUser', data, function(response){

			$("#" +id).closest('tr').addClass('amber-200').fadeIn('slow');
		});
	};


	window.SetID  = function(id) {
	    $("#QID").val(id);
	};


	$('#MarkDoneModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id = $(e.relatedTarget).data('query-id'); 
		// var data        = 'ID='+id;

		console.log('ID='+id);

		$('#queryID').val(id);
	});

	$('#CreateJobModal').on('show.bs.modal', function(e) {// on modal open
		// var id 	 = $(e.relatedTarget).data('query-id'); 
		// var data = 'QueryID='+id;
		// console.log(data);

		// $('#JobQueryID').val(id);
		// JobProperty

		// '#CreateJobForm [id *= "JobProperty"]'
		get_porperties ('#CreateJobForm [id *= "JobProperty"]');
		get_suppliers ('#CreateJobForm [id *= "JobSupplier"]', prop_id);
		get_stars ('#CreateJobForm [id *= "JobPriority"]');
		// get_query_byid (data);

		get_masks_date('DateToBeCompleted');

	});

	$('#EditJobModal').on('show.bs.modal', function(e) {// on modal open
		$('#EditJobModal').trigger('reset');
		var id 	 = $(e.relatedTarget).data('query-id'); 
		var data = 'JobID='+id;
		console.log(data);

		$('#EditJobModal [name="JobID"]').val(id);
		// JobProperty
		get_porperties ('#EditJobModal [id *= "JobProperty"]');
		get_suppliers ('#EditJobModal [id *= "JobSupplier"]');
		get_stars ('#EditJobModal [id *= "JobPriority"]');
		get_query_byid (data);

		get_masks_date('DateToBeCompleted');

	});

	$('#DeleteQueryModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id = $(e.relatedTarget).data('job-id'); 
		console.log('ID='+id);

		$('#deleteID').val(id);
	});




	function get_masks_date(div){
		// $($("[id*=" +div+ "]")).attr("data-inputmask", "'alias': 'date'");
		$($("[id*=" +div+ "]")).inputmask("y-m-d",{ "placeholder": "yyyy-mm-dd" }); 
	}


	$("#deleteQueryForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#deleteQueryForm").serialize();

		var id 		  = $("#deleteID").val();

		console.log('Delete: ' + form_data);
		

	 	$.post('Jobs/DeleteJob', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Job Deleted</p></div>';
	 		}

	 		$("#delete_query_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		show_cards(prop_id);
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#MarkDone").click(function(){
		var ID    		= $.trim($("#queryID").val());
		var proceed     = true;

		console.log(ID);

		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID.trim()
		                    };

		    $.post('Jobs/MarkDone', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>Saved</p></div>';
		        }

		         $("#mark_done2_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$('#table-jobs').bootstrapTable('refresh', {
			 			silent: true
			 		});
					show_cards(prop_id);
					// $('#MarkDone').modal('hide');
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});

	window.GetBillingEdit  = function(id) {
		var data 	= 'id='+id; 

		$.get('Billing/GetBillingInfo', data, function(response){

			console.log(response);
			$("#EditID").val(response.id);
			$("#QueryTypeedt").val(response.queryType);
			$("#PropertyListedt").val(response.propId);
			$("#Unitedt").val(response.unitId);
			$("#UsersListedt").val(response.userId);
			$("#Queryedt").val(response.query);
				
		});
	};

	window.GetEdit  = function(id) {
		var data 	= 'id='+id; 

		$.get('Queries/GetQueriesInfo', data, function(response){

			console.log(response);
			$("#EditID").val(response.queryID);
			$("#QueryTypeedt").val(response.queryType);
			$("#PropertyListedt").val(response.propertyID);
			$("#Unitedt").val(response.unitNo);
			$("#UsersListedt").val(response.queryAssignee);
			$("#Queryedt").val(response.queryInput);
				
		});
	};

	$("#CreateQueryForm").submit(function(e){
		e.preventDefault();

		var form_data = new FormData($('#CreateQueryForm')[0]);

		if($("#Image").length != 0) {
			var file_data = $("#Image").prop("files")[0];  
			form_data.append("file", file_data);
		}

		console.log(form_data);

		$.ajax({
			url: 'Billing/SaveQuery',  
			type: 'POST',   
			data: form_data,
			processData: false,
			contentType: false,
			xhrFields: {
				onprogress: function (e) {

				}
			},
			success: function (response) {  
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success"><p>Submited</p></div>';
                }else{
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                $("#create_q_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          
					bill_table.ajax.reload();
					$('#CreateModal').modal('hide');
		         });

	        }
		});

	});


	$("#EditQueryForm").submit(function(e){
		e.preventDefault();

		var form_data = new FormData($('#EditQueryForm')[0]);

		$.ajax({
			url: 'Billing/EditQuery',  
			type: 'POST',   
			data: form_data,
			processData: false,
			contentType: false,
			xhrFields: {
				onprogress: function (e) {

					
				}
			},
			success: function (response) {  
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success"><p>Submited</p></div>';

                }else{
                	$(".progress").hide();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                 $("#edit_q_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          
					bill_table.ajax.reload();
					$('#EditModal').modal('hide');
		         });
	        }
		});

	});

	window.getImage = function(id) {
		var data = 'id='+id; 
		$.post('Billing/GetImage', data, function(response){
			
			$("#image_area").html('<img src="'+response.images+'" style="width:100%;" id="LoadedImage" />');
				var left  = 0;

				$('#rotateIMG').click(function(){
					left += 90;
					$('#image_area img').rotate(left);

				});
				
		});
	};

	get_porperties("#PropertyList");
	get_porperties("#PropertyListedt");
	function get_porperties(iddiv){
		$.get('UserPermissions/GetPropertyList', '', function(response){
			
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				level_select += '<option value="' +value.propertyID+ '">' +value.propertyName+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	function get_suppliers(iddiv, prop_id){
		$.get('AllQueries/GetAllSuppliers?prop_id='+prop_id, '', function(response){
			
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				level_select += '<option value="' +value.id+ '">' +value.company_name+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	function get_stars(iddiv){
		$(iddiv).rating({
	        step: 1,
	        starCaptions: {1: 'Very Low', 2: 'Low', 3: 'Ok', 4: 'High', 5: 'Very High'},
	        starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
		});
	}

	function get_query_byid(data_url){

		$.get('Jobs/GetJobByID', data_url, function(response){
			$('#input-id').rating('update', 3);
			$('#EditJobModal [id= "JobSupplier"]').val(response.supplier_id);
			$('#EditJobModal [name= "JobUnitNo"]').val(response.unit_number);
			$('#EditJobModal [name= "JobStatus"]').val(response.status);
			$('#EditJobModal [name= "JobDescription"]').val(response.description);
			$('#EditJobModal [name= "JobAssignee"]').val(response.job_assignee);
			$('#EditJobModal [name= "JobPriority"]').rating('update', response.priority);
			$('#EditJobModal [name= "AuthorisedBy"]').val(response.authorised_by);
			$('#EditJobModal [name= "DateToBeCompleted"]').val(response.date_tobe_completed);
		});
	}
	

	$.get('Billing/GetAllAdminUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.user_id+ '">' +value.full_name+ '</option>';
		});

		$('#CreateJobForm [name= "JobAssignee"]').html(level_select);
		$('#UpdateJobForm [name= "JobAssignee"]').html(level_select);
		// $("#AssignToedt").html(level_select);
	});


	$.get('Billing/GetAllUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.id+ '">' +value.full_name+ '</option>';
		});

		$("#UsersList").html(level_select);
		$("#UsersListedt").html(level_select);
	});


	$("#NotificationForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#NotificationForm").serialize();
		var id 		  = $("#JobID").val();
		
		

	 	$.post('AllJobs/SendNotification', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#send_notification_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline(id);
	 		});


	     }, 'json');// End post
	 	return false;

	});



	function show_cards(prop_id) {

		show_loader($("#card_area"));

		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'Jobs/GetAllCards?prop_id='+prop_id,
			error: function () {
				alert("An error occurred getting cards.");
			},
			success: function (dataobj) {
				build_cards(dataobj)
				
				
			}
		});
	
	};

	function build_cards(dataobj){
			var card = '';
			$("#card_area").html('');
			var count = 1;

			console.log(dataobj.length);

			$.each(dataobj, function(key, value){
				//  x % y

				console.log(value)

				var query = value.query;
				var supplier_details = {'supplier_id': value.supplier_id, 'supplier_name': value.supplier, 'supplier_email': value.supplier_email, 'supplier_phone_number': value.supplier_phone_number};


				if (!query) {
					query = value.description;
				}

				console.log(supplier_details);


				if (value.open == true && count == 1) {
					card += '	<div class="card-group">';
				}else if(value.open == true){
					card += '	</div><div class="card-group">';
				}

		       	card += '	<div class="card ';
		       	if (value.job_status == 'done') {
		       	card += '			light-green-100">';	
		       	}
		       	else {
		       	card += '">';	
		       	}
		       	card += '		<div class="card-block">';
		       	card += '			<h4 class="card-title">Job ID: ' +value.job_id+ '</h4>';
		       	card += '			<h5 class="card-title">' +value.property_name+'</h5>';
		       	card += '			<p class="card-text"><small class="text-muted">' +value.date+ '</small></p>';
		       	card += '			<p class="card-text"><small class="text-muted">Authorised By:  ' +value.authorised_by+ '</small></p>';
		       	card += '			<p class="card-text"><small class="text-muted">Priority:  ' +value.priority+ '</small></p>';
		       	card += '			<p class="card-text"><small class="text-muted">Job Status:  ' +value.job_status+ '</small></p>';
		       	card += '			<p class="card-text"><small class="text-muted">Supplier:  ' +value.supplier+ '</small></p>';
		       	card += '		</div>';// Card block

		       	if (value.image) {

		       	card += '		<img src="'+value.image+'" id="imageresource"  class="w-full r-t card-img-top" data-toggle="modal" data-img-src="'+value.image+'" data-target="#MaxImageModal" />';
		       	}
		       	card += '		<div class="card-tools">';// Start card tools
		       	card += '			<ul class="list-inline">';
		       	card += '				<li class="dropdown">';
		       	card += '					<a md-ink-ripple data-toggle="dropdown" class="md-btn md-flat md-btn-circle" rel="tooltip" data-original-title="Assign This Query To A Team Member">';
		       	card += '						<i class="mdi-navigation-more-vert text-md"></i>';
		       	card += '					</a>';
		       	card += '					<ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">';
		       	card += '						<li ><a>Reassign</a></li>';
		       	card += '						<li class="divider"></li>';

		       	var admin_users = value.admin_users;
		       	$.each(admin_users, function(k, v){
		       		card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '" data-query-id="' +value.job_id+ '" data-assignee-name="' +v.full_name+ '">'+v.full_name+'</a></li>';
		       	});

		       	card += '					</ul>';
		       	card += '				</li>';
		       	card += '			</ul>';
		       	card += '		</div>';// End card tools
		       	card += '		<div class="card-block">';
		       	card += '			<p class="card-text">'+query+'</p>';
		       	card += '		</div>';// Card block

		       	card += '		<div class="card-block">';// Card block
		       	if (value.status == 'pending') {
		       	card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" rel="tooltip" data-original-title="Mark Query As Done" data-job-id="'+value.job_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';	
		       	};
		       	
		       	card += '			<a href="#" class="btn btn-warning btn-xs" data-title="Email" data-toggle="modal" rel="tooltip" data-original-title="Email Supplier" data-target="#EmailSupplierModal" data-job-id="'+value.job_id+'" data-prop-id="'+value.prop_id+'" data-supplier-id="'+value.supplier_id+'" data-supplier-name="'+value.supplier+'" data-supplier-phone-number="'+value.supplier_phone_number+'" data-supplier-email="'+value.supplier_email+'" data-supplier-unit-number="'+value.unit_number+'" data-property-name="'+value.property_name+'" data-priority="'+value.priority+'" data-description="'+value.description+'" data-authorised-by="'+value.authorised_by+'" data-date-tobe-completed="'+value.date_tobe_completed+'" data-job-status="'+value.job_status+'" aria-expanded="false"><span class="fa fa-envelope"></span></a>';
		       	card += '			<a href="#" class="btn btn-info btn-xs" data-title="Quote" data-toggle="modal" rel="tooltip" data-original-title="View Quotes" data-target="#JobQuotesModal" data-job-id="'+value.job_id+'" data-prop-id="'+value.prop_id+'" data-supplier-id="'+value.supplier_id+'" data-supplier-name="'+value.supplier+'" data-supplier-phone-number="'+value.supplier_phone_number+'" data-supplier-email="'+value.supplier_email+'" data-supplier-unit-number="'+value.unit_number+'" data-property-name="'+value.property_name+'" data-priority="'+value.priority+'" data-description="'+value.description+'" data-authorised-by="'+value.authorised_by+'" data-date-tobe-completed="'+value.date_tobe_completed+'" data-job-status="'+value.job_status+'" aria-expanded="false"><span class="fa fa-paperclip"></span></a>';

		        card += '			<a href="#" class="btn btn-default btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Comment & Communicate" data-target="#SMSCommentModal" data-job-id="'+value.job_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></a>';
				card += '			<a href="#" class="btn btn-primary btn-xs" data-title="Print" data-toggle="modal" rel="tooltip" data-original-title="Print Job" data-target="#PrintJobModal" data-job-id="'+value.job_id+'"  aria-expanded="false"><span class="fa fa-print"></span></a>';
				card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkModal" rel="tooltip" data-original-title="Mark Job" data-job-id="'+value.job_id+'"  data-job-status="'+value.job_status+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';
		       	
		       	card += '			<a href="#" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" rel="tooltip" data-original-title="Remove This Query" data-target="#DeleteQueryModal" data-job-id="'+value.job_id+'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></a>';
		       	card += '		</div>';// Card footer
		       	card += '		</div>';// Card block

				 

				
		       	// if (value.open == true && count != 1) {
		       	// 	card 	+= '	</div>';// close row
		       	// }
			

				// card 	+= '	<div class="row"><div class="col-md-12"></div></div>';// close row
				

				count ++;

				
			});

			$("#card_area").html(card);

			$("[rel='tooltip']").tooltip();

			$(".mark_done").click(function(e) {
			    $("#QID").val($(this).attr("id"));
			});

			$(".ass_but").click(function(e) {
			    $("#AID").val($(this).attr("id"));
			});
	}


	$('#MaxImageModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var src = $(e.relatedTarget).data('img-src');

		console.log(" --- Maximize image clicked --- ");

		$('#imagepreview').attr('src', src);

	});

	// Voting page
	if (page_name === 'JobQuotesTrusteeVoting') {
		$('#sb_chat').html('');


		var job_id 		= $('#job_id').val();

		var user_id   	= $('#user_id').val();
		var full_name 	= $('#full_name').val();

		// $('#UploadQouteForm [name=JobID]').val(job_id);

		
		// user_id
		// full_name

		var appId 			= '911FEBB5-2A00-484D-BE22-9B9C4F7868DD';
		var coverUrl 		= '';
		var userId 			= full_name;
		var channel_name 	= 'sendbird_open_channel_' + prop_id + '_' + job_id;

		var udata 			= { 
								'job_id': job_id, 
								'prop_id': prop_id
							   }

		// initialize
		var sb = new SendBird({
		    appId: appId
		});

		sb.disconnect(function(){
		    // You are disconnected from SendBird.
		});

		sb.connect(user_id, function(chat_user, error) {
			if (error) {
			    console.error(error);
			    return;
			}

			// Check if channel exists


			$.get('Jobs/GetChatURL', udata, function(response){
				
				if (response.status == false) {// url doesnot exists create new channel 
					sb.OpenChannel.createChannel(channel_name, '', '', function(createdChannel, error) {
					    if (error) {
					        console.error(error);
					        return;
					    }

					    sb.updateCurrentUserInfo(full_name, '', function(response, error) {
					      // console.log(response, error);
					    });

					    // onCreated
					    console.log (createdChannel.url);
					    var channelUrl 	= createdChannel.url; // channel: text_chat_test

					    var cdata 			= { 
					    						'job_id': job_id, 
					    						'chat_url': channelUrl
					    					   }


				    	$.post('Jobs/RecordJobQuote', cdata, function(response){

				     		if(response.status == true){
				     			console.log('Successful', response.text);
				     			start_lie_chat (appId, channelUrl);
				     			$( ".user-id" ).children("input").attr('value', user_id);
				     			$( ".nickname" ).children("input").attr('value', full_name);

				     			$('#sb_chat > div > div.login-board > div.btn').removeClass("disabled");
				     			$('#sb_chat > div > div.login-board > div.btn').trigger('click');

				     		}else if(response.status == true){
				     		    // output = '<div class="alert alert-success"><p>Form Deleted</p></div>';
				     		    console.log('Insert Error', response.text);
				     		}

			

				         }, 'json');// End post
					    
					});
				}else{
					console.log('response: ', response);
					var channelUrl = response.chat_url;

					start_lie_chat (appId, channelUrl);
					$( ".user-id" ).children("input").attr('value', user_id);
					$( ".nickname" ).children("input").attr('value', full_name);
					// $( ".chat-board").children(".btn").prop("disabled", false);

					$('#sb_chat > div > div.login-board > div.btn').removeClass("disabled");
					$('#sb_chat > div > div.login-board > div.btn').trigger('click');

					
					// sb.OpenChannel.getChannel(channelUrl, function(channel, error) {
					//     if(error) {
					//         console.error('get channel', error);
					//         return;
					//     }

					//     // Successfully fetched the channel.
					//     console.log('get channel', channel);
					// });
				}


				
				
				
			});

			// start_lie_chat (appId, channelUrl);
			// $( ".user-id" ).children("input").attr('value', user_id);
			// $( ".nickname" ).children("input").attr('value', full_name);

			console.log(chat_user);
		});

		//
		
		

		

		

		console.log('job id', user_id);

		
		// liveChat.start (appId, channelUrl);
	}

	$('#JobQuotesModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		$('#sb_chat').html('');





		var job_id 		= $(e.relatedTarget).data('job-id');

		var user_id   	= $('#user_id').val();
		var full_name 	= $('#full_name').val();

		$('#UploadQouteForm [name=JobID]').val(job_id);

		
		// user_id
		// full_name

		var appId 			= '911FEBB5-2A00-484D-BE22-9B9C4F7868DD';
		var coverUrl 		= '';
		var userId 			= full_name;
		var channel_name 	= 'sendbird_open_channel_' + prop_id + '_' + job_id;

		var udata 			= { 
								'job_id': job_id, 
								'prop_id': prop_id
							   }

		// initialize
		var sb = new SendBird({
		    appId: appId
		});

		sb.disconnect(function(){
		    // You are disconnected from SendBird.
		});

		sb.connect(user_id, function(chat_user, error) {
			if (error) {
			    console.error(error);
			    return;
			}

			// Check if channel exists


			$.get('Jobs/GetChatURL', udata, function(response){
				
				if (response.status == false) {// url doesnot exists create new channel 
					sb.OpenChannel.createChannel(channel_name, '', '', function(createdChannel, error) {
					    if (error) {
					        console.error(error);
					        return;
					    }

					    sb.updateCurrentUserInfo(full_name, '', function(response, error) {
					      // console.log(response, error);
					    });

					    // onCreated
					    console.log (createdChannel.url);
					    var channelUrl 	= createdChannel.url; // channel: text_chat_test

					    var cdata 			= { 
					    						'job_id': job_id, 
					    						'chat_url': channelUrl
					    					   }


				    	$.post('Jobs/RecordJobQuote', cdata, function(response){

				     		if(response.status == true){
				     			console.log('Successful', response.text);
				     			start_lie_chat (appId, channelUrl);
				     			$( ".user-id" ).children("input").attr('value', user_id);
				     			$( ".nickname" ).children("input").attr('value', full_name);

				     			$('#sb_chat > div > div.login-board > div.btn').removeClass("disabled");
				     			$('#sb_chat > div > div.login-board > div.btn').trigger('click');

				     		}else if(response.status == true){
				     		    // output = '<div class="alert alert-success"><p>Form Deleted</p></div>';
				     		    console.log('Insert Error', response.text);
				     		}

			

				         }, 'json');// End post
					    
					});
				}else{
					console.log('response: ', response);
					var channelUrl = response.chat_url;

					start_lie_chat (appId, channelUrl);
					$( ".user-id" ).children("input").attr('value', user_id);
					$( ".nickname" ).children("input").attr('value', full_name);
					// $( ".chat-board").children(".btn").prop("disabled", false);

					$('#sb_chat > div > div.login-board > div.btn').removeClass("disabled");
					$('#sb_chat > div > div.login-board > div.btn').trigger('click');

					
					// sb.OpenChannel.getChannel(channelUrl, function(channel, error) {
					//     if(error) {
					//         console.error('get channel', error);
					//         return;
					//     }

					//     // Successfully fetched the channel.
					//     console.log('get channel', channel);
					// });
				}


				
				
				
			});

			// start_lie_chat (appId, channelUrl);
			// $( ".user-id" ).children("input").attr('value', user_id);
			// $( ".nickname" ).children("input").attr('value', full_name);

			console.log(chat_user);
		});

		//
		
		

		

		

		console.log('job id', user_id);

		
		// liveChat.start (appId, channelUrl);



	});

	function start_lie_chat(a_id, c_url){
		liveChat.start (a_id, c_url);
	}

	get_job_status_enums ('#FilterForm [name=JobStatus]', 'nothing');
	get_job_status_enums ('#CreateJobForm [name=JobStatus]', 'nothing');
	get_job_status_enums ('#UpdateJobForm [name=JobStatus]', 'nothing');

	$('#MarkModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var job_id 		= $(e.relatedTarget).data('job-id');
		var job_status  = $(e.relatedTarget).data('job-status');

		console.log(job_status);


		$("#MarkJobForm [name=jobID]").val(job_id);
		

		// GetJobStatusEnums
		// MarkJobAs
		get_job_status_enums ('#MarkJobAs', job_status);
		


	});

	function get_job_status_enums(iddiv, job_status){
		$.get('Jobs/GetJobStatusEnums', '', function(response){
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				var sel = '';
				if (job_status == value) {
					sel = 'selected';
				}

				level_select += '<option value="'+value+'" '+sel+'>' +value+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	$('#EmailSupplierModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var job_id    		  		= $(e.relatedTarget).data('job-id');
		var supplier_id    		  	= $(e.relatedTarget).data('supplier-id');
		var property_name    		= $(e.relatedTarget).data('property-name');
		var supplier_name  		  	= $(e.relatedTarget).data('supplier-name');
		var supplier_email 		  	= $(e.relatedTarget).data('supplier-email');
		var supplier_phone_number 	= $(e.relatedTarget).data('supplier-phone-number');
		var supplier_unit_number  	= $(e.relatedTarget).data('supplier-unit-number');
		var supplier_priority  	  	= $(e.relatedTarget).data('priority');
		var supplier_description  	= $(e.relatedTarget).data('description');
		var supplier_authorised_by  = $(e.relatedTarget).data('authorised-by');
		var supplier_date_tobe_completed  = $(e.relatedTarget).data('date-tobe-completed');
		var supplier_job_status  	= $(e.relatedTarget).data('job-status');

		$("#EmailForm [name=job_id]").val(job_id);
		$("#EmailForm [name=supplier_id]").val(supplier_id);
		$("#EmailForm [name=supplier_name]").val(supplier_name);
		$("#EmailForm [name=property_name]").val(property_name);
		$("#EmailForm [name=supplier_phone_number]").val(supplier_phone_number);
		$("#EmailForm [name=supplier_unit_number]").val(supplier_unit_number);
		$("#EmailForm [name=supplier_priority]").val(supplier_priority);
		$("#EmailForm [name=supplier_description]").val(supplier_description);
		$("#EmailForm [name=supplier_authorised_by]").val(supplier_authorised_by);
		$("#EmailForm [name=supplier_date_tobe_completed]").val(supplier_date_tobe_completed);
		$("#EmailForm [name=supplier_job_status]").val(supplier_job_status);
		$("#EmailForm [name=supplier_email]").val(supplier_email);


		var data 			 = 'supplier_id=' + supplier_id;
		 	data 			+= '&supplier_name='+ supplier_name;
		 	data 			+= '&property_name='+ property_name;
		 	data 			+= '&supplier_email='+ supplier_phone_number;
		 	data 			+= '&supplier_unit_number='+ supplier_unit_number;
		 	data 			+= '&supplier_priority='+ supplier_priority;
		 	data 			+= '&supplier_description='+ supplier_description;
		 	data 			+= '&supplier_authorised_by='+ supplier_authorised_by;
		 	data 			+= '&supplier_date_tobe_completed='+ supplier_date_tobe_completed;
		 	data 			+= '&supplier_job_status='+ supplier_job_status;
		 	data 			+= '&job_id='+ job_id;


		$.get('Jobs/GetSupplierEmail', data, function(response){
			console.log(response);

			$("#email_place").html(response.html);
		});


		// $('#imagepreview').attr('src', src);

	});


	$("#MarkJobForm").on( 'submit', function(e) {
		e.preventDefault();

		var form_data = $("#MarkJobForm").serialize();

	 	$.post('Jobs/UpdateJobStatus', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Job Updated</p></div>';
	 		}

	 		$("#mark_job_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		show_cards(response.prop_id);
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#FilterForm").on( 'submit', function(e) {
		e.preventDefault();

		var form_data = $("#FilterForm").serialize()+'&prop_id='+prop_id;

	 	$.get('Jobs/GetFilterCards', form_data, function(response){

	 		build_cards (response);

	     }, 'json');// End post
	 	return false;

	});

	$("#EmailForm").on( 'submit', function(e) {
		e.preventDefault();

		var form_data = $("#EmailForm").serialize();

	 	$.post('Jobs/SendSupplierEmail', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Email sent</p></div>';
	 		}

	 		$("#email_place_error").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$('#AssignModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var ass_name = $(e.relatedTarget).data('assignee-name');
		var admin_id = $(e.relatedTarget).data('id');
		var query_id = $(e.relatedTarget).data('query-id');

		$("[id*='assign_name']").html(ass_name);
		$("#AssignForm [name=adminID]").val(admin_id);
		$("#AssignForm [name=queryID]").val(query_id);
		
	});

	$("#AssignForm").on( 'submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#AssignForm").serialize();
		
		console.log('Creating new asset: ' + form_data);

	 	$.post('Jobs/AssignUser', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#mark_done_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-jobs').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#CreateJobForm").on('submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#CreateJobForm").serialize();
		
		console.log('Creating Job: ' + form_data);

	 	$.post('AllQueries/CreateJob', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#error_save_job").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-jobs').bootstrapTable('refresh', {
		 			silent: true
		 		});

		 		show_cards(prop_id);
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#UpdateJobForm").on('submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#UpdateJobForm").serialize();
		
		console.log('Updating Job: ' + form_data);

	 	$.post('Jobs/UpdateJob', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#error_edit_job").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-jobs').bootstrapTable('refresh', {
		 			silent: true
		 		});

		 		show_cards(prop_id);
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$('#SMSCommentModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var id = $(e.relatedTarget).data('job-id');

		console.log(id);

		$("input[id*='JobID']").val(id);
		get_timeline (id);

	});

	$("#SMSForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#SMSForm").serialize();

		var id 		  = $("#JobID").val();

		console.log('Send SMS: ' + id);
		

	 	$.post('Jobs/SendSMS', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>SMS Sent Successfully</p></div>';
	 		}

	 		$("#send_sms_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline(id);
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#SMSSupplierForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#SMSSupplierForm").serialize();

		var id 		  = $("#JobID").val();

		console.log('Send SMS: ' + id);
		

	 	$.post('Jobs/SendSupplierSMS', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>SMS Sent Successfully</p></div>';
	 		}

	 		$("#send_supplier_sms_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline(id);
	 		});


	     }, 'json');// End post
	 	return false;

	});

	console.log('LOCATION:', location.pathname.split('/').slice(-1)[0]);



	$("#UploadQouteForm").on( 'submit', function(ev) {
		ev.preventDefault();

		// var form_data = $("#CommentSMSForm").serialize();

		var form_data = new FormData($('#UploadQouteForm')[0]);

		if($("#UploadQouteForm [id=UploadFile]").length != 0) {
			var file_data = $("#UploadQouteForm [id=UploadFile]").prop("files")[0];  
			form_data.append("file", file_data);
		}

		var id 		  = $("#UploadQouteForm [name=jobID]").val();

		console.log(file_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success">'+response.text+'</p></div>';

                }else{
                	$(".progress").hide();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                var scope = angular.element('#JobQuotesModal').scope();

                $("#upload_quote_text").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          	scope.get_job_quotes(scope.job_id, scope.prop_id);
		          	$("#progress-bar").width('0%');
		         });
			},
			resetForm: true 
		}); 
		return false; 


	});

	// function email_trustee (company_id, prop_id, job_id, quote_id, file_name) {

	// 	console.log (company_id, prop_id, job_id, quote_id, file_name);

	// }


	$("#CommentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		// var form_data = $("#CommentSMSForm").serialize();

		var form_data = new FormData($('#CommentForm')[0]);

		if($("#UploadFile").length != 0) {
			var file_data = $("#UploadFile").prop("files")[0];  
			form_data.append("file", file_data);
		}

		var id 		  = $("#QueryID").val();

		console.log(form_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
			beforeSubmit: function() {
				$("#progress-bar").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("#progress-bar").width(percentComplete + '%');
				$("#progress-bar").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success"><p>Comment Saved. Pushing to Timeline...</p></div>';

                }else{
                	$(".progress").hide();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                $("#send_comment").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          get_timeline(id);
					$('#loader').html('');
		         });
			},
			resetForm: true 
		}); 
		return false; 


	});


	function get_timeline (id) {

		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'Jobs/GetTimeline?JobID=' + id,
			
			error: function () {
				alert("An error occurred getting timeline.");
			},
			success: function (dataobj) {

				var card = '<li class="tl-header"><div class="btn btn-default"><i class="fa fa-clock-o"></i> Timeline</div></li>'
				$("#timeline_area").html('');
				var count;

				console.log(dataobj);

				$.each(dataobj, function(key, value){
					//  x % y

					var clss = '';
					if (value.type == 'SMS') {
						clss = 'bg-primary';
					}

					card += '<li class="tl-item">';
					card += '  <div class="tl-wrap b-info">';
					card += '  		<span class="tl-date text-muted">' +value.date+ '</span>';
					card += '  		<div class="tl-content panel panel-card w-xl w-auto-xs ' +clss+ '">';
					card += '  			<span class="arrow b-white left pull-top"></span>';
					card += '  			<div class="text-lt p-h m-b-sm">' +value.subject+ '</div>';
					card += '  			<div class="p b-t b-light">';
					card += 				value.message;
					card += '  			</div>';
					if (value.file) {
						card += '  	<div class="p">';
						card += '  		<a class="btn btn-sm btn-addon btn-info" href="Jobs/DownloadFile/?file_name='+value.file+'&JobID='+id+'"><i class="fa fa-paperclip"></i>View File</a>';
					}
					
					card += '  		</div>';
					card += '  </div>';
					card += '</li>';
					count ++;

					
				});

				$("#timeline_area").html(card);

				
				
			}
		});
	};


	function loader (){
		
	}

	$('[data-tooltip="true"]').tooltip();
		
});