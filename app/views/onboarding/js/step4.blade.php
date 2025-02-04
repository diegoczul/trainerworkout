

<script>
var speed = 200;
var testimonialTrigger = true;
var workoutTrigger = true;
var bubble1 = '<span class="dot" id="dot1"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble13") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble14") }}}</div></div><span class="arrow"></span></span>';

var bubble2 = '<span class="dot" id="dot2"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble11") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble12") }}}</div></div><span class="arrow"></span></span>';

var bubble3 = '<span class="dot" id="dot3"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble15") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble16") }}}</div></div><span class="arrow"></span></span>';

var bubble4 = '<span class="dot" id="dot4"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble17") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble18") }}}</div></div><span class="arrow"></span></span>';

var bubble5 = '<span class="dot" id="dot5"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble19") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble20") }}}</div></div><span class="arrow"></span></span>';

var bubble6 = '<span class="dot" id="dot6"></span>';


$(document).ready(function(){
	$(".createanewworkout").hide();
	//$(".chatbox-container").css({ opacity: 1 });
	//$("#chat1").delay(500).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(9);
	},500);
	

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$(window).scroll(function () {

    if ($(window).height() > 200 && testimonialTrigger) {
    	testimonialTrigger = false;
    	launch();
    } 

    if ($(window).height() > 400 && workoutTrigger) {
    	workoutTrigger = false;
    	launch2();
    } 
});

function launch(){
	$(".chatbox-container").css({ opacity: 1 });
	//$("#chat1").fadeOut(speed);
	// $("#dot1").fadeOut();
	// $("#dot2").fadeOut();
	// $("#dot3").fadeOut();
	setTimeout(function(){ 
			$(".clientworkouts").first().append(bubble1); 
			$(".clientfeed").first().append(bubble2); 
			$(".clientmeasurements").first().append(bubble3); 
			$(".clientweight").first().append(bubble4); 
			$(".clientreminders").first().append(bubble5); 
			$(".inner-dot").css("visibility","visible");  
			$(".arrow").css("visibility","visible");  
			glow();
			initilizeStop();
	},500);

	setTimeout(function(){ 
			$(".inner-dot").css("visibility","hidden"); 
			$(".arrow").css("visibility","hidden"); 
	},10000);
	$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );


}

function launch2(){
	$(".chatbox-container").css({ opacity: 1 });
	//$("#chat1").fadeOut(speed);
	setTimeout(function(){ 
		postMessageOnboarding(9.1);
	},500);
	//setTimeout(function(){ 
	//	postMessageOnboarding(9);
	//},500);
	//$("#chat2").delay(500).fadeIn(speed);
	//$("#chat3").delay(1200).fadeIn(speed);
	// $("#dot1").fadeOut();
	// $("#dot2").fadeOut();
	// $("#dot3").fadeOut();
	setTimeout(function(){ 
			//$(".clientworkouts .fltright .bluebtn").first().append(bubble6); 
			$(".m_workouts a").append(bubble6); 
			//$("#dot6").css("left","480px");
			glow();
			initilizeStop();
			
	},500);
	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );


}

function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
	

}

function initilizeStop(){
	$(".dot").hover(function() {
	  $('.dot').stop(true).fadeTo(200, 1);
	}, function() {
	  $('.dot').stop(true).fadeTo(200, 0);
	});

	$(".dot").mouseout(glow);
}


</script>