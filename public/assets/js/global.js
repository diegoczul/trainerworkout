// JavaScript Document

//GLOBAL VARIABLES


var widgetsToReload = [];
var widgetsList = {};
var imgLoad = $('<img />', { src : '/assets/img/tw-gif.gif' });
var loadingIcon = null;
var loadingDiv = null;
var placeholder = "assets/img/client.png";
var loadingCounter = 0;
var imgLoadSpinner = $("<img /", { src : "/assets/img/loading-spinner_.gif "});
var $TWBlockLoading = $('<div id="lodingBlock"><img id="loadingBlockImg" src="/assets/img/loading-spinner_.gif"></div>');


var debug = true;


//TRAINEE
widgetsList['w_weights'] = "/widgets/weight";
widgetsList['w_workouts'] = "/widgets/workouts";
widgetsList['w_workouts_create'] = "/widgets/workouts_create";
widgetsList['w_workoutsTrainee'] = "/widgets/workoutsTrainee";
widgetsList['w_tags'] = "/widgets/tags";
widgetsList['w_tagsWorkout'] = "/widgets/tagsWorkout";
widgetsList['w_trendingWorkouts'] = "/widgets/trendingWorkouts";
widgetsList['w_workoutMarket'] = "/widgets/workoutMarket";
widgetsList['w_workoutMarket_full'] = "/widgets/workoutMarket/full";
widgetsList['w_exercises'] = "/widgets/exercises";
widgetsList['w_exercises_full'] = "/widgets/exercises/full";
widgetsList['w_objectives'] = "/widgets/objectives";
widgetsList['w_objectives_full'] = "/widgets/objectives/full";
widgetsList['w_weights_full'] = "/widgets/weight/full";
widgetsList['w_pictures_full'] = "/widgets/pictures/full";
widgetsList['w_friends'] = "/widgets/friends";
widgetsList['w_friends_full'] = "/widgets/friends/full";
widgetsList['w_measurements'] = "/widgets/measurements";
widgetsList['w_measurements_full'] = "/widgets/measurements/full";

//TRAINER
widgetsList['w_clients'] = "/widgets/clients";
widgetsList['w_appointments'] = "/widgets/appointments";
widgetsList['w_calendar_full'] = "/widgets/calendar/full";
widgetsList['w_calendarActivity_full'] = "/widgets/calendarActivity/full";
widgetsList['w_feedClients'] = "/widgets/clientsFeed";
widgetsList['w_feedClient'] = "/widgets/clientFeed";
widgetsList['w_feedClients_full'] = "/widgets/clientsFeed/full";
widgetsList['w_feedClient_full'] = "/widgets/clientFeed/full";
widgetsList['w_video_word_full'] = "/widgets/videoWord";
widgetsList['w_biography_full'] = "/widgets/biography/full";
widgetsList['w_testimonials_full'] = "/widgets/testimonials/full";
widgetsList['w_tasks'] = "/widgets/tasks";
widgetsList['w_sessions'] = "/widgets/sessions";
widgetsList['w_sessions_full'] = "/widgets/sessions/full";
widgetsList['w_workoutSales'] = "/widgets/workoutSales";
widgetsList['w_workoutsTrainer_full'] = "/widgets/workoutsTrainer/full";
widgetsList['w_workoutsTrainer'] = "/widgets/workoutsTrainer";
widgetsList['w_workoutsClient'] = "/widgets/workoutsClient";
widgetsList['w_workoutsLibrary'] = "/widgets/workoutsLibrary";


//REPORTS
widgetsList['r_workoutsPerformance'] = "/reports/workoutsPerformance";


function toggle(id){
	$("#"+id).slideToggle();
	$("#"+id).closest(".widgets").find(".emptyMessage").toggle();
}

function down(id){
	$("#"+id).slideDown();
}

function up(id){
	$("#"+id).slideUp();
}


function hideKeyboard(element) {
    element.attr('readonly', 'readonly'); // Force keyboard to hide on input field.
    element.attr('disabled', 'true'); // Force keyboard to hide on textarea field.
    setTimeout(function() {
        element.blur();  //actually close the keyboard
        // Remove readonly attribute after keyboard is hidden.
        element.removeAttr('readonly');
        element.removeAttr('disabled');
    }, 100);
}


$('input').keyup(function(event) {
    if (event.which === 13) {
      $(this).blur();
    }
});

