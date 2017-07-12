$(document).ready(function(){

    window.show_loader  = function(div) {
        var loader      = '<div class="loading"> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> <div class="loading-bar"></div> </div>';
         div.html(loader);
    };

    window.hide_loader  = function(div) {
        var loader = '';
         div.html(loader);
    };

    $("[rel='tooltip']").tooltip();

	// Login Button
	$("#LoginForm").submit(function(e){
		e.preventDefault();
		//get input field values
        var email    	= $("#email").val(); 
        var password    = $("#password").val(); 

        var proceed 	= true;
        if(email.trim() == ""){ 
            $("#email").css('border-color','#F33');
            $("#email").focus();
            proceed		= false;
        }

        if(password.trim() == ""){ 
            $("#password").css('border-color','#F33');
            $("#password").focus();
            proceed 	= false;
        }

        show_loader($("#add_err"));

        if(proceed) {
        	//data to be sent to server
        	 post_data 	  = {'email':email.trim(), 'password':password.trim()};
        	 $.post(host_log + 'Admin', post_data, function(response){

        	 	var output = '';

        	 	if(response.status == false){

        	 		output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
        	 		$("#email").focus();

        	 	}else if(response.status == true){
        	 		
        	 		output = '<div class="alert alert-success"><p>Welcome, you\'re going through.... please wait!</p></div>';
        	 		window.setTimeout(function() {window.location.href= response.redirect;}, 2000);
        	 	}

        	 	$("#add_err").html(output).show('slow').fadeIn('slow');

        	 }, 'json');

        }
	});


    
});