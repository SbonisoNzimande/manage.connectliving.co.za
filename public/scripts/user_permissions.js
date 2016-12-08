$(document).ready(function(){

	table        = $('#perm-table').DataTable({
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
                    "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                },
	    "ajax": host + 'UserPermissions/GetAllPermissions'
	});


    $.get(host + 'UserPermissions/GetPropertyList', '', function(response){
        var select_box      = '';
        var select_boxedt   = ''; 
        $.each(response, function(key, value){

            select_box += '<div class="checkbox">'
            select_box += '    <label class="ui-checks ui-checks-md">'
            select_box += '        <input type="checkbox" value="'+value.PropertyID+'" name="modules[]">'
            select_box += '        <i></i>'
            select_box += value.PropertyName
            select_box += '    </label>'
            select_box += ' </div>'

            select_boxedt += '<div class="checkbox">'
            select_boxedt += '    <label class="ui-checks ui-checks-md">'
            select_boxedt += '        <input type="checkbox" value="'+value.PropertyID+'" name="modulesedt[]">'
            select_boxedt += '        <i></i>'
            select_boxedt += value.PropertyName
            select_boxedt += '    </label>'
            select_boxedt += ' </div>'
        });
        $("#pro_list").html(select_box);
        $("#pro_listedt").html(select_boxedt);
     });


	$("#SavePermission").click(function(e){
		var form_data 		= $("#SaveForm").serialize();

    	$.post(host + 'UserPermissions/SavePerm', form_data, function(response){
    		var output = '';

    		if(response.status == true){
    			output = '<div class="alert alert-success"><p>Saved</p></div>';
    		}else{
    		   output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
    		}

    		

            $("#save_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
                  
                table.ajax.reload();
                $('#CreateModal').modal('hide');
             });
    	});

	});

    window.getPermEdit  = function(id) {
        var data        = 'ID='+id;
        $.get(host + 'UserPermissions/GetPermissionByID', data, function(response){
            var modules = JSON.parse(response.modules);

            console.log(typeof(modules));

             $('#EditForm').find(':checkbox[name^="modulesedt"]').each(function () {
                    $(this).prop("checked", ($.inArray($(this).val(), modules) != -1));
                });

            $("#PermissionTypeedt").val(response.permission_type);
            $("#edtID").val(response.permission_id);
        });
    };


    $("#EditPermission").click(function(e){
        var form_data       = $("#EditForm").serialize();

        $.post(host + 'UserPermissions/EditPerm', form_data, function(response){
            var output = '';

            if(response.status == true){
                output = '<div class="alert alert-success"><p>Saved</p></div>';
            }else{
               output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
            }


            $("#edit_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
                  
                table.ajax.reload();
                 $('#EditModal').modal('hide');
             });
        });

    });

    window.getPermDelete = function(id) {
        $("#deleteID").val(id);
    };

    $("#DeletePerm").click(function(){
        
        var ID        = $("#deleteID").val();
        //data to be sent to server
        post_data     = {'ID':ID.trim()};

        $.post(host + 'UserPermissions/DeletePerm', post_data, function(response){

            var output = '';
            if(response.status == false){
                output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
            }else if(response.status == true){
                output = '<div class="alert alert-success"><p>Deleted</p></div>';
            }
            

            $("#delete_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
                  
                table.ajax.reload();
                $('#DeleteModal').modal('hide');
             });

        }, 'json');// End post

    });

});