function upAndClearAdd(){
	$(".add").each(function(i, obj) {
	    $(obj).slideUp();
	    var userId = "";
	    userId = $(obj).find("input[name='userId']").val();
	    $(obj).find('input[type=text]').val('');
	    $(obj).find('input[type=file]').val('');
	    //$(obj).find('input:hidden').val('');
	    $(obj).find('textarea').val('');
	    //$(obj).find("form").trigger('reset');
	    if(userId!=""){
	    	$(obj).find("input[name='userId']").val(userId);
	    }
	});
}

function toggleSideBar(){

	if($(".sidebar-nav").css("display") == "none"){
		$("#page-wrapper").css("margin-left","250px");
		$(".sidebar-nav").css("display","block");
	} else {
		$("#page-wrapper").css("margin-left","0px");
		$(".sidebar-nav").css("display","none");
	}
}



function showMore(){
    $(".moreOptionsButton").css("display", "inline");
}


function showLess(){
    $(".moreOptionsButton").css("display", "none");
}


//Print for datatables show button.
function echoEdit(id,functionName){
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="edit('+id+')"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}
//Print for datatables show button.
function echoRemove(id,functionName){
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-times"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del('+id+')"><i class="fa fa-times"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoRemoveRow(id,functionName){
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'($(this),'+id+')"><i class="fa fa-times"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="delRow($(this),'+id+')"><i class="fa fa-times"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoCustomFunction(id,functionName,buttonText){
	var output = "";
	output = '<td class="center"><button class="btn btn-primary" type="button" onclick="'+functionName+'(\''+id+'\',$(this))">'+buttonText+'</button></td>';
	return output;
}

//Print for datatables show button.
function echoEditString(id,functionName){
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="edit(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}
//Print for datatables show button.
function echoRemoveString(id,functionName){
	var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
		}
		return output;
}

function activateChosen(){
	$(".chosen-select").chosen({search_contains: true});
}

function successMessage(message){
	var numOfNotif = $(".systemMessages > div").length;
	var notifId    = 'successBoxId'+(numOfNotif+1);
	try
	{
	   var msg = "";

	   if(typeof message == "string"){
	   		msg = message;
	   } else if(typeof message === 'object'){
	   	msg = JSON.parse(message);
	   }
	}
	catch(e)
	{
	   msg = message;
	   msg = JSON.stringify(msg);
	   msg = msg.replace(/(\r\n|\n|\r)/gm,"");
	}
	$(".systemMessages")
		.show()
		.append("<div class='successBox' id='"+notifId+"''>"+msg+"</div>");

	setTimeout(function(){
		removeNotification($("div#"+notifId));
	  },7000
	);
}

function isHTML(str) {
    var a = document.createElement('div');
    a.innerHTML = str;
    for (var c = a.childNodes, i = c.length; i--; ) {
        if (c[i].nodeType == 1) return true;
    }
    return false;
}

function errorMessage(message){
	var msg = "Something went wrong";
	try
	{

	   var msg = "";
	   if (message.indexOf('\\') > -1) {
		   msg = JSON.parse(message);
		} else {
			if(typeof message == "string"){
		   		msg = message;
		   } else if(typeof message === 'object'){
		   	msg = JSON.parse(message);
		   }
		}
	}
	catch(e)
	{
	   msg = message;

	   msg = JSON.stringify(msg);
	   msg = msg.replace(/(\r\n|\n|\r)/gm,"");
	}

	var numOfNotif = $(".systemMessages > div").length;
	var notifId    = 'errorBoxId'+(numOfNotif+1);
	if(msg != ""){
		$(".systemMessages")
			.show()
			.append("<div class='errorBox' id='"+notifId+"'><a class='hideErrorMessageButton' href='JavaScript:void(0)' onClick='removeNotification($(this).parent());'><i class='fa fa-times'></i></a>"+msg+"</div>");

		if(!debug){
			setTimeout(function(){
				removeNotification($("div#"+notifId));
			  },7000
			);
		}
	}
}

function removeNotification(notification){
	var notifHeight = (-(notification.outerHeight()+10))+'px';

	$(notification).animate({
	    left: '400px',
	  },
	  'fast', 'linear');

	setTimeout(function(){
		$(notification).animate({
	  		marginTop: notifHeight
	  	},
	  	'fast', 'linear',
	  	function(){

	  		setTimeout(function(){
		  		$(notification).hide();
		  	},200);
	  	});
	},1000);
}


function callWidget(wid,pageSize,userId,object,arrayData){
	showTopLoader();
	if(object !== undefined && object !== null){
		//loadingIcon = showLoadWithElement(object);
		triggerLoading(object);
	}
    return $.ajax({
        url :widgetsList[wid],
        type: "POST",
        data: {
            pageSize:pageSize,
            userId:userId,
            arrayData:JSON.stringify(arrayData),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success:function(data, textStatus, jqXHR)
        {
        	$("#"+wid).html(data);
        	hideTopLoader();

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	errorMessage(jqXHR.responseText);
        	hideTopLoader();
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        		hideTopLoader();
	        	}else {
	        		hideTopLoader();
	        	}

	        }
	    }
    });
}

