
<script>

// call this from the developer console and you can control both instances
var clndr1 = {};
var currentTarget = {};
var eventArray = [];	

var weekday = new Array(7);
weekday[0] = "Monday";
weekday[1] = "Tuesday";
weekday[2] = "Wednesday";
weekday[3] = "Thursday";
weekday[4] = "Friday";
weekday[5] = "Saturday";
weekday[6]=  "Sunday";

$(document).ready( function() {

	// assuming you've got the appropriate language files,
	// clndr will respect whatever moment's language is set to.
	// moment.locale('ru');

	// here's some magic to make sure the dates are happening this month.
	var thisMonth = moment().format('YYYY-MM');

 	refreshEvents();
	
	// the order of the click handlers is predictable.
	// direct click action callbacks come first: click, nextMonth, previousMonth, nextYear, previousYear, or today.
	// then onMonthChange (if the month changed).
	// finally onYearChange (if the year changed).
	clndr1 = $('.cal1').clndr({
		events: eventArray,
		clickEvents: {
		  click: function(target) {
		  	console.log(target);
		  	$(currentTarget.element).children(".day-contents").removeClass("day-selected");
		  	$(target.element).children(".day-contents").addClass("day-selected");
		  	// Set current target
		    currentTarget = target;
		    // Change left panel title
		 	_leftPanelTitle(currentTarget, eventArray);
		 	// Display existing events
		 	_leftPanelEvents(currentTarget, eventArray);
		  },
		  nextMonth: function() {
		    console.log('next month.');
		  },
		  previousMonth: function() {
		  	console.log('previous month.');
		  },
		  onMonthChange: function() {
		    console.log('month changed.');
		  },
		  nextYear: function() {
		    console.log('next year.');
		  },
		  previousYear: function() {
		    console.log('previous year.');
		  },
		  onYearChange: function() {
		    console.log('year changed.');
		  }
		},
		multiDayEvents: {
		  startDate: 'start',
		  endDate: 'end',
		  singleDay: 'date'
		},
		ready: function() {
	      var self = this;
	      $(this.element).on('mouseover', '.day', function(e) {
	        var target = self.buildTargetObject(e.currentTarget, true);
	      }).on('mouseleave','.day',function(e){
	      	var target = self.buildTargetObject(e.currentTarget, true);
	      });

	    // Initialize DatePicker
	    initDatePicker();


	    },
		showAdjacentMonths: true,
		adjacentDaysChangeMonth: false
	}

	);
});

var start;
var end;
var today;

function refreshEvents(){
	$.ajax({
		url: '/widgets/availabilities/getCalendar',
		dataType: 'json',
		type: 'POST',
		success: function(data) {
			 eventArray = data;
		},
		async: false
	});
}

function initDatePicker(){
	today = kendo.date.today();

	start = $("#start").kendoDateTimePicker({
	    value: today,
	    change: startChange,
	    parseFormats: ["MM/dd/yyyy"]
	}).data("kendoDateTimePicker");

	end = $("#end").kendoDateTimePicker({
	    value: today,
	    change: endChange,
	    parseFormats: ["MM/dd/yyyy"]
	}).data("kendoDateTimePicker");

	$("#start").val(today);
	$("#end").val(today);
}

/* Will take care of changing the date in the left panel */
function _leftPanelTitle(target){
	var date = new Date(target.date._i);
	var day = date.getDay();
	var day = weekday[day];
	$("#event-day").html(target.date._i.split('-')[2]+" "+day+" "+target.date._i.split('-')[0])
}

/* Will add the events to the panel */
function _leftPanelEvents(target, eventArray){

	// Prepare day/time UI panel.
	$("#event-item-list ul").html("");
	for(i=0;i<26;i++){
		var timeId = i;
		var suffix = "am";
		var hour = i %13;
		if(hour == 0){	hour=1;	i++; }
		if(i > 11){	suffix = "pm"}
		if(i == 25){suffix = "am"}
		var time = hour+""+suffix;
		// Display proper time
		if(hour < 10){	hour   = "0"+hour;	}
		// Prepend 0 to match date time format
		if (i < 10 ){ timeId = "0"+i; } 
		// Readjust time caused by mod
		if (i > 12 ){ timeId = (i-1); }
		$("#event-item-list ul").append('<li><div class="calendar_time">'+time+'</div><div class="calendar_line" id="hour_'+timeId+'"></div></li>');
	}

	// Add events if any.
    eventArray.some(function (ev, index, _ary) {
	    var eventDateStart = ev.start.split(" ")[0];
	    var eventDateEnd   = ev.end.split(" ")[0];

	    // Handle Multi Days Event
	    var eventStart = createDate(eventDateStart);
	    var eventEnd   = createDate(eventDateEnd);
	    var multi = isEventMultiDays(eventStart, eventEnd);

	    // Get Date object from target
	    var targetDate = createDate(target.date._i);

	    if(targetDate > eventStart && targetDate < eventEnd){
	    	displayMultiDayEventMiddle(ev);
	    } else if (targetDate > eventStart && target.date._i === eventDateEnd){
	    	displayMultiDayEventEnd(ev)
	    } else if (eventDateStart == target.date._i) {		
    		displaySingleDayEvent(ev ,multi);
    	}
	});

	$("#event-item-list").scrollTop(280);
}

