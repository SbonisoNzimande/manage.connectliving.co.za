$(document).ready(function(){
    var host     = 'http://' + window.location.hostname + '/index.php/';

    

    table        = $('#users-table').dataTable({
                        "dom": 'T<"clear">lfrtip',
                        "tableTools": {
                                    "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                                },
                        "ajax": host + 'UserManagement/GetAllUsers'
                    });

    

    window.getUserDelete = function(id) {
        $("#deleteID").val(id);
    };

    

    window.getUserEdit = function(id) {
        var data    = 'user_id='+id;
        $.get(host + 'UserManagement/GetUserByID', data, function(response){

            $("#RepNameEdit").val(response.repName);
            $("#Passwordedt").val(response.password);
            $("#AgencyEdit").val(response.agency);
            $("#StatusEdit").val(response.status);
            $("#edtID").val(response.rep_id);
        });
    };


    $("#EditUser").click(function(){
        var RepName          = $("#RepNameEdit").val();
        var Password         = $("#Passwordedt").val();
        var Agency           = $("#AgencyEdit").val();
        var Status           = $("#StatusEdit").val();
        var ID               = $("#edtID").val();
        var proceed          = true;

        if(RepName.trim() == ""){ 
            $("#RepNameEdit").css('border-color','#F33');
            $("#RepNameEdit").focus();
            proceed         = false;
        }else{
            $("#RepNameEdit").css('border-color','');
        }

        if(Password.trim() == ""){ 
            $("#Passwordedt").css('border-color','#F33');
            $("#Passwordedt").focus();
            proceed         = false;
        }else{
            $("#Passwordedt").css('border-color','');
        }

        if(Agency.trim() == ""){ 
            $("#AgencyEdit").css('border-color','#F33');
            $("#AgencyEdit").focus();
            proceed         = false;
        }else{
            $("#AgencyEdit").css('border-color','');
        }
        if(Status.trim() == ""){ 
            $("#StatusEdit").css('border-color','#F33');
            $("#StatusEdit").focus();
            proceed         = false;
        }else{
            $("#StatusEdit").css('border-color','');
        }

        if(proceed) {// Check to proceed

            //data to be sent to server
            post_data     = {   'RepName':RepName.trim(), 
                                'Password':Password.trim(),
                                'Agency':Agency.trim(),
                                'Status':Status.trim(),
                                'ID':ID.trim()
                            };


            $.post(host + 'UserManagement/EditUser', post_data, function(response){

                var output = '';

                if(response.status == false){
                    output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }else if(response.status == true){
                    output = '<div class="alert alert-success"><p>Saved</p></div>';
                }

                $("#add_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();

            }, 'json');// End post

        
        }else{

           $("#EditModal").effect('shake', 900 );
        }

    });

    $("#DeleteUser").click(function(){
        
        var ID        = $("#deleteID").val();

        //data to be sent to server
        post_data     = {'ID':ID.trim()};


        $.post(host + 'UserManagement/DeleteUser', post_data, function(response){

            var output = '';

            console.log(response);

            if(response.status == false){

                output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';

            }else if(response.status == true){

                output = '<div class="alert alert-success"><p>Deleted</p></div>';
            }

             $("#delete_err").html(output).show('slow').fadeIn().delay(3000).fadeOut();

        }, 'json');// End post

    });
    
});