function callWidgetExternal(wid,pageSize,userId,object,arrayData){
	showTopLoader();
	if(object !== undefined && object !== null){
		//loadingIcon = showLoadWithElement(object);
		//triggerLoading(object);
	}
    $.ajax(
    {
        url :widgetsList[wid],
        type: "POST",
        data: { pageSize:pageSize,userId:userId,arrayData:JSON.stringify(arrayData)  },
        success:function(data, textStatus, jqXHR)
        {
        	$("#"+wid).html(data);
        	hideTopLoader();

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {

	        	}

	        }
	    }
    });
}

function randomId(length) {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');

    if (! length) {
        length = Math.floor(Math.random() * chars.length);
    }

    var str = '';
    for (var i = 0; i < length; i++) {
        str += chars[Math.floor(Math.random() * chars.length)];
    }
    return str;
}

function refreshWidgets(){
	for (var i = 0; i < widgetsToReload.length; i++) {
	    callWidget(widgetsToReload[i]);
	}
	widgetsToReload.splice(0,widgetsToReload.length);
}

function refreshWidgetsExternal(userId){

	for (var i = 0; i < widgetsToReload.length; i++) {
	    callWidgetExternal(widgetsToReload[i],null,userId);
	}
	widgetsToReload.splice(0,widgetsToReload.length);
}

$(document).ready(function(){


	// //DATEPICKER
	// $(function(datepicker) {
	// 	$( ".datepicker" ).datepicker({
	// 		changeYear: true,
	// 		dateFormat: 'yy-mm-dd',
	// 		yearRange: '1920:2019',
	// 	});
	//
	// });
	// //DATEPICKER
	// $(function(datepicker) {
	// 	$( ".datepickerToday" ).datepicker({
	// 		changeYear: true,
	// 		dateFormat: 'yy-mm-dd',
	// 		yearRange: '1920:2019',
	// 		minDate:0
	// 	});
	//
	// });
	//
	// //DATEPICKER
	// $(function(datepicker) {
	// 	$( ".datepickerPast" ).datepicker({
	// 		changeYear: true,
	// 		dateFormat: 'yy-mm-dd',
	// 		yearRange: '1920:2019',
	// 		maxDate:0
	// 	});
	//
	// });

	// //TIMEPICKER
	// $('.timepicker').timepicker();



	//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSave",function(event){
		//alert(1);

		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function()
			        {
                                showTopLoader();
                    },
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideTopLoader();
			        	successMessage(data);
			        	upAndClearAdd();

			        	if(widget !== undefined) widgetsToReload.push(widget);
			        	refreshWidgets();
			        	return false;

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideTopLoader();
			        	errorMessage(jqXHR.responseText);
			        	return false;
			        },
			        statusCode: {
				        500: function(jqXHR) {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        		hideTopLoader();
				        	}

				        }
				    }
			    });

			}
		);
	});

	$("body").on("click",".ajaxSaveExternal",function(event){
		//alert(1);

		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		var userId = tForm.find("input[name=userId]").val();
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function()
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preLoad);
			        	successMessage(data);
			        	upAndClearAdd();
			        	widgetsToReload.push(widget);
			        	refreshWidgetsExternal(userId);
			        	return false;

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideLoadWithElement(preLoad);
			        	errorMessage(jqXHR.responseText);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        	}

				        }
				    }
			    });

			}
		);
	});

	//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSaveFancyBox",function(event){
		//alert(1);

		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    var preload;
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function()
			        {
                                preLoad = showLoadWithElement(handler);
                    },
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preLoad);
			        	successMessage(data);
			        	widgetsToReload.push("w_tasks");
			        	widgetsToReload.push("w_appointments");
			        	widgetsToReload.push("w_calendar_full");
			        	refreshWidgets();
			        	parent.jQuery.fancybox.close();
			        	return false;

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	errorMessage(jqXHR.responseText);
			        	hideLoadWithElement(preLoad);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        	}

				        }
				    }
			    });

			}
		);
	});

