$(document).ready(function(){

	table        = $('#query-table').DataTable({
        "dom": 'T<"clear">lfrtip',
        "tableTools": {
                    "sSwfPath": "../public/libs/jquery/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                },
	    "ajax": 'EmergencyLog/GetLogsTable'
	});

});