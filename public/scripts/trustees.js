$(document).ready(function(){

	var prop_id 	= $('#prop_id').val();
	var prop_name 	= $('#prop_name').val();
	var data 	 	='prop_id=' + prop_id + '&prop_name=' + prop_name;


	$("#UploadDocumentForm").on( 'submit', function(ev) {
		ev.preventDefault();

		var form_data = new FormData($('#UploadDocumentForm')[0]);

		if($("#UploadLogoFile").length != 0) {
			var file_data = $("#UploadLogoFile").prop("files")[0];  
			form_data.append("file", file_data);
		}

		console.log(form_data);

		$(this).ajaxSubmit({ 
			target:   '#targetLayer', 
			beforeSubmit: function() {
				$("[id='progress-bar']").width('0%');
			},
			uploadProgress: function (event, position, total, percentComplete){	
				$("[id='progress-bar']").width(percentComplete + '%');
				$("[id='progress-bar']").html('<div id="progress-status">' + percentComplete +' %</div>')
			},
			success:function (response){
				console.log(response);
				var output = '';
				if(response.status == true){
					output = '<div class="alert alert-success"><p>Uploaded</p></div>';

				}else{
					$(".progress").hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					output = '<div class="alert alert-danger"><p>'+response.text+'</p></div>';
				}

				$("#error_save_doc").html(output).fadeIn('slow').delay(3000).fadeOut('fast', function(){
					$("[id='progress-bar']").width('0%');
			 		$('#table-documents').bootstrapTable('refresh', {
						silent: true
					});
				});
			},
			resetForm: true 
		}); 
		return false; 


	});

	

	// Render callender when tabs are shown
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	       $('#calendar').fullCalendar('render');
	       // $('#calendar1').fullCalendar('render');
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
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);

					var eventData;

					// eventData = { 
					//                 'prop_id': prop_id,
					//                 'title': title,
					//                 'start': start.format(),
					//                 'end': end.format(),
					//                 'allDay': allDay
					//             };

					eventData 	 = 'prop_id=' + prop_id;      
					eventData 	+= '&title=' + title;      
					eventData 	+= '&start=' + start.format();      
					eventData 	+= '&end=' + end.format();      

					/**
			         * ajax call to store event in DB
			         */

			        console.log('eventData: ' + eventData);

			        $.post('Trustees/AddEvent', eventData, function(response){
				 		console.log(response);
				    }, 'json');// End post

			        // jQuery.post(
			        //     "Trustees/AddEvent" // your url
			        //     , { // re-use event's data
			        //         prop_id: prop_id,
			        //         title: title,
			        //         start: start,
			        //         end: end,
			        //         allDay: allDay
			        //     }
			        // );
				}
				calendar.fullCalendar('unselect');
			},

		// defaultDate: '2016-01-12',
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		events: 'Trustees/GetAllEvents?prop_id=' +prop_id,
		eventDrop: function(event, delta) {

						console.log(event);

						console.log('title: ' + event.id);
						console.log('start: ' + event.start.format());

						var eventData;
						eventData 	 = 'id='     + event.id;      
						eventData 	+= '&title=' + event.title;      
						eventData 	+= '&start=' + event.start.format();      
						eventData 	+= '&end=' 	 + event.end.format();  


						$.post('Trustees/EditEvent', eventData, function(response){
					 		console.log(response);
					    }, 'json');// End post


		                // alert(event.title + ' was moved ' + delta + ' days\n' +
		                //     '(should probably update your database)');
		            },

		eventAfterAllRender: function (view) {
						//Use view.intervalStart and view.intervalEnd to find date range of holidays
						//Make ajax call to find holidays in range.
						var fourthOfJuly = moment('2014-07-04','YYYY-MM-DD');
						var holidays = [fourthOfJuly];
						var holidayMoment;
						for(var i = 0; i < holidays.length; i++) {				
							holidayMoment = holidays[i];
							if (view.name == 'month') {
								$("td[data-date=" + holidayMoment.format('YYYY-MM-DD') + "]").addClass('holiday');
							} else if (view.name =='agendaWeek') {
								var classNames = $("th:contains(' " + holidayMoment.format('M/D') + "')").attr("class");
								if (classNames != null) {
									var classNamesArray = classNames.split(" ");
									for(var i = 0; i < classNamesArray.length; i++) {
										if(classNamesArray[i].indexOf('fc-col') > -1) {
											$("td." + classNamesArray[i]).addClass('holiday');
											break;
										}
									}
								}
							} else if (view.name == 'agendaDay') {
								if(holidayMoment.format('YYYY-MM-DD') == $('#calendar').fullCalendar('getDate').format('YYYY-MM-DD')) {
									$("td.fc-col0").addClass('holiday');
								};
							}
						}
					}
	});


	


});