$(document).ready(function(){

	var prop_id 	= $('#prop_id').val();
	// $('form-table').bootstrapTable({
	// 	});



	// function to run when table is expanded
	// $('#form-table2').on('expand-row.bs.table', function (e, index, row, $detail) {
 //        console.log(index);

 //        $('#table-resp' + index).bootstrapTable({});
 //        $("[rel='tooltip']").tooltip();

 //    });

    function get_properties(iddiv){
		$.get('UserPermissions/GetPropertyList', '', function(response){
			
			var level_select = '<option></option>';
			        	
			$.each(response, function(key, value){
				level_select += '<option value="' +value.propertyID+ '">' +value.propertyName+ '</option>';
			});

			$(iddiv).html(level_select);
		});
	}

	function get_question_form () {
		var q_form = '';

			q_form += '	<tr>';
			q_form += '		<td>';
			q_form += '			<input type="text" class="form-control" id="QuestionNumber" name="QuestionNumber[]" placeholder="Question Number" style="width:50px">';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<textarea class="form-control" id="QuestionText" name="QuestionText[]" placeholder="Question Text" cols="50" rows="5"></textarea>';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<input type="text" class="form-control" id="QuestionOptions" name="QuestionOptions[]" placeholder="Question Options" data-role="tagsinput">';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<select class="form-control" name="QuestionType[]" id="QuestionType" placeholder="Question Type">';
			q_form += '				<option value="" disabled="true">Question Type</option>';
			q_form += '				<option value="file_upload">File Upload</option>';
			q_form += '				<option value="number_text">Number Text</option>';
			q_form += '				<option value="free_text">Free Text</option>';
			q_form += '				<option value="checkbox">Checkbox</option>';
			q_form += '				<option value="radio">Radio</option>';
			q_form += '				<option value="select">Select</option>';
			q_form += '				<option value="date">Date</option>';
			q_form += '				<option value="signature">Signature</option>';
			q_form += '				<option value="star_rating">Star Rating</option>';
			q_form += '			</select>';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<select class="form-control" name="QuestionMandatory[]" id="QuestionMandatory" placeholder="Question Mandatory">';
			q_form += '				<option value="false" selected="true">No</option>';
			q_form += '				<option value="true">Yes</option>';
			q_form += '			</select>';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<button type="button" class="btn btn-default waves-effect" id="removemore">Remove</button>';
			q_form += '		</td>';
			q_form += '	 </tr>';
			$("[id=QuestionOptions]").tagsinput();
			return q_form;

	}

	function get_question_edit_form (q_num, q_text, q_options, q_type, count) {
		var q_form = '';

			q_form += '	<tr>';
			q_form += '		<td>';
			q_form += '			<input type="text" class="form-control" id="QuestionNumber" name="QuestionNumber[]" placeholder="Question Number" value="'+q_num+'" style="width:50px">';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<input type="text" class="form-control" id="QuestionText" name="QuestionText[]" placeholder="Question Text" value="'+q_text+'">';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<input type="text" class="form-control" id="QuestionOptions" name="QuestionOptions[]" placeholder="Question Options" data-role="tagsinput"  value="'+q_options+'">';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<select class="form-control" name="QuestionType[]" id="QuestionType'+count+'" placeholder="Question Type">';
			q_form += '				<option value="file_upload">File Upload</option>';
			q_form += '				<option value="number_text">Number Text</option>';
			q_form += '				<option value="free_text">Free Text</option>';
			q_form += '				<option value="checkbox">Checkbox</option>';
			q_form += '				<option value="radio">Radio</option>';
			q_form += '				<option value="select">Select</option>';
			q_form += '				<option value="date">Date</option>';
			q_form += '				<option value="signature">Signature</option>';
			q_form += '				<option value="star_rating">Star Rating</option>';
			q_form += '			</select>';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<select class="form-control" name="QuestionMandatory[]" id="QuestionMandatory'+count+'"  placeholder="Question Mandatory">';
			q_form += '				<option value="false">No</option>';
			q_form += '				<option value="true">Yes</option>';
			q_form += '			</select>';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<button type="button" class="btn btn-default waves-effect" id="removemore">Remove</button>';
			q_form += '		</td>';
			q_form += '	 </tr>';
			
			return q_form;

	}

	function get_question_first_form () {
		var q_form = '';

			q_form += '	<tr>';
			q_form += '		<td>';
			q_form += '';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '';
			q_form += '		</td>';
			q_form += '		<td>';
			q_form += '			<button type="button" class="btn btn-default" id="addmoreedit">Add More</button>';
			q_form += '		</td>';
			q_form += '	 </tr>';
			
			return q_form;

	}

	// event.preventDefault();

	$("[id=addmore]").on( 'click', function(ev) {
		ev.preventDefault();
		var tr_data = get_question_form();

		console.log('append: ' +tr_data);

		$("#questions_table tbody").append(tr_data);
	});

	$("#questions_table_edt").on('click','#addmoreedit',function(ev){
		var tr_data = get_question_form();
		$("#questions_table_edt tbody").append(tr_data);
	});

	// $("[id=addmoreedit]").on( 'click', function(ev) {
	// 	ev.preventDefault();
	// 	var tr_data = get_question_form();

	// 	console.log('append: ' +tr_data);

	// 	$("#questions_table_edt tbody").append(tr_data);
	// });

	$("#questions_table").on('click','#removemore',function(){
	    $(this).parent().parent().remove();
	 });

	$("#questions_table_edt").on('click','#removemore',function(){
	    $(this).parent().parent().remove();
	 });

	$('#DeleteFormModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('form-id'); 
		var data        = 'FormID='+id;
		$("#DeleteFormID").val(id);


	});

	$('#DuplicateFormModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('form-id'); 
		var data        = 'FormID='+id;
		$("#DuplicateFormID").val(id);

		// PropertyName

		get_properties('#PropertyName');


	});

	$('#SendFormModal').on('show.bs.modal', function(e) {// on modal open
		var submission_id 	= $(e.relatedTarget).data('submit-id'); 

		var data = 'prop_id=' + prop_id + '&submission_id=' + submission_id;

		$.get('Forms/GetApprovalEmail', data, function(response){
			$("#email_place").html(response.html);
		});
	});

	$('#EditFormModal').on('show.bs.modal', function(e) {// on modal open
		var id 			= $(e.relatedTarget).data('form-id'); 
		var data        = 'FormID='+id;

		
		var ffrom = get_question_first_form();
		$("#questions_table_edt tbody").html(ffrom);
		$("#EditFormID").val(id);

		console.log(data);

		$.get('Forms/GetFormByID', data, function(response){
			console.log(response);

			var count = 0;
			$.each(response, function(key, value){
		    
				$("#EditFormForm [name=FormName]").val(value.name);
				$("#EditFormForm [name=FormInstructions]").val(value.form_instruction);

				// Loop through options
				$.each(value.questions, function(k, v){
					var tr_data = get_question_edit_form (v.q_num, v.q_text, v.q_option, v.q_type, count);

					var q_mandatory = '';

					if (v.q_mandatory) {
						q_mandatory  = v.q_mandatory;
						q_mandatory2 = q_mandatory.toString();
					}



					$("#questions_table_edt tbody").append(tr_data);
					$("[id=QuestionOptions]").tagsinput();
					$("#QuestionType"+count).val(v.q_type);
					$("#QuestionMandatory"+count).val(q_mandatory2);

					console.log(q_mandatory.toString());

					count++;

				});
			});
		});

	});


	$("#DeleteForm").on( 'click', function(ev) {
		ev.preventDefault();
		var id 			= $("#DeleteFormID").val();
		var data        = 'FormID='+id;

		$.post('Forms/DeleteForm', data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Form Deleted</p></div>';
	 		}

	 		$("#delete_form_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#form-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;
	});



	$("#DuplicateFormFrom").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#DuplicateFormFrom").serialize();

		console.log('Duplicating: ' + form_data);
		
	 	$.post('Forms/DuplicateForm', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Form Saved</p></div>';
	 		}

	 		$("#duplicate_error").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#form-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#CreateNewForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#CreateNewForm").serialize();

		console.log('Send: ' + form_data);
		
	 	$.post('Forms/SaveForm', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Form Saved</p></div>';
	 		}

	 		$("#create_form_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#form-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});


	     }, 'json');// End post
	 	return false;

	});

	$("#EditFormForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#EditFormForm").serialize();

		console.log('Editing form: ' + form_data);
		
	 	$.post('Forms/EditForm', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Form Updated</p></div>';
	 		}

	 		$("#edit_form_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#form-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});

	     }, 'json');// End post
	 	return false;

	});


	$("#LinkResidentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = $("#LinkResidentForm").serialize();

	 	$.post('Forms/LinkResident', form_data, function(response){

	 		if(response.status == false){
	 		    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	 		}else if(response.status == true){
	 		    output = '<div class="alert alert-success"><p>Linked</p></div>';
	 		}

	 		$("#link_error").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){ 
	 	 		$('#form-table').bootstrapTable('refresh', {
	 				silent: true
	 			});
	 		});
	     }, 'json');// End post
	 	return false;
	});



	$('#ViewSubmissionModal').on('show.bs.modal', function(e) {// on modal open
		
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('submit-id'); 
		var data        = 'ID='+id;

		console.log(data)

		$('#responces_table').bootstrapTable('refresh', {
			silent: true,
			url: '/Forms/GetResponcesBySubmissionID?' + data
		});

	});

	$('#FillFormModal').on('show.bs.modal', function(e) {// on modal  

		//FormSubmissions?FormID=7&unitNo=23&userFullname=Sboniso&userCellphone=0780012000
		var form_id 		= $(e.relatedTarget).data('form-id'); 
		var form_name 		= $(e.relatedTarget).data('form-name'); 
		var unit_num 		= $(e.relatedTarget).data('unit-num'); 
		var res_name 		= $(e.relatedTarget).data('res-name'); 
		var res_cell 		= $(e.relatedTarget).data('res-cell'); 

		var url 			= 'FormSubmissions?FormID='+form_id+'&unitNo='+unit_num+'&userFullname='+res_name+'&userCellphone='+res_cell;

		$("#submit-place").attr("src",url);
	});
	

	$('#LinkToResidentModal').on('show.bs.modal', function(e) {// on modal  
		// addCompanyForm
		var id 			= $(e.relatedTarget).data('submit-id'); 
		var data        = 'prop_id='+prop_id;

		$('#SubmissionID').val(id);

		$.get('Forms/GetPropResidents', data, function(response){

			var level_select = '<option></option>';

			$.each(response, function(key, value){
				level_select += '<option value="' +value.id+ '">' +value.residentName+ '</option>';
			});

			$("#ResidentList").html(level_select);
		});

	});


	$('[id=FormInstructions]').froalaEditor(
		{ 'key' :'hbmmjc1esdf1G-10bbjA11lE-13D1hr==', 
		  'codeMirror': true,
		  'toolbarButtons': ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|', 'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|', 'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', '-', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'quote', 'insertHR', 'undo', 'redo', 'clearFormatting', 'selectAll', 'html'],
		  'codeBeautifier': true,
		  'imageManagerLoadURL':'http://manage.connectliving.co.za/public/images/email',
		  'imageUploadURL': 'http://manage.connectliving.co.za/Manage/UploadEmailImage',
		  'height': 250,
		  'pluginsEnabled': null
		}
	);

	

	
});


