

<script>
var speed = 200;
var testimonialTrigger = true;

var bubble = '<span class="dot" id="dot1"></span>';

$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	//$(".chatbox-container").css("right","auto");
	//$(".chatbox-container").css("left", "1%" );
	setTimeout(function(){ 
		postMessageOnboarding(4.2);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(4.3);
	},2000);
	// $("#chat1").delay(500).fadeIn(speed);
	// $("#chat2").delay(2000).fadeIn(speed);
	setTimeout(function(){ 
			$(".profieldetails h3").css("position","relative"); 
			$(".profieldetails h3").append(bubble); 
			$('.dot').css('top', 'auto').css('left', 'auto');
			$(".dot").css("top","0px !important") ;
			$(".dot").css("right","0px !important"); 
 
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