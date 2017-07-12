$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name

	var table 		= $('#query-table').DataTable({
						"dom": 'T<"clear">lfrtip',
						"tableTools": {
						            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
						        },
						
	    				"ajax": 'Maintenance/GetAllMaintenance?' + data,
	    				"order": [[ 4, "desc" ]],
	    				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	    				                    if ( aData[5] == "Done" ){
	    				                        $('td', nRow).addClass('light-green-100');
	    				                    }
	    				               }
	    				                  
					});

	$.get('Maintenance/GetAllUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.id+ '">' +value.full_name+ '</option>';
		});

		$("#UsersList").html(level_select);
		$("#UsersListedt").html(level_select);
	});

	$.get('Maintenance/GetAllAdminUsers', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.user_id+ '">' +value.full_name+ '</option>';
		});

		$("#AssignTo").html(level_select);
		// $("#AssignToedt").html(level_select);
	});

	$.get('UserPermissions/GetPropertyList', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.PropertyID+ '">' +value.PropertyName+ '</option>';
		});

		$("#PropertyList").html(level_select);
		$("#PropertyListedt").html(level_select);
	});

	
	$("#SaveComment").click(function(){
		var Comment    = $.trim($("#comment").val());
		var ID    		= $.trim($("#QID").val());
		var proceed    = true;

		console.log(ID);

		if(Comment  == ""){ 
		    $("#Comment").css('border-color','#F33');
		    $("#Comment").focus();
		    proceed         = false;
		}else{
		    $("#Comment").css('border-color','');
		}

		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = {   
		    					'Comment':Comment.trim(),
		    					'ID':ID.trim()
		                    };


		    $.post('Maintenance/MarkDone', post_data, function(response){

		        var output = '';

		        if(response.status == false){

		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		            

		        }else if(response.status == true){

		            output = '<div class="alert alert-success"><p>Saved</p></div>';

		        }

		         $("#save_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          
					table.ajax.reload();
					$('#MarkDone').modal('hide');
		         });
		         

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});

	window.SetID  = function(id) {
	    $("#QID").val(id);
	};


	window.GetEdit  = function(id) {
		var data 	= 'id='+id; 

		$.get('Maintenance/GetMaintenanceInfo', data, function(response){

			console.log(response);
			$("#EditID").val(response.id);
			$("#QueryTypeedt").val(response.queryType);
			$("#PropertyListedt").val(response.propId);
			$("#Unitedt").val(response.unitId);
			$("#UsersListedt").val(response.userId);
			// $("#AssignToedt").val(response.assignee_id);
			$("#Queryedt").val(response.query);
				
		});
	};

	window.assign_user = function(assinee_id, id) {
		var data = {'id' : id, 'assinee_id' : assinee_id}
		$.get('Maintenance/AssignUser', data, function(response){
			alert('User assigned');
		});
	};

	window.getImage = function(id) {
		var data = 'id='+id; 

		show_loader($("#image_area"));
		$.post('Maintenance/GetImage', data, function(response){
			
			$("#image_area").html('<img src="'+response.images+'" style="width:100%;" id="LoadedImage" />');
			var left  = 0;

			$('#rotateIMG').click(function(){
				left += 90;
				$('#image_area img').rotate(left);

			});
				
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
			url: 'Maintenance/SaveQuery',  
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


                $("#create_q_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          
					table.ajax.reload();
					$('#CreateModal').modal('hide');
		         });
	        }
		});

	});

	$("#EditQueryForm").submit(function(e){
		e.preventDefault();

		var form_data = new FormData($('#EditQueryForm')[0]);

		$.ajax({
			url: 'Maintenance/EditQuery',  
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
		          
					table.ajax.reload();
					$('#EditModal').modal('hide');
		         });
	        }
		});

	});
});