$(document).ready(function(){
	//note_list

	get_all_notifications();

	get_date_picker($('#startdatepicker1'));
	get_date_picker($('#enddatepicker1'));
	get_date_picker($('#startdatepicker2'));
	get_date_picker($('#enddatepicker2'));

	
	function get_date_picker (d) {
		d.datetimepicker({
			format:'YYYY-MM-DD'
		});
	};

	function get_all_notifications(){
		$.get('Notifications/GetAllNotifications', '', function(response){
			var note_html = ''
			$.each(response, function(key, value){

				var mood_class  = '';
				var mood_class1 = '';
				switch(value.mood.toLowerCase()){
					case 'good' :
					mood_class = 'text-success';
					mood_class1 = 'b-l-success';
					break;
					case 'caution':
					mood_class = 'text-warning';
					mood_class1 = 'b-l-warning';
					break;
					case 'bad':
					mood_class  = 'text-danger';
					mood_class1 = 'b-l-danger';
					break;
				}

				note_html += '<div class="list-group md-whiteframe-z0">';
				note_html += '<span href class="list-group-item '+mood_class1+'">';
				note_html += '<span class="pull-right '+mood_class+'"><i class="fa fa-circle text-xs"></i>';

				note_html += '<span class="pull-right text-muted m-l-xs"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#EditModal" rel="tooltip" data-original-title="Edit" onclick="getEdit('+value.id+');" title="Edit Notification"><span class="glyphicon glyphicon-pencil"></span></button></span>';

				note_html += '<span class="pull-right text-muted m-l-xs"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#Delete" rel="tooltip" data-original-title="Delete" title="Delete" onclick="getDelete('+value.id+');"><span class="glyphicon glyphicon-trash"></span></button></span>';

				note_html += '<span class="pull-right text-muted m-l-xs"><button class="btn btn-danger btn-xs" data-title="Delete" rel="tooltip" data-original-title="Send Notification" onclick="pushNotification('+value.propertyID+', \'' +value.message+ '\');"><span class="glyphicon glyphicon-download"></span></button></span></span>';

				note_html += value.message;
				note_html += '</span>';
				note_html += '</div>';
			});

			$("#note_list").html(note_html).fadeIn('slow');
			$("[rel='tooltip']").tooltip();
		});
	}

	// CreateModal

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

	window.getEdit = function(id) {
	    var data    = 'id='+id;
	    $("#Messageedt").html('');
	    $("#StartDateedt").html('');
	    $("#EndDateedt").html('');
	    $.get('Notifications/GetNoteByID', data, function(response){

	        $("#Messageedt").val(response.message);
	        $("#StartDateedt").val(response.showDateFrom);
	        $("#EndDateedt").val(response.showDateTo);
	        $("#Moodedt").val(response.mood);
	        $("#edtID").val(response.id);
	    });
	};

	window.pushNotification = function(id, message) {

		var data = {'message': message, 'id': id}

		$.get('Notifications/SendNotification', data, function(response){

			var output = '';
			if(response.status == false) {
			    output = '<div class="alert alert-danger"><p>' +response.text+ '</p></div>';
			}else if(response.status == true) {
			    output = '<div class="alert alert-success"><p>' +response.text+ '</p></div>';
			}
			
		    $("#note_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
	     	         });
		});
	};
	

	window.getDelete = function(id) {
	    $("#deleteID").val(id);
	};

	$("#DeleteNote").click(function(){
	    
	    var ID        = $("#deleteID").val();
	    post_data     = {'ID':ID.trim()};

	    $.post('Notifications/DeleteNote', post_data, function(response){
	        var output = '';
	        if(response.status == false){
	            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	        }else if(response.status == true){
	            output = '<div class="alert alert-success"><p>Deleted</p></div>';
	        }

	        $("#delete_err").html(output).fadeIn('slow').delay(2000).fadeOut('fast', function(){
	            $('#Delete').modal('hide');
	     		get_all_notifications();
	         });

	    }, 'json');// End post

	});

	$("#SaveNotification").click(function(){
	    var Message        = $("#Message").val();
	    var StartDate      = $("#StartDate").val();
	    var EndDate        = $("#EndDate").val();
	    var Mood           = $("#Mood").val();
	    var PropertyList   = $("#PropertyList").val();
	    var proceed          = true;

	    if(Message.trim() == ""){ 
	        $("#Message").css('border-color','#F33');
	        $("#Message").focus();
	        proceed         = false;
	    }else{
	        $("#Message").css('border-color','');
	    }

	    if(StartDate.trim() == ""){ 
	        $("#StartDate").css('border-color','#F33');
	        $("#StartDate").focus();
	        proceed         = false;
	    }else{
	        $("#StartDate").css('border-color','');
	    }

	    if(EndDate.trim() == ""){ 
	        $("#EndDate").css('border-color','#F33');
	        $("#EndDate").focus();
	        proceed         = false;
	    }else{
	        $("#EndDate").css('border-color','');
	    }
	    if(Mood.trim() == ""){ 
	        $("#Mood").css('border-color','#F33');
	        $("#Mood").focus();
	        proceed         = false;
	    }else{
	        $("#Mood").css('border-color','');
	    }

	    if(proceed) {// Check to proceed
	        //data to be sent to server
	        post_data     = {   'Message':Message.trim(), 
	                            'StartDate':StartDate.trim(),
	                            'EndDate':EndDate.trim(),
	                            'PropertyList':PropertyList.trim(),
	                            'Mood':Mood.trim()
	                        };
	        $.post('Notifications/SaveNotification', post_data, function(response){
	            var output = '';
	            if(response.status == false) {
	                output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	            }else if(response.status == true) {
	                output = '<div class="alert alert-success"><p>Saved</p></div>';
	            }

	           	$("#save_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
	     				get_all_notifications();
	     				$('#CreateModal').modal('hide');
	     	         });

	        }, 'json');// End post

	    
	    }else{
	       $("#CreateModal").effect('shake', 900 );
	    }

	});

	$("#EditNotification").click(function(){
	    var Message        = $("#Messageedt").val();
	    var StartDate      = $("#StartDateedt").val();
	    var EndDate        = $("#EndDateedt").val();
	    var Mood           = $("#Moodedt").val();
	    var PropertyList   = $("#PropertyList").val();
	    var ID             = $("#edtID").val();
	    var proceed        = true;

	    if(Message.trim() == ""){ 
	        $("#Messageedt").css('border-color','#F33');
	        $("#Messageedt").focus();
	        proceed         = false;
	    }else{
	        $("#Messageedt").css('border-color','');
	    }

	    if(StartDate.trim() == ""){ 
	        $("#StartDateedt").css('border-color','#F33');
	        $("#StartDateedt").focus();
	        proceed         = false;
	    }else{
	        $("#StartDateedt").css('border-color','');
	    }

	    if(EndDate.trim() == ""){ 
	        $("#EndDateedt").css('border-color','#F33');
	        $("#EndDateedt").focus();
	        proceed         = false;
	    }else{
	        $("#EndDateedt").css('border-color','');
	    }

	    if(Mood.trim() == ""){ 
	        $("#Moodedt").css('border-color','#F33');
	        $("#Moodedt").focus();
	        proceed         = false;
	    }else{
	        $("#Moodedt").css('border-color','');
	    }

	    if(proceed) {// Check to proceed
	        //data to be sent to server
	        post_data     = {   'Message':Message.trim(), 
	                            'StartDate':StartDate.trim(),
	                            'EndDate':EndDate.trim(),
	                            'Mood':Mood.trim(),
	                            'PropertyList':PropertyList.trim(),
	                            'ID':ID.trim()
	                        };
	        $.post('Notifications/EditNotification', post_data, function(response){
	            var output = '';
	            if(response.status == false) {
	                output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	            }else if(response.status == true) {
	                output = '<div class="alert alert-success"><p>Saved</p></div>';
	            }

	           	$("#edit_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
	     				get_all_notifications();
	     				$('#EditModal').modal('hide');
	     	         });

	        }, 'json');// End post

	    
	    }else{

	       $("#EditModal").effect('shake', 900 );
	    }

	});
  
});