/* Initialize left panel with today */
function _leftPanelInit(target){
	var rgx = /(\d{4})-(\d{2})-(\d{2})/;
	var date = target.classes.match(rgx);
  	target.date._i = date[0]; 
  	// Set current target
  	currentTarget = target; 	
    // Change left panel title
 	_leftPanelTitle(currentTarget, eventArray);
 	// Display existing events
 	_leftPanelEvents(currentTarget, eventArray);
}

/* Adds an event to the calendar */
function addEvent(){
	initAddEventPanel(currentTarget.date._d);
	$("#add_event_panel").fadeIn();
}

function closeAddEventPanel(){
	$("#add_event_panel").fadeOut();
	$("#add_detail_panel_loader").fadeOut();
}

function initAddEventPanel(dateVar){
    var startDate = dateVar;
    endDate = dateVar;

    start.value(startDate);
    end.value(endDate);

    startDate = new Date(startDate);
    startDate.setDate(startDate.getDate());
    end.min(startDate);

    var date = formatDate(startDate);
    var time = formatTime(startDate);
    $("#event_from_day").html(date);
    $("#event_from_time").html(time);
    $("#event_to_day").html(date);
    $("#event_to_time").html(time);
}

function startChange(dateVar) {
	// Used when changing directly from date picker
	// as opposed to init().
    var startDate = start.value();
    var endDate   = end.value();

    // Create date format
    startDate = new Date(startDate);
    // Set start date
    //startDate.setDate(startDate.getDate());
    end.min(startDate);

    if(endDate < startDate){
    	end.value().setDate(startDate.getDate());
    	end.value().setTime(startDate.getTime());
    	setToView(startDate);
    }
    
    setFromView(startDate);
}

function endChange(dateVar) {
	// Used when changing directly from date picker
	// as opposed to init().
    var endDate = end.value();

    // Create date format
    endDate = new Date(endDate);
    endDate.setDate(endDate.getDate());
    start.max(endDate);    

    setToView(endDate);
}

function setFromView(startDate){
	var date = formatDate(startDate);
    var time = formatTime(startDate);
    $("#event_from_day").html(date);
    $("#event_from_time").html(time);
}

function setToView(endDate){
	var date = formatDate(endDate);
    var time = formatTime(endDate);
    $("#event_to_day").html(date);
    $("#event_to_time").html(time);
}

function displaySingleDayEvent(ev, multi){
	var event_time    = ev.start.split(" ")[1].split(":")[0];
	var event_time_30 = ev.start.split(" ")[1].split(":")[1];
	var event_end	  = (multi ? 24 : ev.end.split(" ")[1].split(":")[0]);
	var event_length  = event_end-event_time;
	var event_name = (ev.title == null || ev.title == "" ? ev.type : ev.title);
	console.log(event_time_30);
	if(event_time_30 == "30")
		$("#hour_"+event_time).html('<div class="calendar_event" style="top:20px !important;height:'+((event_length*39)+(event_length-1))+'px">'+event_name+'<div class="delete_event" id="'+ev.eventId+'" onclick="submitDeleteEvent(this)">+</div>');
	else
		$("#hour_"+event_time).html('<div class="calendar_event" style="height:'+((event_length*39)+(event_length-1))+'px">'+event_name+'<div class="delete_event" id="'+ev.eventId+'" onclick="submitDeleteEvent(this)">+</div>');
}	

function displayMultiDayEventEnd(ev){
	var event_name    = (ev.title == null || ev.title == "" ? ev.type : ev.title);
	var event_length     = ev.end.split(" ")[1].split(":")[0];
	var event_time_30 = ev.start.split(" ")[1].split(":")[1];
	if(event_time_30 == "30")
		$("#hour_01").html('<div class="calendar_event" style="height:'+((event_length*39)+(event_length-1)-20)+'px">'+event_name+'</div>');
	else
		$("#hour_01").html('<div class="calendar_event" style="height:'+((event_length*39)+(event_length-1))+'px">'+event_name+'</div>');
}

function displayMultiDayEventMiddle(ev){
	var event_name    = (ev.title == null || ev.title == "" ? ev.type : ev.title);
	$("#hour_01").html('<div class="calendar_event" style="height:'+((24*39)+(24-1))+'px">'+event_name+'</div>');
}

/* HELPER FUNCTIONS */
function formatDate(date){
	var date = date.toString().split(" ");
	return date[0]+', '+date[1]+' '+date[2];
}
function formatTime(date){
	var date = date.toString().split(" ");
	return date[4];
}
function createDate(date){
	var year  = parseInt(date.split("-")[0]);
	var month = parseInt(date.split("-")[1]);
	var day   = parseInt(date.split("-")[2]);
	return new Date(year,month,day);
}
function isEventMultiDays(start, end){
    return end > start;
}

