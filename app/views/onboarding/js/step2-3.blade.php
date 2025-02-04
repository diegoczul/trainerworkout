

<script>
var speed = 200;
var testimonialTrigger = true;

var bubble = '<span class="dot"></span>';



$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat2").delay(2000).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(7);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(7.1);
	},2000);
	setTimeout(function(){ 
			$(".m_friends a").css("position","relative");
			$(".m_friends a").append(bubble); 
			glow();
			initilizeStop();
	},4000);

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

function initilizeStop(){
	$(".dot").hover(function() {
	  $('.dot').stop(true).fadeTo(200, 1);
	}, function() {
	  $('.dot').stop(true).fadeTo(200, 0);
	});

	$(".dot").mouseout(glow);
}

</script>