

<script>
var speed = 200;
var bubble = '<span class="dot"></span>';

$(document).ready(function(){
	sendMessageToUser(15);
	setTimeout(function(){ 
		postMessageOnboarding(1);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(2);
	},2000);
	setTimeout(function(){ 
		postMessageOnboarding(2.1);
	},4000);
	setTimeout(function(){ 
		postMessageOnboarding(3);
	},6000);
	setTimeout(function(){ 
		$(".m_profile a").append(bubble); 
		glow();
	},4000);
	//$("#chat1").delay(8000).fadeOut(speed);
	//$("#chat2").delay(8000).fadeOut(speed);
	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
	
});

function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
}






</script>