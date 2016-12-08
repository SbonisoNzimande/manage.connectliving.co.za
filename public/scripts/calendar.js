$(document).ready(function(){

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
						title: 'Reminder',
						description: title,
						start: start,
						end: end,
						allDay: allDay,
						'className': ["reminders"]
					},
					false // make the event "stick"
				);

				var eventData;

				eventData 	 = 'title=' + title;      
				eventData 	+= '&start=' + start.format();      
				eventData 	+= '&end=' + end.format();      

				/**
		         * ajax call to store event in DB
		         */

		        console.log('eventData: ' + eventData);

		        $.post('Calendar/AddEvent', eventData, function(response){
			 		console.log(response);
			    }, 'json');// End post

		        
			}
			calendar.fullCalendar('unselect');
		},
		// defaultDate: '2016-01-12',
		editable: true,
		eventLimit: true, // allow "more" link when too many events
		events: 'Calendar/GetAllEvents' ,
		eventDrop: function(event, delta) {

					
	            },

		eventAfterAllRender: function (view) {
						
					}, 
        eventRender: function(event, element) { 
            element.find('.fc-title').html("<b><u>" + event.title + "</u></b><br />"+ event.description); 
        } 
	});
  	

});