function get_question_detail (index, row) {
	var form_id = row.id;

	console.log(form_id);


	 var html 	 = '';
		html +=	'<table data-toggle="table"';
		html +=	'class="display table table-striped"';
		html +=	'id="table"';
		html +=	'>';
		html +=	'<thead>';
		html +=	'	<tr>';
		html +=	'		<th data-field = "q_num">#</th>';
		html +=	'		<th data-field = "q_text">Question Text</th>';
		html +=	'		<th data-field = "q_type">Type</th>';
		html +=	'		<th data-field = "Options">Options</th>';
		html +=	'	</tr>';
		html +=	'</thead>';
		html +=	'<tbody>';

		var data        = 'FormID='+form_id;

		var responses 	= $.ajax({type: "GET", data: data, url: "Forms/GetFormByID", async: false, dataType: 'json'}).responseText;;
		var resp 		= JSON.parse(responses);

		console.log(resp);

		$.each(resp, function (key, value) {

			$.each(value.questions, function (k, v) {
				html +=	'<tr>';
				html +=	'	<td>' +v.q_num+ '</td>';
				html +=	'	<td>' +v.q_text+ '</td>';
				html +=	'	<td>' +v.q_type+ '</td>';
				html +=	'	<td>' +v.q_option+ '</td>';
				html +=	'</tr>';
			});
		    
		});

		html +=	'<tbody>';
		html +=	'</table>';
			

		 return html;
}