//ALL AJAX FORMS SAVE
	$("body").on("click",".ajaxSaveSubmit",function(event){
		//alert(1);

		var handler = $(this);
		tForm = $(this).closest("form");
		widget = $(this).attr("widget");
		tForm.submit(function(e)
			{
				e.preventDefault(); //STOP default action
				e.stopImmediatePropagation();
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
				    processData: false,
				    contentType: false,
			        beforeSend:function()
			        {
			        	showTopLoader();
                    },
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideTopLoader();
			        	successMessage(data);
			        	return false;

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideTopLoader();
			        	errorMessage(jqXHR.responseText);
			        	return false;
			        },
			        statusCode: {
				        500: function() {
				        	if(jqXHR.responseText != ""){
				        		errorMessage(jqXHR.responseText);
				        	}

				        }
				    }
			    });

			}
		);
	});

	$(window).scroll(function(){
	  var sticky = $('.headertop'),
	      scroll = $(window).scrollTop();

	  if (scroll >= 100) {
	  	sticky.addClass('fixed');
	  	$(".profiletitle").hide();
	  	$(".oneLineMenu").show();
	  } else {
	  	sticky.removeClass('fixed');
	  	$(".oneLineMenu").hide();
	  	$(".profiletitle").show();
	}
	});

});

function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
    s4() + '-' + s4() + s4() + s4();
}

function showLoadWithElement(el, imageWidth, position){

    var newElement = el.clone();
    var uuid = guid();
    var newImage = getLoadImage(imageWidth,uuid, position, el);
    el.replaceWith(newImage);
    var elements = {
        element : newElement,
        uuid : uuid
    }
    return elements;
}
function hideLoadWithElement(elements){
     $("#"+elements.uuid).replaceWith(elements.element);
}

function restoreLoader(elements){
    $("#"+elements.uuid).replaceWith(elements.element);
}

function getLoadImage(width,uuid, position, el){
	if (width === null || width === undefined) width = 23;
    var itemP = $('<p />', { id: uuid, style: 'display: block; margin:auto; padding: 0; padding-top: 5px; height: auto; width: ' + el.css('width') +'; text-align: center;' });
    if (position == 'center'){
        itemP.append(imgLoad.clone());
        element = itemP;
    } else if (position == 'right'){
        element = imgLoad.clone();
        element.css({'float':'right'});
        element.attr("id",uuid);
    } else{
        element = imgLoad.clone();
        element.attr("id",uuid);
    }


    if (width > 0)
        element.find('img').css({width:width+'px'});

    return element;
}

function callNotifications(numero){

	$.ajax(
    {
        url :"/widgets/notifications",
        type: "POST",
        data: { pageSize:numero },
        success:function(data, textStatus, jqXHR)
        {
        	var response = $.parseJSON(data);
        	if(response.total > 0){
        		$("#icon_notification").css("background-color","rgb(248,148,6)");
        		$("#notificationsNumber").css("color","#FFF");
        	} else{
        		$("#icon_notification").css("background-color","rgb(255,255,255)");
        		$("#notificationsNumber").css("color","#424A7A");
        	}
        	$("#notificationsNumber").text(response.total);
        	$("#notification_preloader").html(response.view);
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	// Display 0 in case of error:
        	$("#icon_notification").css("background-color","rgb(255,255,255)");
        	$("#notificationsNumber").css("color","#424A7A");
        	$("#notificationsNumber").text("0");
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {

	        	}

	        }
	    }
    });
}



function showLastNotifications(){

	$("#notification_preloader").slideDown();
	$.ajax(
    {
        url :"/widgets/notificationsRead",
        type: "POST",
        success:function(data, textStatus, jqXHR)
        {
        	bindNotificationsToCallback();
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {

	        	}

	        }
	    }
    });
}


function callMessages(numero){
	$.ajax(
    {
        url :"/widgets/messages",
        type: "POST",
        data: { pageSize:numero },
        success:function(data, textStatus, jqXHR)
        {
        	var response = $.parseJSON(data);
        	if(response.total > 0){
        		$("#messagesNumber").show();
        		$("#messagesNumber").text(response.total);
        		$("#notification_preloader").html(response.view);
        	} else {
        		$("#messagesNumber").hide();
        	}
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
        	errorMessage(jqXHR.responseText);
        },
        statusCode: {
	        500: function() {
	        	if(jqXHR.responseText != ""){
	        		errorMessage(jqXHR.responseText);
	        	}else {

	        	}

	        }
	    }
    });
}

