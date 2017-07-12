$(document).ready(function(el){

	var host 	 			= 'http://admin.nconnectapp.co.za/index.php/';
	var full_name 			= $("#full_name").val();
	var unit_no 			= $("#unit_no").val();
	var form_id 			= $("#form_id").val();
	var prop_id 			= $("#prop_id").val();
	var cellphone 			= $("#cellphone").val();
	

	var form_data 			= {
								'full_name': full_name, 
								'unit_no': unit_no, 
								'FormID': form_id,
								'prop_id': prop_id,
								'cellphone': cellphone
							};

	$("#survey_area").html('No Questions');// get title name
	$.get('Forms/GetFormByID', form_data, function(response){
		// Build select
		var survey_form 	= '';
		var q_id 	= '';
		$.each(response, function(key, value){

			$("#form_name").html(value.name);
			$("#prop_name").val(value.prop_name);
			$("#prop_id").val(value.prop_id);
			$("#form_id").val(value.id);

			var q 			= value.questions;

			$.each(value.questions, function(k, q){
				var q_num 		= q.q_num;
				var q_text 		= q.q_text;
				var q_options 	= q.q_option;
				var q_type 		= q.q_type;
				var q_mandatory	= q.q_mandatory;
				var options 	= '';

				console.log(q_mandatory)

				var star = '';
				if (q_mandatory == 'true') {
					star = '*';
				}

				if(q_type == 'select'){
					options += '<div class="input-field col s12">';
					options += '<select name="question'+q_num+'" class="browser-default">';
					options += '<option value="" selected>Choose your option</option>';
				}

				// loop through options
				var c = 1;

				// console.log(q_type);
				$.each(q_options, function(k, v){
					switch (q_type) {
						case 'file_upload':// Image upload
							var upload_id 	= 'fileupload'+(c + q_num);
						 	q_id 			= q_num;
						 	options += '<div class="file-field input-field col s12">';

						 	options += '	<div class="btn">';
						 	options += '		<span>File</span>';
						 	options += '		<input type="file" name="question'+q_num+'" id="fileupload'+q_num+'" />';
						 	options += '	</div>';
						 	options += '	<div class="file-path-wrapper">';
						 	options += '		<input class="file-path validate" type="text" placeholder="'+v+'">';
						 	options += '	</div>';
							// options += '<button id="fileupload" name="question'+q_num+' "class="waves-effect waves-light btn-large" style="width:100%">'+v+'</button>';
							// options += '<input class="file-path validate question'+q_num+'" type="hidden" name="question'+q_num+'" value="" class="question'+q_num+'">';
							// options += '<div class="btn">';
							// options += '	<span>'+v+'</span>';
							// options += '	<button id="fileupload'+q_num+'" name="question'+q_num+'" class="waves-effect waves-light btn-large" style="width:100%">'+v+'</button>';
							// options += '</div>';
							// options += '<input type="hidden" name="question'+q_num+'" value="" class="question'+q_num+'"  >';
							options += '</div>';
							
						break;
						case 'number_text':// Number text
							options += '<input placeholder="'+v+'" id="question'+(c + q_num)+'" type="tel" class="form-control" name="question'+q_num+'"/>';
							// options += '<label for="question'+(c + q_num)+'">'+v+'</label>';
						break;
						case 'free_text':// Number text
							options += '<input placeholder="'+v+'" id="question'+(c + q_num)+'" type="text" class="form-control" name="question'+q_num+'"/>';
							// options += '<label for="question'+(c + q_num)+'">'+v+'</label>';
						break;
						case 'checkbox':
							options += '<p>';
							options += '<input class="filled-in" type="checkbox" name="question'+q_num+'[]" id="question'+(c + q_num)+'" value="'+v+'">';
							options += '<label for="question'+(c + q_num)+'">' +v+ '</label>';
							options += '</p>';
						break;
						case 'radio':
							options += '<p>';
							options += '<input class="with-gap" type="radio" name="question'+q_num+'" id="question'+(c + q_num)+'" value="'+v+'">';
							options += '<label for="question'+(c + q_num)+'">' +v+ '</label>';
							options += '</p>';
						break;
						case 'select':
							options += '<option value="'+v+'">';
							options += v;
							options += '</option>';
						break;

						case 'date':// date input
							options += '<input placeholder="'+v+'" id="question'+(c + q_num)+'" type="text" class="form-control datepicker" name="question'+q_num+'"/>';
							// options += '<label for="question'+(c + q_num)+'">'+v+'</label>';
						break;

						case 'signature':// date input
							// options += '<input placeholder="'+v+'" id="question'+(c + q_num)+'" type="text" class="form-control signPad" name="question'+q_num+'"/>';
							
							
							options += '<div id="content">';
							options += ' <div id="signatureparent">';
							options += '	<div><div id="signaturearea'+q_num+'" ></div></div>';
							options += '	<input type="hidden" name="question'+q_num+'"  class="output'+q_num+'"  id="question'+(c + q_num)+'" />';
							options += ' </div>';
							options += ' <div id="tools'+q_num+'"></div>';
							options += '</div>';
							// options += '<label for="question'+(c + q_num)+'">'+v+'</label>';
						break;
						case 'star_rating':// date input
							options += '<div class="star_container">';
							options += '    <input type="radio" id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating" value="1" />';
							options += '    <input type="radio" id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating" value="2" />';
							options += '    <input type="radio" id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating" value="3" />';
							options += '    <input type="radio" id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating" value="4" />';
							options += '    <input type="radio" id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating" value="5" />';
							options += '</div>';
							// options += '<input id="question'+(c + q_num)+'" name="question'+q_num+'" class="rating-loading" data-size="sm">';
						break;


						

						// date
						// signature
						// star_rating
					}
					c++;
				});
				
				if(q_type == 'select'){
					options += '</select>';
					options += '</div>';
				}


				survey_form		+= '<div class="row">';
				// survey_form		+= '<p class="text-darken-2">';
				// survey_form		+= '<strong class="text-darken-2">' + q_num +'. '+ q_text + '</strong>';// question text
				// survey_form		+= '</p>';// close column

				// Options panel
				survey_form		+= '<div class="input-field col s12 text-darken-2">';
				survey_form		+= '<p>' + q_num +'. '+ q_text + ' ' +star+ '</p>';
				survey_form		+= options;// options
				survey_form		+= '</div>';// close column
				// End Options panel
				survey_form		+= '</div>';// close row

				
				
			});
			
		});


		$("#survey_area").html(survey_form);// get title name
		$( "[class*='datepicker']" ).datepicker({
		 	dateFormat: "yy-mm-dd"
		});

		// $('.sigPad').signaturePad({drawOnly:true});
		// get_stars($('.rating-loading'));
		$("[class*='star_container']").rating();

		


		$("[id*='signaturearea']").each(function(i, e) { // loop throug signitures
			var sigid 		= $(this).attr("id");
			var qii 		= sigid.replace('signaturearea', '');
			var toolid 		= 'tools' + qii;
			var outclass 	= 'output' + qii;

			var $sigdiv = $("#" + sigid),
			 	$tools  = $("#" + toolid);

			$sigdiv.jSignature({'UndoButton':true});

			$sigdiv.bind('change', function(e){ 
				/* 'e.target' will refer to div with "#signature" */ 
				var data = $sigdiv.jSignature('getData', 'image');
				$("." + outclass).val(data);

				console.log('here:' + data);
			});
			// console.log(qii);
		});

		// File upload
		// $("[id*='fileupload']").click(function() { // loop throug signitures
		// 	var fileid 		= $(this).attr("id");
		// 	var qii 		= fileid.replace('fileupload', '');

		// 	// console.log ('file', fileid);
		// 	SaveImage (fileid, form_data);
			
		// });


		// var data = form_data + '&q_id=' + q_id;
		
		// SaveImage ("[id*='fileupload']", form_data);

	});

	function get_stars(iddiv){
		$(iddiv).rating({
			step: 1,
			starCaptions: {1: 'Very Low', 2: 'Low', 3: 'Ok', 4: 'High', 5: 'Very High'},
			starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
		});
	}

	
	window.SaveImage = function(but_id, form_data) {
	    // var btn = $(but_id).attr("id");
	    var btn = but_id;

	    console.log ('Button:', btn);

	    new AjaxUpload(btn, {
	        action: 'FormSubmissions/SaveImage', //Your php script
	        name: 'survey-form', //form name, optional
	        data: form_data, //data here
	        responseType:'json',
	        onSubmit: function(file, ext){ 
	            alert("Your file: " + file);
	                                                            
	        },
	        onComplete : function(file, response){ 
	            console.log( response);

	            $('.' + response.question_name).val(response.file_name);
	        },
	        error : function(file, response){ 
	            alert("Response: " + response);
	        }
	    });
	};

	$("#survey-form").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#survey-form')[0]);

		console.log(form_data);

		$("[id*='fileupload']").each(function() { // loop throug signitures
			var fileid 		= $(this).attr("id");
			var qii 		= fileid.replace('fileupload', '');
			// console.log ($('#' + fileid).prop("files"))
			var file_data 	= $('#' + fileid).prop("files")[0];  

			form_data.append ("file", file_data);
		});

		$(this).ajaxSubmit({ 
			target:   '#targetLayer',
			dataType: "json", 
			beforeSubmit: function() {
				console.log ('Before Submit');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				console.log (percentComplete, '%');
			},
			error: function() { console.log ('error') },
			success:function (response){
				afterSuccess(response);
				
			},
			resetForm: true 
		}); 

		return false; 

		function afterSuccess (response) {
		    var output = '';
		    if(response.status == true) {
		    	output = '<div class="card-panel green lighten-2">Survey Has Been Submited</div>';
		    }else{
		    	output = '<div class="card-panel red lighten-2">'+response.text+'</div>';
		    }

		    $("#survey_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();
		}

	});

	// $("#survey-form").submit(function(e){
	// 	e.preventDefault();
		

	// 	var form_data = $("#survey-form").serialize();
	// 	console.log(form_data);
	// 	$.post('FormSubmissions/SubmitForm', form_data, function(response){
	// 		var output = '';
	// 		console.log(response);
	// 		// <div class="card-panel teal lighten-2">This is a card panel with a teal lighten-2 class</div>
	// 		if(response.status == true){
	// 			output = '<div class="card-panel green lighten-2">Survey Has Been Submited</div>';
	// 		}else{
	// 			output = '<div class="card-panel red lighten-2">'+response.text+'</div>';
	// 		}

	// 		// Materialize.toast(response.text, 4000);
	// 		// $("html, body").animate({ scrollTop: 0 }, "slow");
	// 		$("#survey_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();


	// 	});
	// });

	

		
	
});
