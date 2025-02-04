/*!
 * Start Bootstrap - Agency Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top-90
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

// Highlight the top nav as scrolling occurs
$('body').scrollspy({
    target: '.navbar-fixed-top',
    offset: 106
})

// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});

$(document).ready(function() {

	var win_width = $( window ).width();
	adjustComputerSliders(win_width);
	adjustWITFSlidersWidth();
	if(window.innerWidth > 801){
		hideWITFSliders();
	}

});

$(window).resize(function(){
	var win_width = $( window ).width();
	adjustComputerSliders(win_width);
	if(window.innerWidth > 801){
		adjustWITFSlidersWidth();
	}
});	

function adjustComputerSliders(width){
	if(width < 1350){
		$("#slider_container").css("right","");
		$("#slider_container").css("left","192px");		
	} else {
		$("#slider_container").css("left","")
		$("#slider_container").css("right","58%")
	}
}

function showComputerSliders(){
	var listItems = $("#sliders li");
	listItems.each(function(i, li) {
		setTimeout(function(){
	       	var slider = $(li);
		    slider.animate({
			   marginLeft: "0"
			},500, "linear");
	    },100 + ( i * 100 ));
	});
}

function adjustWITFSlidersWidth(){
	var listItems = $("#witf_sliders li");
	listItems.each(function(i, li) {
		$(li).css("width","auto");
		var width= $(li).width();
		$(li).css("width",width+"px");
	});
}

function hideWITFSliders(){
	var listItems = $("#witf_sliders li");
	listItems.each(function(i, li) {
		$(li).css("margin-left","-150%");
	});
}

function showSlider(slider){
	slider.animate({
	   marginLeft: "0"
	},500, "linear");
}

$(window).on('scroll', function() {
    var pageBottom = window.pageYOffset+window.innerHeight;
    var triggerWITFSlider = 300;             // set to whatever you want it to be

    if(window.innerWidth > 801){
    	if(pageBottom > $("#witf_sliders #1").offset().top+200){    	showSlider($("#witf_sliders #1"));    }
	    if(pageBottom > $("#witf_sliders #2").offset().top+200){    	showSlider($("#witf_sliders #2"));    }
	    if(pageBottom > $("#witf_sliders #3").offset().top+200){    	showSlider($("#witf_sliders #3"));    }
    }
    if(pageBottom > $("#sliders").offset().top+100) 		   {		showComputerSliders()				  }
});





//   background-image: linear-gradient(-180deg, #424A79 5%, #38ACFF 91%);