$("span[aria-controls='start_dateview']").hover(function(){ $("#event_from_day").css("font-weight","bold"); }, function(){ $("#event_from_day").css("font-weight","normal"); });
$("span[aria-controls='start_timeview']").hover(function(){ $("#event_from_time").css("font-weight","bold"); },function(){ $("#event_from_time").css("font-weight","normal"); });
$("span[aria-controls='end_dateview']").hover(function(){ $("#event_to_day").css("font-weight","bold"); }, function(){ $("#event_to_day").css("font-weight","normal"); });
$("span[aria-controls='end_timeview']").hover(function(){ $("#event_to_time").css("font-weight","bold"); },function(){ $("#event_to_time").css("font-weight","normal"); });


function submitAddEvent(){
	var valid = true;
	
	// Show Loader
	$("#add_detail_panel_loader").fadeIn();

	// Validation
	var event_name = $("#event_name").val();
	if(event_name.length < 1){
		valid = false;
		$("#event_name").css("border-color","rgba(255,0,0,0.5) !important");
	}
	if(valid){
		$.post( 
			'/widgets/appointments/addEdit', 
			$('form#add_appointment_form').serialize()) 
			.done( function(data) {
				successMessage(data);
		    	closeAddEventPanel();
		    	refreshEvents();
		    	_leftPanelEvents(currentTarget, eventArray);
		    	callWidget("w_calendar_full");
			})
		    .fail( function(xhr, textStatus, errorThrown) {
		        errorMessage(xhr.responseText);
		        $("#add_detail_panel_loader").fadeOut();
		    });
	} else {
		errorMessage("You are missing some fields. Event name and dates are required.");
		$("#add_detail_panel_loader").fadeOut();
	}
}

function submitDeleteEvent(ev){
	var eventID = $(ev).attr("id");
    $.ajax({
	    url: '/widgets/appointments/destroy?'+$.param({"id": eventID}),
	    type: 'DELETE',
	    success: function(result) {
	        successMessage(result);
        	refreshEvents();
        	_leftPanelEvents(currentTarget, eventArray);
        	callWidget("w_calendar_full");
	    },
	    error:function(error){
	    	$(ev).parent().fadeIn();
	    	errorMessage(error.responseJSON);
	    }
	});
	$(ev).parent().fadeOut();
}


$("#event-item-list").niceScroll({cursorcolor:"rgba(158, 255, 230,0.5)",cursorborder:"none"});

</script> 

<!-- <div id='calendar'></div> -->

<!-- <div class="calendar_event hour_long">Task: Reminder</div> -->
 <div class="event_control">
 	<div class='event-listing'>
	    <div class='event-listing-title'>
	    	Events on <span id='event-day'></span> 
	    	<a href="javascript:void(0);" onclick="addEvent()" id="add_event">+</a>
	    </div>
	    <div id='event-item-list'>	
	    	<ul>
	    	</ul>
	    </div>
    </div>
 </div>
 	<form method="POST" id ="add_appointment_form" action="">
	 	<div class='event_add_panel' id='add_event_panel'>
	      <div class='event_add_detail_panel'>
	        <div id="add_detail_panel_loader"><img src="/img/icon_logo_green.png" class="objblink" width="80px" height="50px"></div>
	        <input type='text' class='event_name event_add_group' placeholder='event title' name="appointment" id="event_name"/>
	        <div class='event_dates event_add_group'>
	          <div class='all_day filters'>
	            <div>All day</div>
	            <label>
	               <input name='' onclick='' type='checkbox' value='all_day'>
	               <span class='icon'><i class='fa fa-check'></i></span>
	            </label>
	          </div>
	          <div class='event_from_to'>
	            <div class='event_from event_container'>
	              <input type='text' class='form-control' id='start' name="dateStart"/>
	              <div id='event_from_day'></div>
	              <div id='event_from_time'></div>
	            </div>
	            <div class='event_to event_container'>
	              <input type='text' class='form-control' id='end' name="dateEnd"/>
	              <div id='event_to_day'></div>
	              <div id='event_to_time'></div>
	            </div>
	          </div>
	        </div>
	        <input type='text' class='event_add_group blurred_input' placeholder='location'/>
	        <input type='text' class='event_add_group blurred_input' placeholder='client'/>
	        <textarea class='event_add_group blurred_input' placeholder='description' rows='4'/>
	        <div class='event_button_container'>
	          <a href='javascript:void(0)' onclick='closeAddEventPanel()' class='cancel'>Cancel</a>
	          <a href='javascript:void(0)' class='create' onclick="submitAddEvent()">Save Event</a>
	        </div>

	      </div>
	    </div>
    </form>
 <div class="cal1">
 </div>