$(".heavy").on('submit', function(e) {
    handler = $(this);
    button = handler.find(':submit');
    preLoad = showLoadWithElement(button);
});

function heavy(obj){
		var handler = $(obj);
		preLoad = showLoadWithElement(handler);
}

function handleNotification(url,target){
	if(target=='self'){
		$.ajax(
		    {
		        url : url,
		        type: "GET",
		        success:function(data, textStatus, jqXHR)
		        {
		        	successMessage(data);
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	errorMessage(jqXHR.responseText);
		        },
		        statusCode: {
			        500: function() {
			        	if(jqXHR.responseText != ""){
			        		errorMessage(jqXHR.responseText);
			        	}else {

			        	}

			        }
			    }
		    });
	} else {
		window.location = url;
	}
}

function bindNotificationsToCallback(){
	$("body").on("click",".notifyCallBack",function(e){
		var handler = $(this);
		var preload;
		e.preventDefault(); //STOP default action
		e.stopImmediatePropagation();
		$.ajax(
		    {
		        url : $(this).attr("href"),
		        type: "GET",
		        beforeSend:function()
		        {
                    preLoad = showLoadWithElement(handler);
                },
		        success:function(data, textStatus, jqXHR)
		        {
		        	hideLoadWithElement(preLoad);
		        	successMessage(data);
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	hideLoadWithElement(preLoad);
		        	errorMessage(jqXHR.responseText);
		        },

		    });
	});
}


function showLastMessages(){
	$(".notificationdropdown").show();
	$("#notification_preloader").slideDown();
}

function available(date) {

    dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();

    if ($.inArray(dmy, availableDates) != -1) {
        return [true, "", "Available"];
    } else {
        return [false, "", "unAvailable"];
    }
}


function shareOnFacebook(link,type,id){
	$.ajax(
		    {
		        url : "/Share/Facebook",
		        type: "POST",
		        data: {link:link,type:type,id:id},
		        success:function(data, textStatus, jqXHR)
		        {
		        	successMessage(data);
		        },
		        error: function(jqXHR, textStatus, errorThrown)
		        {
		        	errorMessage(jqXHR.responseText);
		        },

		    });
}

function refreshImages(imgToRefresh){
	var d = new Date();
	$("."+imgToRefresh).each(function(i, obj) {
    	$(obj).attr("src", $(obj).attr("src")+"?"+d.getTime());
	});
}

function selectPrevious(object){
	$(object).prev().focus();
}


$(document).ready(function(){
	$(window).scrollTop($(window)[0].scrollHeight);

    $(window).scroll(function(){
        if ($(window).scrollTop() == 0){
            $("#blur_overlay").hide();
        }else{
			$("#blur_overlay").show();
        }
    });

});

function postMessageOnboarding(message){
	$.ajax(
            {
                url : "/onboarding/message/"+message,
                type: "POST",

                success:function(data, textStatus, jqXHR)
                {

                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    errorMessage(jqXHR.responseText);
                },
            });
}


function sendMessageToUser(toUserId,userType){

	if(userType == null) userType = "Trainer";
    $("#arrowchat_buddy_list_tab").click();
	setTimeout(function(){
		$("#arrowchat_userlist_"+toUserId).click();
	},1000);
}

var typewatchGlobal = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

/*
 *  Validation Helpers
 */
function isNameValid(name){
  return name.trim() != '';
}
function showErrorField(element, error_message){
	if(element)
  		$(element).addClass("field_error");
  	errorMessage(error_message);
}

function hideErrorField(element){
  $(element).removeClass("field_error");
}

Array.prototype.clean = function(deleteValue) {
  for (var i = 0; i < this.length; i++) {
    if (this[i] == deleteValue) {
      this.splice(i, 1);
      i--;
    }
  }
  return this;
};


function callForEvent(eventName,arrayOfMetas){
	$.ajax(
    {
        url : "/events/postEvent",
        type: "POST",
        data: {eventName: eventName, metas: JSON.stringify(arrayOfMetas)},
        success:function(data, textStatus, jqXHR)
        {

        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            errorMessage(jqXHR.responseText);
        },
    });


}

function triggerLoading(target){

	target.attr("loadingCounter",loadingCounter);
	loadingDiv = target.clone();
	html =  '<div class="deleting loading loading'+loadingCounter+'">'+
                '<div class="loadingSignIcon"><img src="/assets/img/tw-gif.gif"> </div>'+
                '<div class="deletingSign">'+dict["loading"]+'...</div>'+
           '</div>';
	target.replaceWith(html);
}



