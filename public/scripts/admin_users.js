$(document).ready(function(){
    var host     = 'http://' + window.location.hostname + '/index.php/';

    table        = $('#users-table').DataTable({
        "ajax": 'AdminUsers/GetAllUsers'
    });

    // Get user_types

    $("#UserType").html('');
	$.get('AdminUsers/GetUserTypes', '', function(response){
		// Build select
    	var select 			= '<option></option>';
    	
    	$.each(response, function(key, value){
    		var id 			= value.permission_id;
    		var description = value.permission_type;

    		select += '<option value="' +id+ '">' +description+ '</option>';
    	});

		$("#UserType").html(select);
		$("#UserTypeedt").html(select);
	});

	window.getUserDelete = function(id) {
	    $("#deleteID").val(id);
	};

	var e=1;
	$('#EditModal').on('shown.bs.modal', function(e) {// on modal open
		var id 		 = $(e.relatedTarget).data('company-id'); 
		var email 	 = $(e.relatedTarget).data('email'); 
		var admin_id = $(e.relatedTarget).data('admin-id'); 
		var data     = 'ID='+id;

		$("#company_id").val(id);
		$("#email").val(email);
		$("#admin_id").val(admin_id);

		console.log(email);

		$.get('AdminUsers/GetPropertyForCompany', data, function(response){
		    var select_box      = '';
		    var select_boxedt   = ''; 
		    $.each(response, function(key, value){

		        select_box += '<div class="checkbox">'
		        select_box += '    <label class="ui-checks ui-checks-md">'
		        select_box += '        <input type="checkbox" value="'+value.propertyID+'" name="modules[]">'
		        select_box += '        <i></i>'
		        select_box += 			value.propertyName
		        select_box += '    </label>'
		        select_box += ' </div>'

		        select_boxedt += '<div class="checkbox">'
		        select_boxedt += '    <label class="ui-checks ui-checks-md">'
		        select_boxedt += '        <input type="checkbox" value="'+value.propertyID+'" name="modulesedt[]">'
		        select_boxedt += '        <i></i>'
		        select_boxedt += value.propertyName
		        select_boxedt += '    </label>'
		        select_boxedt += ' </div>'
		    });
		    $("#prop_list").html(select_box);
		   
		 });

		$("#PermissionType").val(email);


	});

	$("#SavePermissionForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#SavePermissionForm").serialize();

	 	$.post('AdminUsers/UpdatePerm', form_data, function(response){

	 		if(response.status == false) {
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true) {
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		 $("#ass_user_err").html (output).fadeIn ('slow').delay (3000).fadeOut ('fast', function(){ 

	 	 		table.ajax.reload();
	 	 		$(this).off('shown.bs.modal');

	 		 });

	     }, 'json');// End post
	 	return false;
	});

	

	window.getUserEdit = function(id) {
	    var data    = 'user_id='+id;
	    $.get('AdminUsers/GetUserByID', data, function(response){

	        $("#edtID").val(response.user_id);
	        $("#FirstNameedt").val(response.first_name);
	        $("#Surnameedt").val(response.last_name);
	        $("#Emailedt").val(response.email);
	        $("#CellNumberedt").val(response.cellphone);
	        $("#Password1edt").val(response.password);
	        $("#Password2edt").val(response.password);
	        $("#UserTypeedt").val(response.permission_id);
	        
	    });
	};


    $("#SaveUser").click(function(e){
    	var FirstName    	= $("#FirstName").val(); 
    	var Surname    		= $("#Surname").val(); 
    	var Email    		= $("#Email").val(); 
    	var CellNumber    	= $("#CellNumber").val(); 
    	var Password1    	= $("#Password1").val(); 
    	var Password2    	= $("#Password2").val(); 
    	var UserType    	= $("#UserType").val(); 
    	
    	var proceed        	= true;

		if(FirstName.trim() == ""){ 
	        $("#FirstName").css('border-color','#F33');
	        $("#FirstName").focus();
	        proceed         = false;
	    }else{
	        $("#FirstName").css('border-color','');
	    }

	    if(Surname.trim() == ""){ 
	        $("#Surname").css('border-color','#F33');
	        $("#Surname").focus();
	        proceed         = false;
	    }else{
	        $("#Surname").css('border-color','');
	    }

	    if(Email.trim() == ""){ 
	        $("#Email").css('border-color','#F33');
	        $("#Email").focus();
	        proceed         = false;
	    }else{
	        $("#Email").css('border-color','');
	    }

	    if(CellNumber.trim() == ""){ 
	        $("#CellNumber").css('border-color','#F33');
	        $("#CellNumber").focus();
	        proceed         = false;
	    }else{
	        $("#CellNumber").css('border-color','');
	    }

	    if(Password1.trim() == ""){ 
	        $("#Password1").css('border-color','#F33');
	        $("#Password1").focus();
	        proceed         = false;
	    }else{
	        $("#Password1").css('border-color','');
	    }

	    if(Password2.trim() == ""){ 
	        $("#Password2").css('border-color','#F33');
	        $("#Password2").focus();
	        proceed         = false;
	    }else{
	        $("#Password2").css('border-color','');
	    }

	    if(UserType.trim() == ""){ 
	        $("#UserType").css('border-color','#F33');
	        $("#UserType").focus();
	        proceed         = false;
	    }else{
	        $("#UserType").css('border-color','');
	    }

	    

	    if(proceed) {

	    	post_data     = {   
	    						'FirstName':FirstName.trim(),
	    						'Surname':Surname.trim(),
	    						'Email':Email.trim(),
	    						'CellNumber':CellNumber.trim(),
	    						'Password1':Password1.trim(),
	    						'Password2':Password2.trim(),
	    						'UserType':UserType.trim()
	    	                };

	    	$.post('AdminUsers/SaveUser', post_data, function(response){
	    		var output = '';

	    		if(response.status == true){
	    			output = '<div class="alert alert-success"><p>Saved</p></div>';
	    		}else{
	    		   output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	    		}

	    		 $("#save_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();

	    		 table.ajax.reload();// Refresh table
	    	});


	    }else{
	    	$("#CreateModal").effect('shake', 900);
	    }
	});

	$("#EditUser").click(function(e){
    	var FirstName    	= $("#FirstNameedt").val(); 
    	var Surname    		= $("#Surnameedt").val(); 
    	var Email    		= $("#Emailedt").val(); 
    	var CellNumber    	= $("#CellNumberedt").val(); 
    	var Password1    	= $("#Password1edt").val(); 
    	var Password2    	= $("#Password2edt").val(); 
    	var UserType    	= $("#UserTypeedt").val(); 
    	
    	var ID              = $("#edtID").val();

    	var proceed        	= true;

		if(FirstName.trim() == ""){ 
	        $("#FirstNameedt").css('border-color','#F33');
	        $("#FirstNameedt").focus();
	        proceed         = false;
	    }else{
	        $("#FirstNameedt").css('border-color','');
	    }

	    if(Surname.trim() == ""){ 
	        $("#Surnameedt").css('border-color','#F33');
	        $("#Surnameedt").focus();
	        proceed         = false;
	    }else{
	        $("#Surnameedt").css('border-color','');
	    }

	    if(Email.trim() == ""){ 
	        $("#Emailedt").css('border-color','#F33');
	        $("#Emailedt").focus();
	        proceed         = false;
	    }else{
	        $("#Emailedt").css('border-color','');
	    }

	    if(CellNumber.trim() == ""){ 
	        $("#CellNumberedt").css('border-color','#F33');
	        $("#CellNumberedt").focus();
	        proceed         = false;
	    }else{
	        $("#CellNumberedt").css('border-color','');
	    }

	    if(Password1.trim() == ""){ 
	        $("#Password1edt").css('border-color','#F33');
	        $("#Password1edt").focus();
	        proceed         = false;
	    }else{
	        $("#Password1edt").css('border-color','');
	    }

	    if(Password2.trim() == ""){ 
	        $("#Password2edt").css('border-color','#F33');
	        $("#Password2edt").focus();
	        proceed         = false;
	    }else{
	        $("#Password2edt").css('border-color','');
	    }

	    if(UserType.trim() == ""){ 
	        $("#UserTypeedt").css('border-color','#F33');
	        $("#UserTypeedt").focus();
	        proceed         = false;
	    }else{
	        $("#UserTypeedt").css('border-color','');
	    }

	    

	    if(proceed) {

	    	post_data     = {   
	    						'ID':ID.trim(),
	    						'FirstName':FirstName.trim(),
	    						'Surname':Surname.trim(),
	    						'Email':Email.trim(),
	    						'CellNumber':CellNumber.trim(),
	    						'Password1':Password1.trim(),
	    						'Password2':Password2.trim(),
	    						'UserType':UserType.trim()
	    	                };

	    	$.post('AdminUsers/EditUser', post_data, function(response){
	    		var output = '';

	    		if(response.status == true){
	    			output = '<div class="alert alert-success"><p>Saved</p></div>';
	    		}else{
	    		   output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	    		}

	    		 $("#edit_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();

	    		 table.ajax.reload();// Refresh table
	    	});


	    }else{
	    	$("#EditModal").effect('shake', 900);
	    }
	});


	$("#DeleteUser").click(function(){
	    
	    var ID        = $("#deleteID").val();
	    //data to be sent to server
	    post_data     = {'ID':ID.trim()};

	    $.post('AdminUsers/DeleteUser', post_data, function(response){

	        var output = '';
	        if(response.status == false){
	            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	        }else if(response.status == true){
	            output = '<div class="alert alert-success"><p>Deleted</p></div>';
	        }
	        
	        table.ajax.reload();// Refresh table

	        $("#delete_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();

	    }, 'json');// End post

	});
});