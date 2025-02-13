// JavaScript Document

//GLOBAL VARIABLES

var arrayDataTables = {};
var imgLoad = $('<img />', { src : '/img/ajax-loader.gif' });
var loadingIcon = null;

function toggle(id,idToClear){
	if(idToClear != undefined && idToClear != null){
		$("#"+idToClear).val("");
	}
	$("#"+id).slideToggle();
}

function toggleAndClear(id,idToClear){
	if(idToClear != undefined && idToClear != null){
		$("#"+idToClear).val("");
	}
	clear(id);
	$("#"+id).slideToggle();
}

function down(id){
	$("#"+id).slideDown();
}

function up(id){
	$("#"+id).slideUp();
}

function image(image,width){

	w = 200;

	if(width != null) w = width;
	if(image != null && checkUrl("/"+image)){

		return "<img width='"+w+"' src=/"+image+">"
	} else {
		return "";
	}
}

function echoColorIfDatePast(data){
	if(data != ""){
		var dates = data.split(" ");
		var from = dates[0].split("-");
		var startDate = new Date(from[0], from[1] - 1, from[2]);
		var today = new Date();
		if(startDate < today){
			return "<span class='expiredDate'>"+data+"</span>";
		} else {
			return data;
		}
	}
}

function checkUrl(url) {
        var request = false;
        if (window.XMLHttpRequest) {
                request = new XMLHttpRequest;
        } else if (window.ActiveXObject) {
                request = new ActiveXObject("Microsoft.XMLHttp");
        }

        if (request) {
                request.open("GET", url);
                if (request.status == 200) { return true; }
        }

        return false;
}


function imageRotate(image,id,width){
	w = 200;

	if(width != null) w = width;
	if(image != null){

		return '<div class="showRotate"><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateLeft('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-undo"></i></a><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateRight('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2"><i class="fa fa-repeat"></i></a>'+"<a href='/"+image.replace("_thumb","")+"' target='_blank'><img width='"+w+"' src=/"+image+"></a>"
	} else {
		return "";
	}
}

function imageRotate1(image,id,width){
	w = 200;

	if(width != null) w = width;
	if(image != null){

		return '<div class="showRotate"><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateLeft1('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-undo"></i></a><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateRight1('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2"><i class="fa fa-repeat"></i></a>'+"<a href='/"+image.replace("_thumb","")+"' target='_blank'><img width='"+w+"' src=/"+image+"></a>"
	} else {
		return "";
	}
}

function imageRotate1Switch(image,id,width){
	w = 200;

	if(width != null) w = width;
	if(image != null){

		return '<div class="showRotate"><a style="margin-right:2px" href="javascript:void(0)" onClick="switchPictures('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-refresh"></i></a><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateLeft1('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-undo"></i></a><a href="javascript:void(0)" onClick="rotateRight1('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2"><i class="fa fa-repeat"></i></a>'+"<a href='/"+image.replace("_thumb","")+"' target='_blank'><img width='"+w+"' src=/"+image+"></a>"
	} else {
		return "";
	}
}


function imageRotate2(image,id,width){
	w = 200;

	if(width != null) w = width;
	if(image != null){

		return '<div class="showRotate"><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateLeft2('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-undo"></i></a><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateRight2('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2"><i class="fa fa-repeat"></i></a>'+"<a href='/"+image.replace("_thumb","")+"' target='_blank'><img width='"+w+"' src=/"+image+"></a>"
	} else {
		return "";
	}
}

function imageRotate2Switch(image,id,width){
	w = 200;

	if(width != null) w = width;
	if(image != null){

		return '<div class="showRotate"><a style="margin-right:2px" href="javascript:void(0)" onClick="switchPictures('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-refresh"></i></a><a style="margin-right:2px" href="javascript:void(0)" onClick="rotateLeft2('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver"><i class="fa fa-undo"></i></a><a href="javascript:void(0)" onClick="rotateRight2('+id+',$(this)); arguments[0].stopPropagation(); return false;" class="showRotateReceiver2"><i class="fa fa-repeat"></i></a>'+"<a href='/"+image.replace("_thumb","")+"' target='_blank'><img width='"+w+"' src=/"+image+"></a>"
	} else {
		return "";
	}
}






function upAndClearAdd(){

	$(".add").each(function(i, obje) {
    	if(!$(obje).hasClass("noErase")) {
	    	$(obje).find('input:checkbox').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).attr('checked', false);
	    	});
	    	$(obje).find('input:text').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
	    	$(obje).find('input:hidden').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
    	}
    	$(".chosen-select").trigger("chosen:updated");
    	//$(obj).find('form')[0].reset();

    	for ( instance in CKEDITOR.instances ){
	    	CKEDITOR.instances[instance].setData('');
	        CKEDITOR.instances[instance].updateElement();
	    }
	});



}

