$(document).ready(function(){
	// Login Button
	$("#ForgotPasswordForm").submit(function(e){
		e.preventDefault();
		//get input field values
        var email    	= $("#email").val(); 

        var proceed 	= true;
        if(email.trim() == ""){ 
            $("#email").css('border-color','#F33');
            $("#email").focus();
            proceed		= false;
        }

        

        show_loader($("#add_err"));

        if(proceed) {
        	//data to be sent to server
        	 post_data 	  = {'email':email.trim()};
        	 $.post('ForgotPassword/SendPasswordReminder', post_data, function(response){

        	 	var output = '';

        	 	if(response.status == false){

        	 		output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
        	 		$("#email").focus();

        	 	}else if(response.status == true){
        	 		
        	 		output = '<div class="alert alert-success"><p>Password Reminder Sent</p></div>';
        	 		window.setTimeout(function() {window.location.href = 'http://manage.connectliving.co.za/Login';}, 2000);
        	 	}

        	 	$("#add_err").html(output).show('slow').fadeIn('slow');

        	 }, 'json');

        }
	});

});