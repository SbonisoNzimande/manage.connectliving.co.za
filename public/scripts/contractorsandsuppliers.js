// CreateResidentForm
$(document).ready(function(){
	
	get_service_types('[id="ServiceType"]');


	$("#UploadCompanyLogoForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#UploadCompanyLogoForm')[0]);

		if($("#UploadLogoFile").length != 0) {
			var file_data = $("#UploadLogoFile").prop("files")[0];  
			form_data.append("file", file_data);
		}

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
					output = '<div class="alert alert-success"><p>Uploaded</p></div>';

				}else{
					$(".progress").hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
				}

				$("#error_company_logo").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("#progress-bar").width('0%');
					// get_images(data);
				});
			},
			resetForm: true 
		}); 
		return false; 


	});

	$('#EditContractorModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#ContractorID').val(id);

		// Get edit items
		$.get('ContractorsAndSuppliers/GetContractorByID', data, function(response){
			console.log(response);
		    $("#EditContractorForm [name=ServiceType]").val(response.service_id);
		    $("#EditContractorForm [name=CompanyName]").val(response.company_name);
		    $("#EditContractorForm [name=Address]").val(response.address);
		    $("#EditContractorForm [name=PhoneNumber]").val(response.phone_number);
		    $("#EditContractorForm [name=Email]").val(response.email);
		    
		});
	});

	$('#SupplierThumbnailModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 

		console.log(id);

		$('#supplier_id').val(id);

		
	});


	$('#DuplicateContractorsModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('res-id'); 
		var data        = 'ContractorID='+id;
		$("#DuplicateContractorID").val(id);

		// PropertyName

		get_properties('#PropertyName');


	});

	$("#DuplicateContractorsForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#DuplicateContractorsForm").serialize();

		console.log('Duplicating: ' + form_data);
		
	 	$.post('ContractorsAndSuppliers/DuplicateContractor', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Form Saved</p></div>';
	 		}

	 		$("#duplicate_error").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	function get_properties(iddiv){
		$.get('UserPermissions/GetPropertyList', '', function(response){
			
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				level_select += '<option value="' +value.propertyID+ '">' +value.propertyName+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	$('#ArchiveResidentModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		// var data        = 'ID='+id;

		console.log('ID='+id);

		$('#archive_id').val(id);

	});

	$("#CreateContractorForm").on( 'submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#CreateContractorForm").serialize();

		console.log('Creating new contractor: ' + form_data);
		
	 	$.post('ContractorsAndSuppliers/CreateContractor', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Saved</p></div>';
	 		}

	 		$("#create_res_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});

	     }, 'json');// End post
	 	return false;

	});

	$("#EditContractorForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditContractorForm").serialize();

		console.log('Updating contractor: ' + form_data);
		
	 	$.post('ContractorsAndSuppliers/UpdateContractor', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Updated</p></div>';
	 		}

	 		$("#edit_contractor_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});

	     }, 'json');// End post
	 	return false;

	});


	$('#DeleteContractorModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#deleteID').val(id);

	});


	$("#DeleteContractor").on( 'click', function(e) {
		e.preventDefault();

		var id 			= $('#deleteID').val();
		var form_data 	= 'id=' + id;

	 	$.post('ContractorsAndSuppliers/DeleteContractor', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Delete</p></div>';
	 		}

	 		$("#delete_cont_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
					silent: true
				});

	 	 		$('#DeleteContractorModal').modal('hide');
	 		 });
	 		 
	     }, 'json');// End post
	 	return false;


	});


	$('#EditSupplierTypesModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('doc-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#CategoryID').val(id);

		// Get edit items
		$.get('ContractorsAndSuppliers/GetSupplierTypeByID', data, function(response){
			console.log(response);
		    $("#EditSupplierTypeForm [name=SupplierTypeName]").val(response.service_name);
		});

	});

	$("#EditSupplierTypeForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditSupplierTypeForm").serialize();

		console.log('Edit category: ' + form_data);
		
	 	$.post('ContractorsAndSuppliers/EditSupplierType', form_data, function(response){

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

	$("#CreateSupplierTypeForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#CreateSupplierTypeForm").serialize();

		console.log('Saving category: ' + form_data);
		
	 	$.post('ContractorsAndSuppliers/CreateSupplierType', form_data, function(response){

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


	$('#DeleteSupplierTypeModal').on('show.bs.modal', function(ev) {// on modal open
			// addCompanyForm
			var id 			= $(ev.relatedTarget).data('doc-id'); 
			var data        = 'ID='+id;

			console.log('ID='+id);

			$('#DelCategoryID').val(id);

		});
	$("#DeleteSupplierType").on( 'click', function(e) {
			e.preventDefault();

			var id 			= $('#DelCategoryID').val();
			var form_data 	= 'id=' + id;

		 	$.post('ContractorsAndSuppliers/DeleteSupplierType', form_data, function(response){

		 		if(response.status == false){
		 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
		 		}else if(response.status == true){
		 		    output = '<div class="alert alert-success"><p>Deleted</p></div>';
		 		}

		 		$("#delete_doc_type_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
		 	 		$('#table-category').bootstrapTable('refresh', {
		 				silent: true
		 			});

		 	 		$('#DeleteSupplierTypeModal').modal('hide');
		 		 });
		 		 
		     }, 'json');// End post
		 	return false;


		});


	function get_service_types(elem){
		$.get('ContractorsAndSuppliers/GetAllServiceTypes', '', function(response){
			
		    var select = '<option></option>';
		    $.each(response, function(key, value){
		    	select += '<option value="'+value.id+'">' +value.service_name+ '</option>';
		    });

		    $(elem).html(select);
		});
	}

});