$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;

	window.show_loader  = function(div) {
	    var loader      = '<div class="loading"> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> </div>';
	     div.html(loader);
	};


	show_cards();

	
	
	var queries_table 	= $('#queries-table').DataTable({
						"dom": 'T<"clear">lfrtip',
						"tableTools": {
						            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
						        },
						
	    				"ajax": host + 'AllQueries/GetAllQueries?' + data,
	    				
	    				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	    				                    if ( aData[5] == "Done" ){
	    				                        $('td', nRow).addClass('light-green-100');
	    				                    }
	    				               }
					});

	window.assign_billing_user = function(assinee_id, id) {

		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get(host + 'Billing/AssignUser', data, function(response){
			// var cls = $("#" +id).closest('tr').attr('class');

			// $("#" +id).closest('tr').css({ "background-color": "#a5d6a7" }).fadeIn('slow').delay(3000).css({ "background-color": "#fff" });

			$("#" +id).closest('tr').addClass('amber-200').fadeIn('slow');
		});
	};

	window.assign_maintanance_user = function(assinee_id, id) {

		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get(host + 'Queries/AssignUser', data, function(response){

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

	$("#MarkDone").click(function(){
		var ID    		= $.trim($("#queryID").val());
		var proceed     = true;

		console.log(ID);

		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID.trim()
		                    };

		    $.post('AllQueries/MarkDone', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>Saved</p></div>';
		        }

		         $("#mark_done_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					queries_table.ajax.reload();
					show_cards();
					// $('#MarkDone').modal('hide');
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});

	window.GetBillingEdit  = function(id) {
		var data 	= 'id='+id; 

		$.get(host + 'Billing/GetBillingInfo', data, function(response){

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

		$.get(host + 'Queries/GetQueriesInfo', data, function(response){

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
			url: host + 'Billing/SaveQuery',  
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
			url: host + 'Billing/EditQuery',  
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
		$.post(host + 'Billing/GetImage', data, function(response){
			
			$("#image_area").html('<img src="'+response.images+'" style="width:100%;" id="LoadedImage" />');
				var left  = 0;

				$('#rotateIMG').click(function(){
					left += 90;
					$('#image_area img').rotate(left);

				});
				
		});
	};

	$.get(host + 'UserPermissions/GetPropertyList', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.PropertyID+ '">' +value.PropertyName+ '</option>';
		});

		$("#PropertyList").html(level_select);
		$("#PropertyListedt").html(level_select);
	});

	$.get(host + 'Billing/GetAllAdminUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.user_id+ '">' +value.full_name+ '</option>';
		});

		$("#AssignTo").html(level_select);
		// $("#AssignToedt").html(level_select);
	});


	$.get(host + 'Billing/GetAllUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.id+ '">' +value.full_name+ '</option>';
		});

		$("#UsersList").html(level_select);
		$("#UsersListedt").html(level_select);
	});



	function show_cards() {

		show_loader($("#card_area"));

		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'AllQueries/GetAllCards',
			error: function () {
				alert("An error occurred getting cards.");
			},
			success: function (dataobj) {

				var card = ''
				$("#card_area").html('');
				var count;

				console.log(dataobj);

				$.each(dataobj, function(key, value){
					//  x % y
					card += '<div class="col-sm-3">';

					card += '	<div class="panel panel-card">';
					card += '		<div class="card-heading">';
					card += '			<h5><strong>' +value.property_name+ '</strong></h5>';
					card += '			<h6><i>Unit Number: ' +value.unit_number+ '</i></h5>';
					card += '		</div>';// Close heading
					card += '		<div class="card-tools">';// Start card tools
					card += '			<ul class="list-inline">';
					card += '				<li class="dropdown">';
					card += '					<a md-ink-ripple data-toggle="dropdown" class="md-btn md-flat md-btn-circle">';
					card += '						<i class="mdi-navigation-more-vert text-md"></i>';
					card += '					</a>';
					card += '					<ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">';
					card += '						<li ><a>Reassign</a></li>';
					card += '						<li class="divider"></li>';
					var admin_users = value.admin_users;
					$.each(admin_users, function(k, v){
						card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '">'+v.full_name+'</a></li>';
					});
					card += '					</ul>';
					card += '				</li>';
					card += '			</ul>';
					card += '		</div>';// End card tools
					
					card += '		<div class="item">';
					card += '			<img src="'+value.image+'" id="imageresource"  class="w-full r-t" data-toggle="modal" data-img-src="'+value.image+'" data-target="#MaxImageModal" />';
					card += '		</div>';
					// End card tools
					card += '		<div class="p">';
					
					card += '			<p><h6><i>' +value.date+ '</i></h6></p>';
					card += '			<p>' +value.query_type+ '</p>';
					card += '			<p>' +value.query+ '</p>';
					card += '		</div>';


					card += '		<p class="m-b p  list-group-item">';
					if (value.status == 'pending') {
					card += '			<button class="btn btn-success btn-xs " data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></button>';	
					};
					

					card += '			<button class="btn btn-default btn-xs " data-title="Edit" data-toggle="modal" data-target="#SMSCommentModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></button>';
					// card += '			<button class="btn btn-default btn-xs " data-title="Edit" data-toggle="modal" data-target="#ViewTimeLineModal" aria-expanded="false"><span class="fa fa-rss"></span></button>';
					card += '			<button class="btn btn-info btn-xs " data-title="Edit" data-toggle="modal" data-target="#EditModal" onclick="GetEdit(56)" aria-expanded="false"><span class="glyphicon glyphicon-pencil"></span></button>';
					card += '		</div>';

					
					card += '	</div>';
					

					count ++;

					
				});

				$("#card_area").html(card);

				$(".mark_done").click(function(e) {

				        $("#QID").val($(this).attr("id"));
				   });

				$(".ass_but").click(function(e) {

				        $("#AID").val($(this).attr("id"));
				   });
				
			}
		});
		

	};


	$('#MaxImageModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var src = $(e.relatedTarget).data('img-src');

		console.log(" --- Maximize image clicked --- ");

		$('#imagepreview').attr('src', src);

	});

	$('#SMSCommentModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var id = $(e.relatedTarget).data('query-id');

		$("input[id*='QueryID']").val(id);
		get_timeline (id);

	});

	$("#SMSForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#SMSForm").serialize();

		var id 		  = $("#QueryID").val();

		console.log('Send SMS: ' + id);
		

	 	$.post('AllQueries/SendSMS', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>SMS Sent</p></div>';
	 		}

	 		$("#send_sms_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline(id);
	 		});


	     }, 'json');// End post
	 	return false;

	});


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
                	output = '<div class="alert alert-success"><p>Submited</p></div>';

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
			url: 'AllQueries/GetTimeline?QueryID=' + id,
			
			error: function () {
				alert("An error occurred getting timeline.");
			},
			success: function (dataobj) {

				var card = '<li class="tl-header"><div class="btn btn-default">Timeline</div></li>'
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
						card += '  		<a class="btn btn-sm btn-addon btn-info" href="AllQueries/DownloadFile/?file_name='+value.file+'&query_id='+value.query_id+'"><i class="fa fa-paperclip"></i>View File</a>';
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
		
});