$(document).ready(function(){
	console.log('Done loading - -');
	var $table = $('#queries-table').bootstrapTable({
	    onLoadSuccess: function() {
	        // do something
	        console.log('Done loading - -');
	    },
	 });


	$('#BlockUserModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var user_id 	= $(e.relatedTarget).data('user-id'); 
		$("#BlockUserID").val(user_id);

	});

	$('#EditUserModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var user_id 	= $(e.relatedTarget).data('user-id'); 
		var user_type 	= $(e.relatedTarget).data('user-type'); 

		console.log(user_type)
		$("#ChangeUserTypeForm [id='UserID']").val(user_id);
		$("#ChangeUserTypeForm [id='UserType']").val(user_type);

	});


	$("#SaveUser").click(function(){
		var ID    		= $.trim($("#UserID").val());
		var UserType    = $.trim($("#UserType").val());

		console.log('Change User Type Confirm',  ID);
		var proceed     = true;

		

		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID,
		    					'UserType':UserType
		                    };

		    $.post('AppRegistrations/ChangeUserType', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
		        }

		         $("#update_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		         	$('#EditUserModal').modal('hide');
					$('#queries-table').bootstrapTable('refresh', {
			 			silent: true
			 		});
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});
	// ('#EditJobModal [id="JobProperty"]')
	// EditUserModal
	// update_err
	// SaveUser

	$('#ConvertUserModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var user_id 	= $(e.relatedTarget).data('user-id'); 
		var company_id 	= $(e.relatedTarget).data('company-id'); 
		var property_id = $(e.relatedTarget).data('property-id'); 
		var unit_no 	= $(e.relatedTarget).data('unitno'); 
		var fullname 	= $(e.relatedTarget).data('fullname'); 
		var cellphone 	= $(e.relatedTarget).data('cellphone'); 
		var email 		= $(e.relatedTarget).data('email'); 

		var datadata  		= {
				'user_id' : user_id,
				'company_id' : company_id,
				'property_id' : property_id,
				'unit_no' : unit_no,
				'fullname' : fullname,
				'cellphone' : cellphone,
				'email' : email
			}

			console.log(datadata);

		$("#ConvertUserButton").click(function(){
			    $.post('AppRegistrations/ConvertUser', datadata, function(response){

			        var output = '';

			        if(response.status == false){
			            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
			        }else if(response.status == true){
			            output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
			        }

			         $("#convert_user_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
			         	$('#ConvertUserModal').modal('hide');
						$('#queries-table').bootstrapTable('refresh', {
				 			silent: true
				 		});
			         });

			    }, 'json');// End post
		});	
	    


		// $("#BlockUserID").val(user_id);

	});

	$('#UnBlockUserModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var user_id 	= $(e.relatedTarget).data('user-id'); 
		$("#UnBlockUserID").val(user_id);

	});


	$("#BlockUserButton").click(function(){
		var ID    		= $.trim($("#BlockUserID").val());

		console.log('Block User confirmed' + ID);
		var proceed     = true;


		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID.trim()
		                    };

		    $.post('AppRegistrations/BlockUser', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
		        }

		         $("#block_user_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		         	$('#UnBlockUserModal').modal('hide');
					$('#queries-table').bootstrapTable('refresh', {
			 			silent: true
			 		});
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});

	$("#UnBlockUserButton").click(function(){
		var ID    		= $.trim($("#UnBlockUserID").val());

		console.log('UnBlock User confirmed' + ID);
		var proceed     = true;


		
		if(proceed) {// Check to proceed

		    //data to be sent to server
		    post_data     = { 
		    					'ID':ID.trim()
		                    };

		    $.post('AppRegistrations/UnblockBlockUser', post_data, function(response){

		        var output = '';

		        if(response.status == false){
		            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		        }else if(response.status == true){
		            output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
		        }

		         $("#unblock_user_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){

					$('#queries-table').bootstrapTable('refresh', {
			 			silent: true
			 		});
		         });

		    }, 'json');// End post

		
		}else{

		   $("#MarkDone").effect('shake', 900 );
		}


	});
});