function refreshImages(divIdentifier){
	var d = new Date();
	$(divIdentifier+" img").each(function(i, obj) {
    	$(obj).attr("src", $(obj).attr("src")+"?"+d.getTime());
	});
}

function upAndClearAddImage(){
	$(".add").each(function(i, obje) {
    	if(!$(obje).hasClass("noErase")) {
	    	$(obje).find('input:checkbox').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).attr('checked', false);
	    	});
	    	$(obje).find('input:text').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
	    	$(obje).find('input:hidden').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
    	}
    	$(".chosen-select").trigger("chosen:updated");
    	$(obj).find('form')[0].reset();

    	for ( instance in CKEDITOR.instances ){
	    	CKEDITOR.instances[instance].setData('');
	        CKEDITOR.instances[instance].updateElement();
	    }
	});

}

function clearAdd(){
	$(".add").each(function(i, obj) {
		if(!$(obj).hasClass("noErase")) $(obj).find('input:text').val('');
	});
}

function clear(obj){

    $(".add").each(function(i, obje) {
    	if(!$(obj).hasClass("noErase")) {
	    	$(obje).find('input:checkbox').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).attr('checked', false);
	    	});
	    	$(obje).find('input:text').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
	    	$(obje).find('input:hidden').each(function(t,o){
	    		if(!$(o).hasClass("noErase")) $(o).val('');
	    	});
    	}
	});

}

function clearAddScopeForm(obj){
		$(obj).each(function(i, obj) {
			if(!$(obj).hasClass("noErase")) $(obj).find('input:text').val('');
			if(!$(obj).hasClass("noErase")) $(obj).find('input:hidden').val('');
		});
}


