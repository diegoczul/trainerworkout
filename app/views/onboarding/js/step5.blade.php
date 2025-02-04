

<script>
var speed = 200;
var testimonialTrigger = true;
var workoutTrigger = true;
var bubble1 = '<span class="dot" id="dot1"></span>';





$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	setTimeout(function(){ 
		postMessageOnboarding(20);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(20.1);
	},500);
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat2").delay(500).fadeIn(speed);
	setTimeout(function(){ 
			$(".createaworkout .fltright .bluebtn").first().css("position","relative");
			$(".createaworkout .fltright .bluebtn").first().append(bubble1); 
			$("#dot1").css("left","-20px");
			glow();
	},4000);	

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});


function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
	initilizeStop();

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