$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	= 'prop_id=' + prop_id + '&prop_name=' + prop_name;

	$('.icp-auto').iconpicker();
	$('[id=cp2]').colorpicker();


	

	$("#CreateContactForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#CreateContactForm").serialize();

		console.log('CreateContactForm: ' + form_data);
		
	 	$.post('EmergencyContacts/CreateContactForm', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#error_save_contact").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-emergecy').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#EditContactForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditContactForm").serialize();

		console.log('EditContactForm: ' + form_data);
		
	 	$.post('EmergencyContacts/EditContactForm', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#error_edit_contact").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-emergecy').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#DeleteContactForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#DeleteContactForm").serialize();

		console.log('EditContactForm: ' + form_data);
		
	 	$.post('EmergencyContacts/DeleteContact', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>'+response.text+'</p></div>';
	 		}

	 		$("#error_delete_contact").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#table-emergecy').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	// EditContactModal
	// $("#EditFormForm [name=FormName]").val(value.name);


	$('#EditContactModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('contact-id'); 
		var data        = 'ContactID='+id;

		$("#EditID").val(id);


		$.get('EmergencyContacts/GetContactByID', data, function(response){
			console.log(response);

			$("#EditContactForm [name=ContactName]").val(response.contact_name);
			$("#EditContactForm [name=ContactType]").val(response.contact_type);
			$("#EditContactForm [name=ContactPhone]").val(response.contact_phone);
			$("#EditContactForm [name=ContactIcon]").val(response.contact_icon);
			$("#EditContactForm [name=ContactColor]").val(response.contact_color);
		});


	});

	$('#DeleteContactModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('contact-id'); 
		var data        = 'ContactID='+id;

		$("#DeleteID").val(id);

	});


});

function cellStyle(value, row, index) {
	return {
	    classes: value,
	    css: {"background-color": value}
	  };
}