function refreshAllDataTables(){
	for (var key in arrayDataTables) {
		arrayDataTables[key].api().ajax.reload();;
	}
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

function showLoadWithElement(el, imageWidth, position){

    var newElement = el.clone();
    var newImage = getLoadImage(imageWidth, position, el);
    el.replaceWith(newImage);
    var elements = {
        element : newElement,
        image : newImage
    }
    return elements;
}
function hideLoadWithElement(elements){
    elements['image'].replaceWith(elements['element']);
}

function getLoadImage(width, position, el){

    var itemP = $('<p />', { id: 'p-loading', style: 'display: inline-block; padding: 0; height: auto; width: ' + el.css('width') +'; text-align: center;' });
    if (position == 'center'){
        itemP.append(imgLoad.clone());
        element = itemP;
    } else if (position == 'right'){
        element = imgLoad.clone();
        element.css({'float':'right'});
    } else
        element = imgLoad.clone();
    if (width > 0)
        element.css({width:width+'px'});

    return element;
}

function loader(button){
	 var loader = {button: button, contenido:"", cell: null};
     var cell = $(button).closest("td");
     var contents = cell.html();
     loader.contenido = contents;
     loader.cell = cell;
     cell.html('<i class="fa fa-spinner fa-spin"></i>');
     return loader;
}

function restoreLoader(loader){
    loader.cell.html(loader.contenido);
    return true;
}

//Print for datatables show button.
function echoPlacer(id,content){
	var output = "";
		output = '<td class="center"><button class="btn btn-warning btn-circle" type="button" onclick="placeDiv('+id+',\''+content+'\')"><i class="fa fa-arrows"></i></button></td>';
	return output;
}

function echoFilterNullRelationshipTail(data){
	if(data == null || data === undefined){
		return "";
	} else {
		return data.TailNumber;
	}
}

function echoBooleanFunction(value,options){
	var arrayOptions = options.split(",");
	if(value == 1){
		return arrayOptions[0];
	} else {
		return arrayOptions[1];
	}
}

function echoDynamicFunctionTail(value,valueToCompare,options){

	var arrayOptions = options.split(",");
	if(value == null || value === undefined){
		return arrayOptions[1];
	} else {
		//alert(value);
		if(value == valueToCompare){
			return arrayOptions[1];
		} else {
			return arrayOptions[0];
		}
	}


}

//Print for datatables show button.
function echoEdit(id,functionName){
	window.scrollTo(0, 0);
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button" onclick="edit('+id+')"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}

function echoLoginUser(data){
	output = '<a href="/ControlPanel/Users/loginUserAdmin/'+data+' class="btn btn-primary btn-circle"><i class="fa fa-list"></i></a>';
	return output;
}

//Print for datatables show button.
function echoRemove(id,functionName,permissions){
		var output = "";

		if(permissions !== undefined){

			if(permissions !== false){
					alert(1);
				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle buttonRemove" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-times"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle buttonRemove" type="button" onclick="del('+id+')"><i class="fa fa-times"></i></button></td>'
				}
			}
		} else {

				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle buttonRemove" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-times"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle buttonRemove" type="button" onclick="del('+id+')"><i class="fa fa-times"></i></button></td>'
				}


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
function echoClone(id,functionName){
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle buttonClone" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-external-link"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle buttonClone" type="button" onclick="clone('+id+')"><i class="fa fa-external-link"></i></button></td>'
		}
		return output;
}


//Print for datatables show button.
function echoLink(id,functionName){
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-success btn-circle buttonLink" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-link"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-success btn-circle buttonLink" type="button" onclick="link('+id+')"><i class="fa fa-link"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoCloneString(id,functionName,permissions){
	window.scrollTo(0, 0);

		var output = "";
		if(permissions !== undefined){
			if(permissions !== false){
				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="'+functionName+'(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="clone(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
				}
			}
		} else {

				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="'+functionName+'(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="clone(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
				}


		}
		return output;
}

//Print for datatables show button.
function echoLinkString(id,functionName,permissions){
	window.scrollTo(0, 0);

	var output = "";
		if(permissions !== undefined){
			if(permissions !== false){
				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-success btn-circle" type="button" onclick="'+functionName+'(\''+id+'\')"><i class="fa fa-link"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-success btn-circle" type="button" onclick="link(\''+id+'\')"><i class="fa fa-link"></i></button></td>'
				}
			}
		} else {

				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-success btn-circle" type="button" onclick="'+functionName+'(\''+id+'\')"><i class="fa fa-link"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-success btn-circle" type="button" onclick="link(\''+id+'\')"><i class="fa fa-link"></i></button></td>'
				}


		}
		return output;

}

//Print for datatables show button.

function echoProperties(id,functionName){
	window.scrollTo(0, 0);
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="'+functionName+'('+id+')"><i class="fa fa-external-link"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="showProperties('+id+')"><i class="fa fa-external-link"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoPropertiesString(id,functionName){
	window.scrollTo(0, 0);
		var output = "";
		if(functionName !== undefined){
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="'+functionName+'(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
		} else {
			output = '<td class="center removeIcon"><button class="btn btn-warning btn-circle" type="button" onclick="showProperties(\''+id+'\')"><i class="fa fa-external-link"></i></button></td>'
		}
		return output;
}

//Print for datatables show button.
function echoCustomFunction(id,functionName,buttonText){
	window.scrollTo(0, 0);
	var output = "";
	output = '<td class="center"><button class="btn btn-primary" type="button" onclick="'+functionName+'(\''+id+'\',$(this))">'+buttonText+'</button></td>';
	return output;
}

//Print for datatables show button.
function echoEditString(id,functionName){
	window.scrollTo(0, 0);
	var output = "";
	if(functionName !== undefined){
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button  buttonRemove" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	} else {
		output = '<td class="center"><button class="btn btn-primary btn-circle" type="button  buttonRemove" onclick="edit(\''+id+'\',$(this))"><i class="fa fa-list"></i></button></td>';
	}
	return output;
}
//Print for datatables show button.
function echoRemoveString(id,functionName,permissions){
	var output = "";
	var output = "";

		if(permissions !== undefined){

			if(permissions !== false){

				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
				}
			}
		} else {
				//alert(functionName);
				if(functionName !== undefined){
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="'+functionName+'(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
				} else {
					output = '<td class="center removeIcon"><button class="btn btn-danger btn-circle" type="button" onclick="del(\''+id+'\',$(this))"><i class="fa fa-times"></i></button></td>'
				}


		}
		return output;
}

function successMessage(msg){
	var numOfNotif = $(".systemMessages > div").length;
	var notifId    = 'successBoxId'+(numOfNotif+1);

	$(".systemMessages")
		.show()
		.append("<div class='successBox' id="+notifId+">"+msg+"</div>");

	setTimeout(function(){
		removeNotification($("div#"+notifId));
	  },7000
	);
}

function errorMessage(msg){
	var numOfNotif = $(".systemMessages > div").length;
	var notifId    = 'successBoxId'+(numOfNotif+1);

	$(".systemMessages")
	.show()
	.append("<div class='errorBox' id='"+notifId+"'><a class='hideErrorMessageButton' href='JavaScript:void(0)' onClick='removeNotification($(this).parent());'><i class='fa fa-times'></i></a>"+msg+"</div>");


	// $(".systemMessages")
	// 	.show()
	// 	.append("<div class='errorBox' id='"+notifId+"'>"+msg+"</div>");

	// setTimeout(function(){
	// 	removeNotification($("div#"+notifId));
	//   },10000
	// );
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


function translateCNOType(value){
	if(value == 3){
		return "Type 1";
	}

	if(value == 5){
		return "Type 2";
	}

	if(value == 10){
		return "Type 3";
	}

	if(value == 1){
		return "New Dev";
	}

	return "Old Type";
}


function msieversion() {
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	if (msie > 0)      // If Internet Explorer, return version number
		errorMessage("Your current browser Internet Explorer version "+parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)))+" it is not supported. Please switch to Google Chrome or Mozilla Firefox.");
	return false;
}

