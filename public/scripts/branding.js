$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;

	get_images(data);
	$("#UploadCompanyLogoForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#UploadCompanyLogoForm')[0]);

		if($("#UploadLogoFile").length != 0) {
			var file_data = $("#UploadLogoFile").prop("files")[0];  
			form_data.append("file", file_data);
		}

		var id 		  = $("#QueryID").val();

		console.log(form_data);

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
					get_images(data);
				});
			},
			resetForm: true 
		}); 
		return false; 


	});

	$("#UploadEstateLogoForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#UploadEstateLogoForm')[0]);

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

				$("#error_estate_logo").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("[id='progress-bar']").width('0%');
					get_images(data);
				});
			},
			resetForm: true 
		}); 
		return false; 


	});

	function get_images(data){
		$.get('Branding/GetImages', data, function(response){

			if (response.company_image) {
				$('#imgcompany').attr('src',response.company_image);
			}

			if (response.estate_image) {
				$('#imgestate').attr('src',response.estate_image);
			}

			
			
			// $("#inbox_list").html(note_html).fadeIn('slow');

			
		});
	}
	


});