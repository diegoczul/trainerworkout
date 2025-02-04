

<script>
var speed = 200;
var testimonialTrigger = true;
var bubble1 = '<span class="dot" id="dot1"></span>';


$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	// $("#chat1").delay(500).fadeIn(speed);
	// $("#chat2").delay(1000).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(19);
	},800);
	setTimeout(function(){ 
		postMessageOnboarding(19.1);
	},800);
	setTimeout(function(){ 
			$(".w_workouts").first().css("position","relative");
			$(".w_workouts").append(bubble1); 
			$("#dot1").css("left","-20px");
			$("#dot1").css("top","-20px");
			glow();  
	},2000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});



function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
}


</script>