function get_responses_for_group(index, row) {

		var form_id = row.form_id;

		console.log(form_id);


		var html 		 = '';
		html 			+=	'<table data-toggle="table"';
		html 			+=	'	class="display table table-striped"';
		html 			+=	'	id="table-resp' +index+ '"';
		html 			+=	'>';
		html 			+=	'<thead>';
		html 			+=	'	<tr>';
		html 			+=	'		<th>ID</th>';
		html 			+=	'		<th>Name</th>';
		html 			+=	'		<th>Date</th>';
		html 			+=	'		<th>Action</th>';
		html 			+=	'	</tr>';
		html 			+=	'</thead>';
		html 			+=	'<tbody>';

		var data        = 'ID='+form_id;

		var responses 	= $.ajax({type: "GET", data: data, url: "Forms/GetResponcesByFormID", async: false, dataType: 'json'}).responseText;
		var resp 		= JSON.parse(responses);

		var but 		= '';

		console.log(resp.length)
		for(var i=0; i<resp.length;i++){
			var v = resp[i];

			but 		 = '<a class="btn btn-info btn-xs " rel="tooltip" data-original-title="View Submission" data-title="View" data-toggle="modal" data-target="#ViewSubmissionModal" data-submit-id="' + v.submit_id + '" aria-expanded="false"><span class="glyphicon glyphicon-zoom-in"></span></a>';
			but 		+= '<a class="btn btn-success btn-xs " rel="tooltip" data-original-title="Link to Resident" data-toggle="modal" data-target="#LinkToResidentModal" data-submit-id="' + v.submit_id + '" aria-expanded="false"><span class="glyphicon glyphicon-resize-small"></span></a>';
			but 		+= '<a href="Forms/PrintResponces?SubmitID=' + v.submit_id + '" target="_blank" rel="tooltip" data-original-title="Print Response" class="btn btn-warning btn-xs" ><span class="glyphicon glyphicon-print"></span></a>';
			but 		+= '<a class="btn btn-primary btn-xs " rel="tooltip" data-original-title="Send Form For Approval" data-toggle="modal" data-target="#SendFormModal" data-submit-id="' + v.submit_id + '" aria-expanded="false"><span class="glyphicon glyphicon-envelope"></span></a>';

			html 		+=	'<tr>';
			html 		+=	'	<td>' +v.submit_id+ '</td>';
			html 		+=	'	<td>' +v.res_name+ '</td>';
			html 		+=	'	<td>' +v.created+ '</td>';
			html 		+=	'	<td>' +but+ '</td>';
			html 		+=	'</tr>';
		    
		};

		html +=	'<tbody>';
		html +=	'</table>';
			
		
		return html;
}

