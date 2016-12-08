$(document).ready(function(){
	var company_id 	= $('#company_id').val();

	show_loader($("#bargraph"));
	show_loader($("#activities"));

	// get_activities();
	// var obj 	= JSON.parse(get_card_object());
	show_cards();
	get_bar_graph();


	function get_activities(){
		var feedback = $.ajax({
			type: "GET",
			url:'Dashboard/GetAllActivities',
			async: false
		}).complete(function(){
			setTimeout(function(){get_activities();}, 10000);
		}).responseText;

		$('#activities').html(feedback);


	};

	function get_bar_graph(){
		$.ajax({
			type: "GET",
			dataType: 'json',
			url: 'Dashboard/GetBarGraph',
			error: function () {
				alert("An error occurred.");
			},
			success: function (dataSet) {

					var d1, xaxisLabels = [], i=0;

				    d1 	= dataSet.map(function(elt){return {label: elt[1], data: [[i++, elt[0]]]};});
				    i 	= 0;
				    xaxisLabels = dataSet.map(function(elt) { return [i++, elt[1]]; });
				    $.plot($("#bargraph"),
				           d1,
				           {
				             legend: {
				               show: true,
				               container: $('#legend1'),
				             },
				             series: {
				                   bars: {
				                     show       : true,
				                     align      : 'center',
				                     //dataLabels : true,
				                     barWidth : 0.4
				                   }
				             },
				             xaxis: { ticks: xaxisLabels },
				             yaxis: {
				                   ticks: 10
				             },
				             grid: {
				                   show: true,
				                   backgroundColor: { colors: ["#fff", "#eee"] }
				             }
				           });
			}
		});
	};



	

  function get_card_object() {
		var feedback = $.ajax({
			type: "GET",
			url: 'Dashboard/GetMaintenanceCards',
			async: false
		}).responseText;
			
		return feedback;
  	};


  	// ////// ////

	  $("#SaveComment").click(function(){
	  	var Comment    = $.trim($("#comment").val());
	  	var ID    		= $.trim($("#QID").val());
	  	var proceed    = true;

	  	console.log(ID);

	  	if(Comment  == ""){ 
	  	    $("#Comment").css('border-color','#F33');
	  	    $("#Comment").focus();
	  	    proceed         = false;
	  	}else{
	  	    $("#Comment").css('border-color','');
	  	}

	  	if(proceed) {// Check to proceed
	  	    //data to be sent to server
	  	    post_data     = {   
	  	    					'Comment':Comment.trim(),
	  	    					'ID':ID.trim()
	  	                    };
	  	    $.post(host + 'Queries/MarkDone', post_data, function(response){

	  	        var output = '';
	  	        if(response.status == false){
	  	            output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
	  	        }else if(response.status == true){
	  	            output = '<div class="alert alert-success"><p>Saved</p></div>';
	  	        }

	  	         $("#save_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
	  				$('#MarkDone').modal('hide');
	  				show_cards();
	  	         });
	  	         

	  	    }, 'json');// End post
	  	
	  	}else{

	  	   $("#MarkDone").effect('shake', 900 );
	  	}

	  });

	$.get('Dashboard/GetAllAdminUsers', 'company_id='+company_id, function(response){
		
		var level_select = '<option></option>';
		$.each(response, function(key, value){
			level_select += '<option value="' +value.adminID+ '">' +value.full_name+ '</option>';
		});

		$("#AssignTo").html(level_select);
		// $("#AssignToedt").html(level_select);
	});


	$.get(host + 'Dashboard/GetPropertyList', '', function(response){
		
		var level_select = '<option></option>';
		        	
		$.each(response, function(key, value){
			level_select += '<option value="' +value.propertyID+ '">' +value.propertyName+ '</option>';
		});

		$("#PropertyList").html(level_select);
		sel_picker($('#PropertyList'), 'Property');

	});

	function sel_picker(div, txt) {
		div.selectpicker({
			title : txt,
			width: '110px'
		});
	};

	


	function show_cards() {

		show_loader($("#maintenance-cards"));

		$.ajax({
			type: "GET",
			dataType: 'json',
			url: host + 'Dashboard/GetMaintenanceCards',
			error: function () {
				alert("An error occurred.");
			},
			success: function (dataobj) {

				var card = ''
				$("#maintenance-cards").html('');
				var count;

				$.each(dataobj, function(key, value){
					//  x % y
					card += '<div class="col-sm-3">';

					card += '<div class="panel panel-card">';
					card += '<div class="card-heading">';
					card += '<h5>' +value.property_name+ '</h5>';
					card += '</div>';// Close heading
					card += '<div class="card-tools">';// Start card tools
					card += '<ul class="list-inline">';
					card += '<li class="dropdown">';
					card += '<a md-ink-ripple data-toggle="dropdown" class="md-btn md-flat md-btn-circle">';
					card += '<i class="mdi-navigation-more-vert text-md"></i>';
					card += '</a>';
					card += '<ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">';
					card += ' <li><a data-toggle="modal" data-target="#AssignModal" class="ass_but" id="' +value.id+ '">Reassign</a></li>';
					card += ' <li><a data-toggle="modal" data-target="#MarkDone" class="mark_done" id="' +value.id+ '">Mark Done</a></li>';
					card += '</ul>';
					card += '</li>';
					card += '</ul>';
					card += '</div>';// End card tools
					card += '<div class="item">';
					card += '<img src="'+value.image+'" style="width:100%;height:100%"  />';
					card += '</div>';
					card += '<div class="p">';
					
					card += '<p><i>' +value.date+ '</i></p>';
					card += '<p>' +value.query_type+ '</p>';
					card += '<p>' +value.query+ '</p>';
					card += '</div>';
					card += '</div>';
					card += '</div>';

					count ++;

					
				});

				$("#maintenance-cards").html(card);

				$(".mark_done").click(function(e) {

				        $("#QID").val($(this).attr("id"));
				   });

				$(".ass_but").click(function(e) {

					$("#AID").val($(this).attr("id"));
				});
			}
		});
		

	};

	$("#AssignUser").click(function(e) {

        var data = {'id' : $("#AID").val(), 
        			'assinee_id' : $("#AssignTo").val()
        			}
        $.get(host + 'Queries/AssignUser', data, function(response){

        	if(response.status == true){
        		output = '<div class="alert alert-success"><p>Submited</p></div>';
        	}else{
        		output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
        	}

        	$("#ass_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
  				$('#AssignModal').modal('hide');
  	         });
  	         
        });
	});


	function SetID(id) {

		console.log(id);
	    $("#QID").val(id);
	};

	// File upload
	$('#upload_image').on("click", function(e){  

		event.preventDefault();
		// alert(5);
		
	});

	$("#CreateQueryForm").submit(function(e){
		e.preventDefault();

		var form_data = new FormData($('#CreateQueryForm')[0]);

		if($("#Image").length != 0) {
			var file_data = $("#Image").prop("files")[0];  
			form_data.append("file", file_data);
		}

		console.log(form_data);

		$.ajax({
			url: 'Queries/SaveQuery',  
			type: 'POST',   
			data: form_data,
			processData: false,
			contentType: false,
			xhrFields: {
				onprogress: function (e) {

					
				},
				beforeSubmit: function (e) {

					alert('before');
				}
			},
			success: function (response) {  
                var output = '';
                if(response.status == true){
                	output = '<div class="alert alert-success"><p>Submited</p></div>';

                }else{
                	$(".progress").hide();
                	$("html, body").animate({ scrollTop: 0 }, "slow");
                	output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
                }


                $("#create_q_err").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
		          show_cards();
					
		         });
	        }
		});

	});


	var calendar = $('#calendar').fullCalendar({

		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		selectable: true,
		selectHelper: true,
		// Selectable

		select: function(start, end, allDay) {
			var title = prompt('Event Title:');
			if (title) {
				calendar.fullCalendar('renderEvent',
					{
						title: 'Added Event',
						description: title,
						start: start,
						end: end,
						allDay: allDay
					},
					true // make the event "stick"
				);

				var eventData;

				eventData 	 = 'title=' + title;      
				eventData 	+= '&start=' + start.format();      
				eventData 	+= '&end=' + end.format();      

				/**
		         * ajax call to store event in DB
		         */

		        console.log('eventData: ' + eventData);

		        $.post('Dashboard/AddEvent', eventData, function(response){
			 		console.log(response);
			    }, 'json');// End post

		        
			}
			calendar.fullCalendar('unselect');
		},
		// defaultDate: '2016-01-12',
		editable: true,
		eventLimit: false, // allow "more" link when too many events
		events: 'Dashboard/GetAllEvents' ,
		eventDrop: function(event, delta) {

					
	            },

		eventAfterAllRender: function (view) {
						
					}, 
        eventRender: function(event, element) { 
            element.find('.fc-title').html("<b><u>" + event.title + "</u></b>" + "<br/>" + event.description); 
        } 
	});
  	

});