$(document).ready(function(){
	window.get_job_statuses = function(id) {

		$.get('AllJobs/GetEnums', {'type': 'job_status'}, function(response){
			$("#" +id).closest('tr').addClass('amber-200').fadeIn('slow');
		});
	};
});