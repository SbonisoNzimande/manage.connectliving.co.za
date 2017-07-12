$(document).ready(function(){
	var host 	 = 'http://admin.nconnectapp.co.za/index.php/';
	var store_id = $("#store_id").val();

	var form_data 	= {'store_id': store_id};

	$.get('SurveySetup/GetSurveysByStoreID', form_data, function(response){

			// Build select
        	var survey_list = '<h5>Survey\'s to Complete</h5>';
        	if(response.length !== 0 ){

        		$.each(response, function(key, value){
        			var title 	= value.title;
        			var id 		= value.id;

        			survey_list += '<div class="row">';
        			survey_list += '<div class="col m12 s12">';
        			survey_list += '<a class="waves-effect waves-light btn-large amber darken-2" href="survey.php?id='+id+'&store_id='+store_id+'" style="width:100%"">';
        			survey_list += title;
        			survey_list += '</a></div>';
        			survey_list += '</div>';

        		});
        	}else{
        		survey_list = '<h5>No Survey\'s For This Store</h5>';
        	}

        	

			$("#SurveyArea").html(survey_list);
	});

});