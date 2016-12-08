$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;

	window.show_loader  = function(div) {
	    var loader      = '<div class="loading"> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> </div>';
	     div.html(loader);
	};

	var pagenum = show_initial_cards(1);
	// var pagenum = $('#page-num').val();
	$('#page-selection').bootpag ({
			total: pagenum,
			maxVisible: 20,
    }).on("page", function(event, num){
     	var pagenum = $('#page-num').val();
        show_initial_cards (num);
        $(this).bootpag ({total: pagenum, maxVisible: 20});
     });


    
	 
	
	// var queries_table 	= $('#queries-table').DataTable({
	// 					"dom": 'T<"clear">lfrtip',
	// 					"tableTools": {
	// 					            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
	// 					        },
						
	//     				"ajax": host + 'AllQueries/GetAllQueries?' + data,
	    				
	//     				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	//     				                    if ( aData[5] == "Done" ){
	//     				                        $('td', nRow).addClass('light-green-100');
	//     				                    }
	//     				               }
	// 				});

	window.assign_billing_user = function(assinee_id, id) {

		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get(host + 'Billing/AssignUser', data, function(response){
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

	$('#MarkMaterialsRequiredModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id = $(e.relatedTarget).data('query-id'); 
		// var data        = 'ID='+id;

		console.log('ID='+id);

		$('#querymID').val(id);
	});

	$('#CreateJobModal').on('show.bs.modal', function(e) {// on modal open
		var id 	 = $(e.relatedTarget).data('query-id'); 
		var data = 'QueryID='+id;
		console.log(data);

		$('#JobQueryID').val(id);
		// JobProperty
		get_porperties ("#JobProperty");
		get_suppliers ("#JobSupplier");
		get_stars ("#JobPriority");
		get_query_byid (data);

		get_masks_date('DateToBeCompleted');

	});

	$('#DeleteQueryModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id = $(e.relatedTarget).data('query-id'); 
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

	 	$.post('AllQueries/DeleteQuery', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Query Deleted</p></div>';
	 		}

	 		$("#delete_query_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		show_initial_cards(1);
	 	 		$('#DeleteQueryModal').modal('hide');
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

		    $.post('AllQueries/MarkDone', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>Saved</p></div>';
		        }

		         $("#mark_done_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){

					$('#table-pending').bootstrapTable('refresh', {
			 			silent: true
			 		});
					show_initial_cards(1);
					// $('#MarkDone').modal('hide');
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});

	$("#MarkMaterialsRequired").click(function(){
		var ID    		= $.trim($("#querymID").val());
		var proceed     = true;

		console.log(ID);

		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID.trim()
		                    };

		    $.post('AllQueries/MaterialsRequired', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>Saved</p></div>';
		        }

		         $("#mark_materials_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){

					$('#table-pending').bootstrapTable('refresh', {
			 			silent: true
			 		});
					show_initial_cards(1);
					// $('#MarkDone').modal('hide');
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkMaterialsRequired").effect('shake', 900 );
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

	function get_suppliers(iddiv){
		$.get('AllQueries/GetAllSuppliers', '', function(response){
			
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
		$.get('AllQueries/GetQueryByID', data_url, function(response){
			
			$('#JobProperty').val(response.propertyID);
			$('#JobUnitNo').val(response.unitNo);
			$('#JobImageName').val(response.queryImage);
			$('#JobDescription').val(response.queryInput);
		});
	}
	

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



	function show_cards(page) {

		show_loader($("#card_area"));

		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'AllQueries/GetAllCards?current_page='+page,
			error: function () {
				alert("An error occurred getting cards.");
			},
			success: function (dataobj) {

				var card = '';
				 $("#card_area").html('');
				var count = 1;

				console.log(dataobj.length);

				$.each(dataobj, function(key, value){
					//  x % y

					if (value.open == true && count == 1) {
						card += '	<div class="card-group">';
					}else if(value.open == true){
						card += '	</div><div class="card-group">';
					}

			       	card += '	<div class="card ';
			       	if (value.status == 'done') {
			       	card += '			light-green-100">';	
			       	}else if (value.status == 'materials required') {
			       	card += '			card-warning">';	
			       	}
			       	else {
			       	card += '">';	
			       	}
			       	card += '		<div class="card-block">';
			       	card += '			<h4 class="card-title">' +value.query_type+ ' - ' +value.unit_number+ '</h4>';
			       	card += '			<h5 class="card-title">' +value.property_name+'</h5>';
			       	card += '			<h5 class="card-title">submited by: ' +value.full_name+'</h5>';
			       	card += '			<p class="card-text"><small class="text-muted">' +value.date+ '</small></p>';
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
			       		card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '" data-query-id="' +value.query_id+ '" data-assignee-name="' +v.full_name+ '">'+v.full_name+'</a></li>';
			       	});

			       	card += '					</ul>';
			       	card += '				</li>';
			       	card += '			</ul>';
			       	card += '		</div>';// End card tools
			       	card += '		<div class="card-block">';
			       	card += '			<p class="card-text">'+value.query+'</p>';
			       	card += '		</div>';// Card block

			       	card += '		<div class="card-block">';// Card block
			       	if (value.status == 'pending') {
			       		card += '			<a href="#" class="btn btn-warning btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkMaterialsRequiredModal" rel="tooltip" data-original-title="Materials Required" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-compressed"></span></a>';	
			       		card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" rel="tooltip" data-original-title="Mark Query As Done" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';	
			       	};
			       	
			       	card += '			<a href="#" class="btn btn-default btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Comment & Communicate" data-target="#SMSCommentModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></a>';

			       	card += '			<a href="#" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Convert Query to Job" data-target="#CreateJobModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-briefcase"></span></a>';
			       	
			       	card += '			<a href="#" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" rel="tooltip" data-original-title="Remove This Query" data-target="#DeleteQueryModal" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></a>';
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
		});
	
	};

	function show_initial_cards(page) {

		show_loader($("#card_area"));


		var dataobj = $.ajax({type: "GET", url: 'AllQueries/GetPageCards?current_page='+page, async: false}).responseText;
		dataobj  = jQuery.parseJSON(dataobj);

		var card 	= '';
		$("#card_area").html('');
		var count 	= 1;

		console.log('count :' + dataobj[0].count_pages);
		$('#page-num').val(dataobj[0].count_pages);

		$.each(dataobj, function(key, value){
			//  x % y

			if (value.open == true && count == 1) {
				card += '	<div class="card-group">';
			}else if(value.open == true){
				card += '	</div><div class="card-group">';
			}

	       	card += '	<div class="card ';
	       	if (value.status == 'done') {
	       	card += '			light-green-100">';	
	       	}else if (value.status == 'materials required') {
	       	card += '			card-warning">';	
	       	}
	       	else {
	       	card += '">';	
	       	}
	       	card += '		<div class="card-block">';
	       	card += '			<h4 class="card-title">' +value.query_type+ ' - ' +value.unit_number+ '</h4>';
	       	card += '			<h5 class="card-title">' +value.property_name+'</h5>';
	       	card += '			<h5 class="card-title">submited by: ' +value.full_name+'</h5>';
	       	card += '			<p class="card-text"><small class="text-muted">' +value.date+ '</small></p>';
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
	       		card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '" data-query-id="' +value.query_id+ '" data-assignee-name="' +v.full_name+ '">'+v.full_name+'</a></li>';
	       	});

	       	card += '					</ul>';
	       	card += '				</li>';
	       	card += '			</ul>';
	       	card += '		</div>';// End card tools
	       	card += '		<div class="card-block">';
	       	card += '			<p class="card-text">'+value.query+'</p>';
	       	card += '		</div>';// Card block

	       	card += '		<div class="card-block">';// Card block
	       	if (value.status == 'pending') {
	       	card += '			<a href="#" class="btn btn-warning btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkMaterialsRequiredModal" rel="tooltip" data-original-title="Materials Required" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-compressed"></span></a>';	
	       	card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" rel="tooltip" data-original-title="Mark Query As Done" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';	
	       	};
	       	
	       	card += '			<a href="#" class="btn btn-default btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Comment & Communicate" data-target="#SMSCommentModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></a>';

	       	card += '			<a href="#" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Convert Query to Job" data-target="#CreateJobModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-briefcase"></span></a>';
	       	
	       	card += '			<a href="#" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" rel="tooltip" data-original-title="Remove This Query" data-target="#DeleteQueryModal" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></a>';
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

		console.log(dataobj[0].count_pages)



		// $.ajax({
		// 	type: "GET",
		// 	dataType: 'json',
		// 	url: 'AllQueries/GetPageCards?current_page='+page,
		// 	error: function () {
		// 		alert("An error occurred getting cards.");
		// 	},
		// 	success: function (dataobj) {

		// 		var card = '';
		// 		$("#card_area").html('');
		// 		var count = 1;

		// 		console.log('count :' + dataobj[0].count_pages);
		// 		$('#page-num').val(dataobj[0].count_pages);

		// 		$.each(dataobj, function(key, value){
		// 			//  x % y

		// 			if (value.open == true && count == 1) {
		// 				card += '	<div class="card-group">';
		// 			}else if(value.open == true){
		// 				card += '	</div><div class="card-group">';
		// 			}

		// 	       	card += '	<div class="card ';
		// 	       	if (value.status == 'done') {
		// 	       	card += '			light-green-100">';	
		// 	       	}
		// 	       	else {
		// 	       	card += '">';	
		// 	       	}
		// 	       	card += '		<div class="card-block">';
		// 	       	card += '			<h4 class="card-title">' +value.query_type+ ' - ' +value.unit_number+ '</h4>';
		// 	       	card += '			<h5 class="card-title">' +value.property_name+'</h5>';
		// 	       	card += '			<p class="card-text"><small class="text-muted">' +value.date+ '</small></p>';
		// 	       	card += '		</div>';// Card block

		// 	       	if (value.image) {

		// 	       	card += '		<img src="'+value.image+'" id="imageresource"  class="w-full r-t card-img-top" data-toggle="modal" data-img-src="'+value.image+'" data-target="#MaxImageModal" />';
		// 	       	}
		// 	       	card += '		<div class="card-tools">';// Start card tools
		// 	       	card += '			<ul class="list-inline">';
		// 	       	card += '				<li class="dropdown">';
		// 	       	card += '					<a md-ink-ripple data-toggle="dropdown" class="md-btn md-flat md-btn-circle" rel="tooltip" data-original-title="Assign This Query To A Team Member">';
		// 	       	card += '						<i class="mdi-navigation-more-vert text-md"></i>';
		// 	       	card += '					</a>';
		// 	       	card += '					<ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">';
		// 	       	card += '						<li ><a>Reassign</a></li>';
		// 	       	card += '						<li class="divider"></li>';

		// 	       	var admin_users = value.admin_users;
		// 	       	$.each(admin_users, function(k, v){
		// 	       		card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '" data-query-id="' +value.query_id+ '" data-assignee-name="' +v.full_name+ '">'+v.full_name+'</a></li>';
		// 	       	});

		// 	       	card += '					</ul>';
		// 	       	card += '				</li>';
		// 	       	card += '			</ul>';
		// 	       	card += '		</div>';// End card tools
		// 	       	card += '		<div class="card-block">';
		// 	       	card += '			<p class="card-text">'+value.query+'</p>';
		// 	       	card += '		</div>';// Card block

		// 	       	card += '		<div class="card-block">';// Card block
		// 	       	if (value.status == 'pending') {
		// 	       	card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" rel="tooltip" data-original-title="Mark Query As Done" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';	
		// 	       	};
			       	
		// 	       	card += '			<a href="#" class="btn btn-default btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Comment & Communicate" data-target="#SMSCommentModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></a>';

		// 	       	card += '			<a href="#" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Convert Query to Job" data-target="#CreateJobModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-briefcase"></span></a>';
			       	
		// 	       	card += '			<a href="#" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" rel="tooltip" data-original-title="Remove This Query" data-target="#DeleteQueryModal" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></a>';
		// 	       	card += '		</div>';// Card footer
		// 	       	card += '		</div>';// Card block

					 

					
		// 	       	// if (value.open == true && count != 1) {
		// 	       	// 	card 	+= '	</div>';// close row
		// 	       	// }
				

		// 			// card 	+= '	<div class="row"><div class="col-md-12"></div></div>';// close row
					

		// 			count ++;

					
		// 		});

		// 		$("#card_area").html(card);
		// 		$("[rel='tooltip']").tooltip();

		// 		$(".mark_done").click(function(e) {
		// 		    $("#QID").val($(this).attr("id"));
		// 		});

		// 		$(".ass_but").click(function(e) {
		// 		    $("#AID").val($(this).attr("id"));
		// 		});

		// 		console.log(dataobj[0].count_pages)

				


				
		// 	}
		// });

		return dataobj[0].count_pages;
	
	};

	// Filter
	$("#FilterForm").submit(function(e){// Filter clicked
		e.preventDefault();
		show_loader($("#card_area"));

		var data  	=  $("#FilterForm").serialize();// Get data


		$.get('AllQueries/FilterCards', data, function(dataobj){

			var card = '';
			$("#card_area").html('');
			var count = 1;

			console.log(dataobj.length);

			$.each(dataobj, function(key, value){
				//  x % y

				if (value.open == true && count == 1) {
					card += '	<div class="card-group">';
				}else if(value.open == true){
					card += '	</div><div class="card-group">';
				}

		       	card += '	<div class="card ';
		       	if (value.status == 'done') {
		       	card += '			light-green-100">';	
		       	}else if (value.status == 'materials required') {
		       	card += '			card-warning">';	
		       	}
		       	else {
		       	card += '">';	
		       	}
		       	card += '		<div class="card-block">';
		       	card += '			<h4 class="card-title">' +value.query_type+ ' - ' +value.unit_number+ '</h4>';
		       	card += '			<h5 class="card-title">' +value.property_name+'</h5>';
		       	card += '			<h5 class="card-title">submited by: ' +value.full_name+'</h5>';
		       	card += '			<p class="card-text"><small class="text-muted">' +value.date+ '</small></p>';
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
		       		card += ' 						<li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" data-id="' +v.adminID+ '" data-query-id="' +value.query_id+ '" data-assignee-name="' +v.full_name+ '">'+v.full_name+'</a></li>';
		       	});

		       	card += '					</ul>';
		       	card += '				</li>';
		       	card += '			</ul>';
		       	card += '		</div>';// End card tools
		       	card += '		<div class="card-block">';
		       	card += '			<p class="card-text">'+value.query+'</p>';
		       	card += '		</div>';// Card block

		       	card += '		<div class="card-block">';// Card block
		       	if (value.status == 'pending') {
		       		card += '			<a href="#" class="btn btn-warning btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkMaterialsRequiredModal" rel="tooltip" data-original-title="Materials Required" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-compressed"></span></a>';	
		       		card += '			<a href="#" class="btn btn-success btn-xs" data-title="Edit" data-toggle="modal" data-target="#MarkDoneModal" rel="tooltip" data-original-title="Mark Query As Done" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-ok"></span></a>';	
		       	};
		       	
		       	card += '			<a href="#" class="btn btn-default btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Comment & Communicate" data-target="#SMSCommentModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-comment-o"></span></a>';

		       	card += '			<a href="#" class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" rel="tooltip" data-original-title="Convert Query to Job" data-target="#CreateJobModal" data-query-id="'+value.query_id+'"  aria-expanded="false"><span class="fa fa-briefcase"></span></a>';
		       	
		       	card += '			<a href="#" class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" rel="tooltip" data-original-title="Remove This Query" data-target="#DeleteQueryModal" data-query-id="'+value.query_id+'" aria-expanded="false"><span class="glyphicon glyphicon-trash"></span></a>';
		       	card += '		</div>';// Card footer
		       	card += '		</div>';// Card block

			
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
		});
	});


	$('#MaxImageModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var src = $(e.relatedTarget).data('img-src');

		console.log(" --- Maximize image clicked --- ");

		$('#imagepreview').attr('src', src);

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
		
		console.log('Reassigning query: ' + form_data);

	 	$.post('AllQueries/AssignUser', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Re-assigned</p></div>';
	 		}

	 		$("#ressign_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#AssignModal').modal('hide');
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#CreateJobForm").on('submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#CreateJobForm").serialize();
		
		// console.log('Creating Job: ' + form_data);

	 	$.post('AllQueries/CreateJob', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#error_save_job").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 	// 	$('#contractors-table').bootstrapTable('refresh', {
		 		// 	silent: true
		 		// });
	 		});

	     }, 'json');// End post
	 	return false;

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
	 		    output = '<div class="alert alert-success"><p>SMS Sent Successfully</p></div>';
	 		}

	 		$("#send_sms_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline(id);
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#NotificationForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#NotificationForm").serialize();
		var id 		  = $("#QueryID").val();
		
		

	 	$.post('AllQueries/SendNotification', form_data, function(response){

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
			url: 'AllQueries/GetTimeline?QueryID=' + id,
			
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

	$('[data-tooltip="true"]').tooltip();
		
});