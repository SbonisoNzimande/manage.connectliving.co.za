// CreateResidentForm
$(document).ready(function(){
	var PropID = $('#PropertyID').val();
	var aData 	= 'prop_id=' + PropID;

	console.log(aData);

	get_contructor_types('[id="ContructorID"]', aData);
	get_datepicker('[id="lastinspecteddatepicker"]');
	get_datepicker('[id="inspectionduedatepicker"]');


	$('#QRCodeModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		// Get edit items
		$.get('Assets/GetQRURL', data, function(response){
			console.log(response);

			$('#ass_name').html(response.asset_name);
			$('#ass_loc').html(response.location);
			$('#ass_ser').html(response.serial_number);

			// ass_name
			// ass_loc
			// ass_ser
		    
			$('#QRCodArea').attr('src', response.url);
		});
	});

	$('#EditAssetModal').on('show.bs.modal', function(e) {// on modal open
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#AssetID').val(id);

		// Get edit items
		$.get('Assets/GetAssetByID', data, function(response){
			console.log(response);
		    $("#EditAssetForm [name=ContructorID]").val(response.supplier_id);
		    $("#EditAssetForm [name=AssetName]").val(response.asset_name);
		    $("#EditAssetForm [name=Description]").val(response.description);
		    $("#EditAssetForm [name=Make]").val(response.make);
		    $("#EditAssetForm [name=Location]").val(response.location);
		    $("#EditAssetForm [name=SerialNumber]").val(response.serial_number);
		    $("#EditAssetForm [name=CostOfAsset]").val(response.cost_of_asset);
		    $("#EditAssetForm [name=LastInspected]").val(response.last_inspected);
		    $("#EditAssetForm [name=InspectionDueDate]").val(response.inspection_due_date);
		});
	});

	
	$('#DeleteAssetModal').on('show.bs.modal', function(ev) {// on modal open
		// addCompanyForm
		var id 			= $(ev.relatedTarget).data('res-id'); 
		var data        = 'ID='+id;

		console.log('ID='+id);

		$('#deleteID').val(id);

	});


	$("#DeleteAsset").on( 'click', function(e) {
		e.preventDefault();

		var id 			= $('#deleteID').val();
		var form_data 	= 'id=' + id;

	 	$.post('Assets/DeleteAsset', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Delete</p></div>';
	 		}

	 		$("#delete_ass_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
					silent: true
				});

	 	 		$('#DeleteAssetModal').modal('hide');
	 		 });
	 		 
	     }, 'json');// End post
	 	return false;

	});

	$("#PrintQR").on( 'click', function(e) {
		$('#qr-print').printThis();
	});
	

	$("#CreateAssetForm").on( 'submit', function(ev) {
		ev.preventDefault();
		var form_data = $("#CreateAssetForm").serialize();

		console.log('Creating new asset: ' + form_data);
		
	 	$.post('Assets/CreateAsset', form_data, function(response){

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

	$("#EditAssetForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditAssetForm").serialize();


		console.log('Updating asset: ' + form_data);
		

	 	$.post('Assets/UpdateAsset', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Updated</p></div>';
	 		}

	 		$("#edit_res_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#contractors-table').bootstrapTable('refresh', {
		 			silent: true
		 		});
	 		});


	     }, 'json');// End post
	 	return false;

	});


	function get_contructor_types(elem, data){
		$.get('Assets/GetConstructorByPropID', data, function(response){
			
		    var select = '<option></option>';
		    $.each(response, function(key, value){
		    	select += '<option value="'+value.id+'">' +value.company_name+ '</option>';
		    });

		    $(elem).html(select);
		});
	}


	function get_datepicker(elem){
		$(elem).datetimepicker({
            format: 'YYYY-MM-DD'
        });
	}

});