/* Overlays loading */
var $overlay = $("<div id='lightBoxLoading'></div>");
var $blackSpinner = $('<img src="/assets/img/loading-spinner_.gif">');
var $TWloading = $('<img src="/assets/img/tw-gif.gif">');


/*  Backyup ONLY  **/
function lightBoxLoadingBlackSpinner () {
	$overlay.append($blackSpinner);
	$("body").append($overlay);
	$overlay.show();
}

/* Main Loader  */
function lightBoxLoadingTwSpinner () {
	$overlay.append($TWloading);
	$("body").append($overlay);
	$overlay.show();
}

/* Close Overlay */
function closeLoadingOverlay() {
	$overlay.hide();
}

/* ends overlays */


/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 * jQuery.browser.mobile will be true if the browser is a mobile device
 **/



function triggerAjaxFileDownload(url){
	lightBoxLoadingTwSpinner();
	var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.responseType = 'arraybuffer';
        xhr.onload = function () {
            if (this.status === 200) {
                var filename = "";
                var disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('attachment') !== -1) {
                    var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                    var matches = filenameRegex.exec(disposition);
                    if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                }
                var type = xhr.getResponseHeader('Content-Type');

                var blob = new Blob([this.response], { type: type });
                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                    // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                    window.navigator.msSaveBlob(blob, filename);
                } else {
                    var URL = window.URL || window.webkitURL;
                    var downloadUrl = URL.createObjectURL(blob);

                    if (filename) {
                        // use HTML5 a[download] attribute to specify filename
                        var a = document.createElement("a");
                        // safari doesn't support this yet
                        if (typeof a.download === 'undefined') {
                            window.location = downloadUrl;
                             closeLoadingOverlay();
                        } else {
                            a.href = downloadUrl;
                            a.download = filename;
                            document.body.appendChild(a);
                            a.click();
                             closeLoadingOverlay();
                        }
                    } else {
                        window.location = downloadUrl;
                         closeLoadingOverlay();
                    }

                    setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                }
            }
        };
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send();
}


function showTopLoader() {
	$(".loader-bg").addClass("moveMe");
}


function hideTopLoader() {
	$(".loader-bg").removeClass("moveMe");
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function convertTo(value,unit){

	var tempValue = 0;
	if(value > 0 && value != "" && isNumeric(value)){
		if(unit == "Metric" || unit == "Kg"){
			tempValue = (value / 2.2).toFixed(1);
		} else{
			tempValue = (value*2.2).toFixed(1);
		}
	}

	return tempValue;

}

function convertToSpeed(value,unit){

	var tempValue = 0;
	if(value > 0 && value != "" && isNumeric(value)){
		if(unit == "Metric" || unit == "Kg"){
			tempValue = (value * 1.609344).toFixed(1);
		} else{
			tempValue = (value/1.609344).toFixed(1);
		}
	}

	return tempValue;

}

function convertToDistance(value,unit){

	var tempValue = 0;
	if(value > 0 && value != "" && isNumeric(value)){
		if(unit == "Metric" || unit == "Kg"){
			tempValue = (value * 1.609344).toFixed(2);
		} else{
			tempValue = (value/1.609344).toFixed(2);
		}
	}

	return tempValue;

}

function setToUnits(value,unit){

	var tempValue = 0;
	if(value > 0 && value != "" && isNumeric(value)){
		if(unit == "Metric" || unit == "Kg"){
			tempValue = (value / 2.2).toFixed(1);
		} else{
			tempValue = (value*2.2).toFixed(1);
		}
	}

	return tempValue;

}

function convertToUnit(value,unit){
	if(unit == "Kg" || unit == "Metric"){
		value = dict["Kg"];
	} else {
		value = dict["Lbs"];
	}

	return value;
}


function convertToUnitSpeed(value,unit){
	if(unit == "km/h" || unit == "Metric"){
		value = dict["km/h"];
	} else {
		value = dict["mi/h"];
	}

	return value;
}


function convertToUnitDistance(value,unit){
	if(unit == "km" || unit == "Metric"){
		value = dict["km"];
	} else {
		value = dict["mi"];
	}

	return value;
}


function showOverlay() {
  $(".overlayKillParent").addClass("overlayKillParent-active");
  $(".overlayKillChild").click(function() {
    $(".overlayKillParent").removeClass("overlayKillParent-active");
  })
}








