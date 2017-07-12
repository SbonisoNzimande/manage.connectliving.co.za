$(document).ready(function(){

	var bill_table 	= $('#billing-table').DataTable({
						"dom": 'T<"clear">lfrtip',
						"tableTools": {
						            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
						        },
						
	    				"ajax": 'Dashboard/GetBillingList',
	    				"order": [[ 2, "desc" ]],
	    				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
				                     if ( aData[2] == "done" ){
				                        $('td', nRow).addClass('light-green-100');
				                     }
	    				         }
					});

	var maintain_table 	= $('#maintain-table').DataTable({
						"dom": 'T<"clear">lfrtip',
						"tableTools": {
						            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
						        },
						
	    				"ajax": 'Dashboard/GetMaintanaceList',
	    				"order": [[ 2, "desc" ]],
	    				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	    				                    if ( aData[2] == "done" ){
						                        $('td', nRow).addClass('light-green-100');
						                     }
	    				               }
					});

	var myqueries_table = $('#myqueries-table').DataTable({
						"dom": 'T<"clear">lfrtip',
						"tableTools": {
						            "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
						        },
						
	    				"ajax": 'Dashboard/GetMyTaskList',
	    				"order": [[ 2, "desc" ]],
	    				"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	    				                    if ( aData[2] == "done" ){
						                        $('td', nRow).addClass('light-green-100');
						                     }
	    				               }
					});
});