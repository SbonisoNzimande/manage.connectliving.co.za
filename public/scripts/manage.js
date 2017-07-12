// CreateResidentForm
$(document).ready(function(){


	$('#EditResidentModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#ResidentID').val(id);

		// Get edit items
		$.get('Manage/GetResidentByID', data, function(response){
			console.log(response);
		    $("#EditResidentForm [name=UnitNumber]").val(response.unitNumber);
		    $("#EditResidentForm [name=ResidentName]").val(response.residentName);
		    $("#EditResidentForm [name=ResidentPhone]").val(response.residentPhone);
		    $("#EditResidentForm [name=ResidentCellphone]").val(response.residentCellphone);
		    $("#EditResidentForm [name=ResidentNotifyEmail]").val(response.residentNotifyEmail);
		    $("#EditResidentForm [name=ResidentType]").val(response.residentType);
		    $("#EditResidentForm [name=ResidentTrustee]").val(response.residentTrustee);
		    
		});
	});


	$('#ArchiveResidentModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		// var data        = 'ID='+id;

		console.log('ID='+id);

		$('#archive_id').val(id);

	});

	$("#CreateResidentForm").on( 'submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#CreateResidentForm").serialize();

		console.log('Creating new resident: ' + form_data);
		
	 	$.post('Manage/CreateResident', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#create_res_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#dynamic-table-residents').bootstrapTable('refresh', {
		 			silent: true
		 		});

		 		$('#trustee-residents').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#EditResidentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditResidentForm").serialize();


		console.log('Updating resident: ' + form_data);
		

	 	$.post('Manage/UpdateResident', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Updated</p></div>';
	 		}

	 		$("#update_res_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#dynamic-table-residents').bootstrapTable('refresh', {
		 			silent: true
		 		});

		 		$('#trustee-residents').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});


	     }, 'json');// End post
	 	return false;

	});


	$("#ArchiveUnit").on( 'click', function(ev) {
		ev.preventDefault();

		var form_data = 'ID=' + $("#archive_id").val();

		console.log('Updating resident: ' + form_data);

	 	$.post('Manage/ArchiveUnit', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Archived</p></div>';
	 		}

	 		$("#archive_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#dynamic-table-residents').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$('#CommunicateModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource

		var id 				= $(e.relatedTarget).data('res-id');
		var residentname 	= $(e.relatedTarget).data('res-name');

		$("input[id*='ResID']").val(id);
		$("#resName").html(residentname);
		get_timeline (id, "#timeline_area");

	});

	$('#ArchivedComHistoryModal').on('show.bs.modal', function(e) {// on modal open
		// imageresource
		var id 				= $(e.relatedTarget).data('res-id');
		var residentname 	= $(e.relatedTarget).data('res-name');

		$("input[id*='ResID']").val(id);
		$("[id*='resName']").html(residentname);
		get_timeline (id, "#timeline_area_archived");

	});


	$("#SMSForm").on( 'submit', function(ev) {

		ev.preventDefault();

		var form_data = $("#SMSForm").serialize();

		var id 		  = $("#ResID").val();

		console.log('Send SMS: ' + id);
		

	 	$.post('Manage/SendSMS', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>SMS Sent</p></div>';
	 		}

	 		$("#send_sms_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		get_timeline (id, "#timeline_area");
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

		var id 		  = $("#ResID").val();

		console.log(form_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
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
                	output = '<div class="alert alert-success"><p>Submited</p></div>';

                }else{
                	$(".progress").hide();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                $("#send_comment").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          get_timeline (id, "#timeline_area");
					$("[id*='progress-bar']").width('0%');
		         });
			},
			resetForm: true 
		}); 
		return false; 


	});

	$("#EmailForm").on( 'submit', function(ev) {
		ev.preventDefault();
	
		var id 		  = $("#ResID").val();

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
                }else{
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }

                $("#send_email_query").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          get_timeline (id, "#timeline_area");
					$('#loader').html('');
					$("[id*='progress-bar']").width('0%');
		         });
			},
			// resetForm: true 
		}); 
		return false; 


	});





	$('#editor').froalaEditor(
		{ 'key' :'hbmmjc1esdf1G-10bbjA11lE-13D1hr==', 
		  'codeMirror': true,
		  'toolbarButtons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
		  'codeBeautifier': true,
		  'imageManagerLoadURL':'http://manage.connectliving.co.za/public/images/email',
		  'imageUploadURL': 'http://manage.connectliving.co.za/index.php/Manage/UploadEmailImage',
		  'height': 400,
		  'pluginsEnabled': null
		}
	);

	function get_timeline (id, time_area) {
		console.log('Getting timeline: ' + id)
		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'Manage/GetTimeline?ResID=' + id,
			
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

					if (value.type == 'Email') {
						clss = 'bg-info';
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

				$(time_area).html(card);

				
				
			}
		});
	};
	
	$("[rel='tooltip']").tooltip();
	$('[data-tooltip="true"]').tooltip();

});