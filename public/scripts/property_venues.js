$(document).ready(function(){
	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;


	// get_document_types('[id *= "DocumentType"]');


	$("#CreateVenueForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#CreateVenueForm')[0]);

		if($("#UploadVeneImageFile").length != 0) {
			var file_data = $("#UploadVeneImageFile").prop("files")[0];  
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
					output = '<div class="alert alert-success"><p>Saved</p></div>';

				}else{
					$(".progress").hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
				}

				$("#error_save_venue").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("[id='progress-bar']").width('0%');
			 		$('#table-documents').bootstrapTable('refresh', {
						silent: true
					});
				});
			},
			resetForm: false 
		}); 
		return false; 


	});


	// load-success.bs.table

	$("#table-documents").bootstrapTable({
		onLoadSuccess: function (data) {
		                alert(4);
		            }
		});

	function imageFormatter (value, row) {
	    return '<img src="http://connectliving.co.za'+value+'">';
	}


	$("[rel='tooltip']").tooltip();


});