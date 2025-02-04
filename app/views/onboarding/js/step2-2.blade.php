

<script>
var speed = 200;
var testimonialTrigger = true;

var bubble = '<span class="dot"></span>';


var bubble1 = '<span class="dot" id="dot1"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble1") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble2") }}}</div></div><span class="arrow"></span></span>';

var bubble2 = '<span class="dot" id="dot2"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble3") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble4") }}}</div></div><span class="arrow"></span></span>';

var bubble3 = '<span class="dot" id="dot3"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble5") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble6") }}}</div></div><span class="arrow"></span></span>';

var bubble4 = '<span class="dot" id="dot4"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble21") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble22") }}}</div></div><span class="arrow"></span></span>';

var bubble5 = '<span class="dot" id="dot5"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble23") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble24") }}}</div></div><span class="arrow"></span></span>';

var bubble6 = '<span class="dot" id="dot6"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble27") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble28") }}}</div></div><span class="arrow"></span></span>';

var bubble7 = '<span class="dot" id="dot7"><div class="inner-dot"><div class="header">{{{ Messages::showMessageOnboarding("bubble29") }}}</div><div class="body">{{{ Messages::showMessageOnboarding("bubble30") }}}</div></div><span class="arrow"></span></span>';



$(document).ready(function(){
	
	//$(".chatbox-container").css({ opacity: 1 });
	setTimeout(function(){ 
		postMessageOnboarding(5.0);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(5);
	},1000);
	setTimeout(function(){ 
		postMessageOnboarding(5.1);
	},1500);
	// $("#chat4").delay(500).fadeIn(speed);
	// $("#chat1").delay(1000).fadeIn(speed);
	// $("#chat2").delay(1500).fadeIn(speed);
	setTimeout(function(){ 
			$(".word").append(bubble1); 
			$(".bio").append(bubble2); 
			$(".testimonial").append(bubble3);
			$(".objectives").append(bubble4);
			$(".weight").append(bubble5);
			$(".bodyM").append(bubble6);
			$(".pictures").append(bubble7);

			$(".inner-dot").css("visibility","visible");  
			$(".arrow").css("visibility","visible");  
			glow();
			initilizeStop();
	},4000);

	setTimeout(function(){ 
			$(".inner-dot").css("visibility","hidden"); 
			$(".arrow").css("visibility","hidden"); 
	},10000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$(window).scroll(function () {

    if ($(window).height() > 400 && testimonialTrigger) {
    	testimonialTrigger = false;
    	launchTestimonials();
    } 
});

function launchTestimonials(){
	//$(".chatbox-container").css({ opacity: 1 });
	//$("#chat3").delay(500).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(4.2);
	},500);
	$(".m_dashboard a").css("position","relative");
	$(".m_dashboard a").append(bubble); 
	// $("#dot1").fadeOut();
	// $("#dot2").fadeOut();
	// $("#dot3").fadeOut();
	
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