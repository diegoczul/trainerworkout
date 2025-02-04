

<script>
var speed = 200;
var testimonialTrigger = true;
var workoutTrigger = true;
var bubble = '<span class="dot"></span>';

$(document).ready(function(){
	//$(".createanewworkout").hide();
	//$(".chatbox-container").css({ opacity: 1 });
	setTimeout(function(){ 
		postMessageOnboarding(10.1);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(10.2);
	},1000);
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat2").delay(1000).fadeIn(speed);
	setTimeout(function(){ 
			$(".m_workouts a").css("position","relative");
			$(".m_workouts a").append(bubble); ; 
			glow();
			initilizeStop();
	},4000);


	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});






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