function detailFormatter(index, row) {

	console.log(row.id);

	var form_id = row.id;

    var html 	 = '';
   	html +=	'<table data-toggle="table"';
   	html +=	'data-url="http://manage.connectliving.co.za/Forms/GetResponcesByID"';
   	html +=	'data-query-params="form_id='+form_id+'"';
   	html +=	'data-search="true"';
   	html +=	'data-show-refresh="true"';
   	html +=	'data-show-toggle="true"';
   	html +=	'data-show-columns="true"';
   	html +=	'data-show-export="true"';
   	html +=	'data-pagination="true"';
   	html +=	'data-detail-view="true"';
   	html +=	'data-detail-formatter="detailFormatter"';
   	html +=	'class="display table table-striped"';
   	html +=	'id="table"';
   	html +=	'>';
   	html +=	'<thead>';
   	html +=	'	<tr>';
   	html +=	'		<th data-field = "id">ID</th>';
   	html +=	'		<th data-field = "name">Name</th>';
   	html +=	'		<th data-field = "questions">Cell</th>';
   	html +=	'		<th data-field = "created">Question #</th>';
   	html +=	'		<th data-field = "created">Response</th>';
   	html +=	'		<th data-field = "created">Created</th>';
   	html +=	'		<th data-field = "buttons">Action</th>';
   	html +=	'	</tr>';
   	html +=	'</thead>';
   	html +=	'<tbody>';

   	var data        = 'ID='+form_id;

   	var responses = $.ajax({type: "GET", data: data, url: "Forms/GetResponcesByID", async: false, dataType: 'json'}).responseText;;
   	console.log(JSON.parse(responses));

   	$.each(JSON.parse(responses), function (key, value) {
   	    html +=	'<tr>';
   	    html +=	'<td>' +value.id+ '</td>';
   	    html +=	'<td>' +value.res_name+ '</td>';
   	    html +=	'<td>' +value.res_cell+ '</td>';
   	    html +=	'<td>' +value.q_num+ '</td>';
   	    html +=	'<td>' +value.responce+ '</td>';
   	    html +=	'<td>' +value.responce+ '</td>';
   	    html +=	'<td>' +value.created+ '</td>';
   	    html +=	'</tr>';
   	})
   	html +=	'<tbody>';
   	html +=	'</table>';
   	

    return html;
}


function get_responces(form_id){
	var html = '';
	
	 html +='<table data-toggle="table"';
	 html +='data-url="http://manage.connectliving.co.za/Forms/GetResponcesByID"';
	 html +='data-query-params="form_id='+form_id+'"';
	 html +='data-search="true"';
	 html +='data-show-refresh="true"';
	 html +='data-show-toggle="true"';
	 html +='data-show-columns="true"';
	 html +='data-show-export="true"';
	 html +='data-pagination="true"';
	 html +='data-detail-view="true"';
	 html +='data-detail-formatter="detailFormatter"';
	 html +='class="display table table-striped"';
	 html +='id="table"';
	 html +='>';
	 html +='<thead>';
	 html +='	<tr>';
	 html +='		<th data-field = "id">ID</th>';
	 html +='		<th data-field = "name">Name</th>';
	 html +='		<th data-field = "questions">Cell</th>';
	 html +='		<th data-field = "created">Question #</th>';
	 html +='		<th data-field = "created">Response</th>';
	 html +='		<th data-field = "created">Created</th>';
	 html +='		<th data-field = "buttons">Action</th>';
	 html +='	</tr>';
	 html +='</thead>';
	 


	// console.log(html);
	return html;



}