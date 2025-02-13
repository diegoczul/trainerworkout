

<script>
var speed = 200;
var testimonialTrigger = true;
var bubble1 = '<span class="dot" id="dot1"></span>';


$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat2").delay(900).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(21);
	},800);
	setTimeout(function(){ 
		postMessageOnboarding(22);
	},800);
	setTimeout(function(){ 
			$(".fullwidthwidget .bluebtn").first().css("position","relative");
			$(".fullwidthwidget .bluebtn").first().append(bubble1); 
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