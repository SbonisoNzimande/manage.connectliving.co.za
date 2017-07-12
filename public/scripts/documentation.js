$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;


	// get_document_types('[id *= "DocumentType"]');


	$('#UploadDocumentationModal').on('show.bs.modal', function(e) {// on modal open
		get_document_types('#UploadDocumentForm [id *= "DocumentType"]');
	});

	$('#editDocumentModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('doc-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#editID').val(id);

		// Get edit items
		$.get('Documentation/GetDocumentByID', data, function(response){
			console.log(response);
		    get_document_types('#EditUploadDocumentForm [id *= "DocumentType"]');
		    $("#EditUploadDocumentForm [name=DocumentType]").val(response.type_id);
		});
	});

	$("#UploadDocumentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($(this)[0]);

		if($("#UploadLogoFile").length != 0) {
			var file_data = $("#UploadLogoFile").prop("files")[0];  
			form_data.append("file", file_data);
		}

		var id 		  = $("#QueryID").val();

		console.log(form_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
			beforeSubmit: function() {
				$("[id='progress-bar']").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("[id='progress-bar']").width(percentComplete + '%');
				$("[id='progress-bar']").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
				var output = '';
				if(response.status == true){
					output = '<div class="alert alert-success"><p>Uploaded</p></div>';

				}else{
					$(".progress").hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
				}

				$("#error_save_doc").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("[id='progress-bar']").width('0%');
			 		$('#table-documents').bootstrapTable('refresh', {
						silent: true
					});
				});
			},
			resetForm: true 
		}); 
		return false; 


	});

	$("#EditUploadDocumentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#EditUploadDocumentForm')[0]);

		if($("#UploadLogoFile").length != 0) {
			var file_data = $("#UploadLogoFile").prop("files")[0];  
			form_data.append("file", file_data);
		}


		console.log(form_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
			beforeSubmit: function() {
				$("[id='progress-bar']").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("[id='progress-bar']").width(percentComplete + '%');
				$("[id='progress-bar']").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
				var output = '';
				if(response.status == true){
					output = '<div class="alert alert-success"><p>Uploaded</p></div>';

				}else{
					$(".progress").hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
				}

				$("#error_edit_doc").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("[id='progress-bar']").width('0%');
			 		$('#table-documents').bootstrapTable('refresh', {
						silent: true
					});
				});
			},
			resetForm: true 
		}); 
		return false; 


	});



	$('#EditDocumentationTypesModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('doc-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#CategoryID').val(id);



		// Get edit items
		$.get('Documentation/GetDocumentTypeByID', data, function(response){
			console.log(response);
		    $("#EditDocumentTypeForm [name=DocumentTypeName]").val(response.name);
		});

	});

	$('#deleteDocumentModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('doc-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#deleteID').val(id);

	});

	$('#DeleteDocumentTypeModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('doc-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#DelCategoryID').val(id);

	});


	$("#CreateDocumentTypeForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#CreateDocumentTypeForm").serialize();

		console.log('Savind category: ' + form_data);
		
	 	$.post('Documentation/CreateDocumentType', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#error_save_doc_type").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-category').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#EditDocumentTypeForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditDocumentTypeForm").serialize();

		console.log('Edit category: ' + form_data);
		
	 	$.post('Documentation/EditDocumentType', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#error_edit_doc_type").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-category').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#DeleteDocument").on( 'click', function(e) {
		e.preventDefault();

		var id 			= $('#deleteID').val();
		var form_data 	= 'id=' + id;

	 	$.post('Documentation/DeleteDoc', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Delete</p></div>';
	 		}

	 		$("#delete_doc_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-documents').bootstrapTable('refresh', {
					silent: true
				});

	 	 		$('#deleteDocumentModal').modal('hide');
	 		 });
	 		 
	     }, 'json');// End post
	 	return false;


	});

	$("#DeleteDocumentType").on( 'click', function(e) {
		e.preventDefault();

		var id 			= $('#DelCategoryID').val();
		var form_data 	= 'id=' + id;

	 	$.post('Documentation/DeleteDocType', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Deleted</p></div>';
	 		}

	 		$("#delete_doc_type_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-category').bootstrapTable('refresh', {
	 				silent: true
	 			});

	 	 		$('#DeleteDocumentTypeModal').modal('hide');
	 		 });
	 		 
	     }, 'json');// End post
	 	return false;


	});


	$('#DuplicateDocumentsModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('doc-id'); 
		var data        = 'DocumentID='+id+'&prop_id='+prop_id;
		$("#DuplicateDocumentID").val(id);

		// PropertyName

		get_properties('#PropertyName');


	});


	$("#DuplicateDocumentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#DuplicateDocumentForm").serialize()+'&prop_id='+prop_id;

		console.log('Duplicating Document: ' + form_data);
		
	 	$.post('Documentation/DuplicateDocument', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#duplicate_error").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-documents').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});


	function get_document_types(elem){
		$.get('Documentation/GetAllDocumentTypes', '', function(response){
			
		    var select = '<option></option>';
		    $.each(response, function(key, value){
		    	select += '<option value="'+value.id+'">' +value.name+ '</option>';
		    });

		    $(elem).html(select);
		});
	}

	function get_properties(iddiv){
		$.get('UserPermissions/GetPropertyList', '', function(response){
			
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				level_select += '<option value="' +value.propertyID+ '">' +value.propertyName+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	// load-success.bs.table

	$("#table-documents").bootstrapTable({
		onLoadSuccess: function (data) {
		                alert(4);
		            }
		});


	$("[rel='tooltip']").tooltip();


});