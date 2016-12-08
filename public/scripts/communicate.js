$(document).ready(function(){

	var prop_id 			= $('#prop_id').val();
	var prop_name 			= $('#prop_name').val();
	var payment_processed 	= $('#payment_processed').val();
	var company_id 		    = $('#company_id').val();
	var data 	 		    = 'prop_id=' + prop_id + '&prop_name=' + prop_name+ '&company_id=' + company_id

	get_all_notifications(data);
	get_sms_balance(data);

	get_date_picker($('#startdatepicker1'));
	get_date_picker($('#enddatepicker1'));
	get_date_picker($('#startdatepicker2'));
	get_date_picker($('#enddatepicker2'));

	
	function get_date_picker (d) {
		d.datetimepicker({
			format:'YYYY-MM-DD'
		});
	};

	window.getMsgDelete = function(id, type) {
	    $("#deleteID").val(id);
	    $("#deleteType").val(type);
	};

	if (payment_processed == 'true') {
		var transaction_id 	= $("#transaction_id").val();
		var company_id 		= $("#company_id").val();
		var form_data 		= {'transaction_id':transaction_id};

		$.post('Communicate/SucessTransaction', form_data, function(response){
	 		$('#BuyMoreCreditsSucessModal').modal('show'); 
	 		get_sms_balance(data);
	 		send_admin_email (company_id);
	     }, 'json');// End post
	}

	if (payment_processed == 'cancelled') {

		var transaction_id 	= $("#transaction_id").val();
		
		var form_data 		= {'transaction_id':transaction_id};

		$.post('Communicate/CancelTransaction', form_data, function(response){
	 		$('#BuyMoreCreditsCanceledModal').modal('show'); 

	 		get_sms_balance(data);
	     }, 'json');// End post

	}

	function send_admin_email (company_id){
		var form_data 		= {'company_id':company_id};
		$.post('Communicate/SendAdminEmail', form_data, function(response){
	 		
	     }, 'json');// End post

	}


	$("#SaveSMSForm").on( 'submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#SaveSMSForm").serialize();
		
	 	$.post('Communicate/SendSMS', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#send_sms_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_all_notifications(data);
     			// $('#SendSMSModal').modal('hide');
	 		});

	     }, 'json');// End post
	 	return false;

	});



	
	function get_sms_balance(data){
		$.get('Communicate/GetSMSBalance', data, function(response){
			$(".sms-balance").html(response.credits);
		});
	}
	function get_all_notifications(data){

		show_loader($("#inbox_list"));

		$.get('Communicate/GetAllCommunication', data, function(response){
			var note_html = ''
			$.each(response, function(key, value){

				var mood_class  = '';
				var mood_class1 = '';
				switch(value.mood){
					case 'good' :
					mood_class  = 'text-success';
					mood_class1 = 'b-l-success';
					break;
					case 'caution':
					mood_class  = 'text-warning';
					mood_class1 = 'b-l-warning';
					break;
					case 'bad':
					mood_class  = 'text-danger';
					mood_class1 = 'b-l-danger';
					break;
				}

				note_html += '<div class="hover panel-card bg-white p clearfix m-b-sm b-l b-l-2x r ng-scope ' +mood_class1+ '">';
				note_html += '	<a class="pull-left m-r">';
				note_html += '		<img class="w-40 rounded" src="../public/images/a0.jpg">';
				note_html += '	</a>';
				note_html += '	<div class="pull-right text-sm text-muted">';
				note_html += '		<span class="hidden-xs ng-binding">';
				note_html += '			<span class="pull-left">';
				note_html += 				value.date;
				note_html += '			</span>';

				note_html += '			<span class="pull-right">';
				note_html += '				<button class="btn btn-sm btn-default w-xxs w-auto-xs" tooltip="Delete" data-title="Delete" data-toggle="modal" data-target="#Delete" onclick="getMsgDelete('+value.id+',\''+value.type+'\');" tabindex="0"><i class="fa fa-trash-o"></i></button>';
				note_html += '			</span>';
				note_html += '		</span>';
				note_html += '	</div>';
				note_html += '	<div class="clear"  data-toggle="modal" data-title="Preview" data-target="#PreviewModal" onclick="preview(\'' +value.id+ '\',\''+value.type+'\')">';
				note_html += '		<div><a class="text-md ng-binding" href="#/app/inbox/10">' +value.subject+ '</a>';

				note_html += '			<span class="label bg-light m-l-sm ng-binding">' +value.type+ ' - ' +value.send_to+ '</span></div>';
				note_html += '		<div class="text-ellipsis m-t-xs text-muted-dk text-sm ng-binding">' +value.message+ '</div>';
				note_html += '	</div>';
				note_html += '</div>';

			});

			$("#inbox_list").html(note_html).fadeIn('slow');
			// $("#inbox_list").html(note_html).fadeIn('slow');

			
		});


		
	}

	window.preview = function(id, type){

		var data = {prop_id: $('#prop_id').val(), 
					ID: id,
					Type: type};
		show_loader($("#msg_content"));
		$.get('Communicate/GetCommunicationByid', data, function(response){
			$("#message_title").html(response.subject);
			$("#msg_date").html(response.date);
			$("#msg_content").html(response.message);
		});
	};

	$("#DeleteMsg").click(function(){
	    
	    var ID        = $("#deleteID").val();
	    var Type      = $("#deleteType").val();
	   
	    //data to be sent to server
	    post_data     = {
		    				'ID':ID.trim(),
		    				'Type': Type.trim(),
		    				prop_id: prop_id,
		    				prop_name: prop_name
	    			   	};

	    $.post(host + 'Communicate/DeleteMsg', post_data, function(response){
	        var output = '';
	        if(response.status == false){
	            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	        }else if(response.status == true){
	            output = '<div class="alert alert-success"><p>Deleted</p></div>';
	        }
	        
	        $("#delete_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
 				get_all_notifications(data);
 				$('#Delete').modal('hide');
	     	});

	    }, 'json');// End post

	});



	$("#Refresh").click(function(){
		get_all_notifications(data);
	});

	$("#SaveNotification").click(function(){
	    var Message        = $("#Message").val();
	    var Mood           = $("#Mood").val();
	    var proceed        = true;

	    if(Message.trim() == ""){ 
	        $("#Message").css('border-color','#F33');
	        $("#Message").focus();
	        proceed         = false;
	    }else{
	        $("#Message").css('border-color','');
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
	        post_data     = {   'Message': Message.trim(), 
	                            'Mood': Mood.trim(),
	                            'Property_ID': $('#prop_id').val()
	                        };

	        $.post(host + 'Communicate/SendNotification', post_data, function(response){
	            var output = '';
	            if(response.status == false) {
	                output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	            }else if(response.status == true) {
	                output = '<div class="alert alert-success"><p>Saved</p></div>';
	            }

	           	$("#save_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
     				get_all_notifications(data);
     				$('#SendNotification').modal('hide');
	     	    });

	        }, 'json');// End post
	    
	    }else{
	       $("#SendNotification").effect('shake', 900 );
	    }

	});

	


	$("#SaveEmailForm").on( 'submit', function(ev) {
		ev.preventDefault();
	
		var id 		  = $("#ResID").val();
		var resetf    = false;

		$(this).ajaxSubmit({ 
			target:   '#targetLayer2', 
			beforeSubmit: function() {
				$("[id*='progress-bar']").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("[id*='progress-bar']").width(percentComplete + '%');
				$("[id*='progress-bar']").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success"><p> Submitted </p></div>';
                	resetf = true;
                }else{
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                $("#send_email_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          	get_all_notifications(data);
					$("[id*='progress-bar']").width('0%');
		         });
			},
			resetForm: resetf 
		}); 
		return false; 


	});


	$('#EmailText').froalaEditor(
		{ 'key' :'hbmmjc1esdf1G-10bbjA11lE-13D1hr==', 
		  'codeMirror': true,
		  'toolbarButtons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
		  'codeBeautifier': true,
		  'imageManagerLoadURL':'http://manage.connectliving.co.za/public/images/email',
		  'imageUploadURL': 'http://manage.connectliving.co.za/Manage/UploadEmailImage',
		  'height': 400,
		  'pluginsEnabled': null
		}
	);

});


