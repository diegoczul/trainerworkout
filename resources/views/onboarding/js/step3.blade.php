

<script>
var speed = 200;
var testimonialTrigger = true;
var bubble1 = '<span class="dot" id="dot1"></span>';


$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	setTimeout(function(){ 
		postMessageOnboarding(8);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(8.1);
	},900);
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat2").delay(900).fadeIn(speed);
	setTimeout(function(){ 
			$(".clientlist li").css("position","relative"); 
			$(".clientlist li").first().append(bubble1); 
			$("#dot1").css("top","20px") ;
			$("#dot1").css("left","120px") ;
			glow();  
	},2000);

	setTimeout(function(){ 
			$(".inner-dot").css("visibility","hidden"); 
			$(".arrow").css("visibility","hidden"); 
	},10000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});



function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
}


</script>