function filterUIPermissions(permissions){

	if(permissions === undefined) permissions = "read";

	if(permissions == "write"){

	} else {
		$(".addButton").hide();
		$(".buttonRemove").hide();
		$(".insertPermissions").hide();
	}
}
$(document).ready(function(){
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd'
	});
});


$(document).ready(function(){
	$(".ajaxSave").click(function(){
		tForm = $(this).closest("form");
		var handler = $(this);
		tForm.submit(function(e)
			{
				var preload = showLoadWithElement(handler);
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
					processData: false,
					contentType: false,
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preload);
			        	successMessage(data);
			        	upAndClearAdd();
			        	refreshAllDataTables();

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideLoadWithElement(preload);
			        	errorMessage(jqXHR.responseText +" "+errorThrown);
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
			    e.preventDefault(); //STOP default action
			}
		);
		$(this).unbind();
	});

	$(".ajaxSaveImage").click(function(){
		tForm = $(this).closest("form");
		var handler = $(this);

		tForm.submit(function(e)
			{

				var preload = showLoadWithElement(handler);
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
					processData: false,
					contentType: false,
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preload);
			        	successMessage(data);
			        	upAndClearAddImage();
			        	refreshAllDataTables(); //Corinne Fix

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideLoadWithElement(preload);
			        	errorMessage(jqXHR.responseText +" "+errorThrown);
			        },

			    });
			    e.preventDefault(); //STOP default action
			}
		);
		$(this).unbind();
	});

	$(".ajaxSaveNoCollapse").click(function(){
		tForm = $(this).closest("form");
		var handler = $(this);
		tForm.submit(function(e)
			{
				var preload = showLoadWithElement(handler);
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
					processData: false,
					contentType: false,
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preload);
			        	successMessage(data);
			        	clearAddScopeForm(tForm);
			        	refreshAllDataTables();

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideLoadWithElement(preload);
			        	errorMessage(jqXHR.responseText +" "+errorThrown);
			        },

			    });
			    e.preventDefault(); //STOP default action
			}
		);
		$(this).unbind();
	});

	$(".ajaxSaveNoCollapseBlock").click(function(){
		//alert($(this).attr("am"));
		var AM = $(this).attr("am");
		tForm = $(this).closest("form");
		var handler = $(this);
		tForm.submit(function(e)
			{
				var preload = showLoadWithElement(handler);
			    //var postData = $(this).serializeArray();
			    var formURL = $(this).attr("action");
			    $.ajax(
			    {
			        url : formURL,
			        type: "POST",
			        data: new FormData( this ),
					processData: false,
					contentType: false,
			        success:function(data, textStatus, jqXHR)
			        {
			        	hideLoadWithElement(preload);
			        	successMessage(data);
			        	clearAddScopeForm(tForm);
			        	refreshAllDataTables();
			        	$("input[name=AM_Id]").val(AM);
			        	refreshBlock(AM);
			        	refreshBlockSmall(AM);

			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	hideLoadWithElement(preload);
			        	errorMessage(jqXHR.responseText +" "+errorThrown);
			        },

			    });
			    e.preventDefault(); //STOP default action
			}
		);
		$(this).unbind();
	});

});


$(function() {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});


function showNames(data){
	var output = "";
	var array = [];
	if(data != null){

    for(var x = 0; x < data.length; x++){
    		if(data[x].equipments != null){
	            array[x] = data[x].equipments.name;
    		}

        }
    }

    return array.join(", ");
}

function showNamesB(data){
	var output = "";
	var array = [];
	if(data != null){

    for(var x = 0; x < data.length; x++){
    		if(data[x].bodygroup != null){
	            array[x] = data[x].bodygroup.name;
    		}

        }
    }

    return array.join(", ");
}

function showNamesT(data){
	var output = "";
	var array = [];
	if(data != null){

    for(var x = 0; x < data.length; x++){
    		if(data[x].exercisestypes != null){
	            array[x] = data[x].exercisestypes.name;
    		}

        }
    }

    return array.join(", ");
}



var $loaderBG = $("<div id='loader-bg'><img src='assets/img/tw-gif.gif'></div>");


function showTopLoader() {
	$("body").append($loaderBG);
}


function hideTopLoader() {
	$loaderBG.remove();
}
