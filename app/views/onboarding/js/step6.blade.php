

<script>
var speed = 200;
var testimonialTrigger = true;
var workoutTrigger = true;
var bubble1 = '<span class="dot" id="dot1"></span>';
var bubble2 = '<span class="dot" id="dot2"></span>';
var bubble3 = '<span class="dot" id="dot3"></span>';
var bubble4 = '<span class="dot" id="dot3"></span>';
var bubble5 = '<span class="dot dot5"></span>';
var bubble6 = '<span class="dot" id="dot6"></span>';
var bubble7 = '<span class="dot dot7"></span>';
var bubble8 = '<span class="dot" id="dot8"></span>';





$(document).ready(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	//$(".chatbox-container").css("right","auto");
	//$(".chatbox-container").css({ "left": "1%" });
	setTimeout(function(){ 
		postMessageOnboarding(1);
	},500);
	setTimeout(function(){ 
		postMessageOnboarding(11.1);
	},2000);
	//$("#chat1").delay(500).fadeIn(speed);
	//$("#chat11").delay(900).fadeIn(speed);
	setTimeout(function(){ 
			$(".addnameworkout").css("position","relative");
			$("#nameWorkoutLink").append(bubble1); 
			$("#dot1").css("left","-18px");
			$("#dot1").css("top","-15px");
			$("#pname").parent().append(bubble2); 
			$("#dot2").css("top","6px");
			glow();
	},4000);	

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});


function glow(){
	$(".dot").animate({opacity:0.2},1000);
	$(".dot").animate({opacity:1},1000,glow);
	initilizeStop();

}

$("#nameWorkoutLink").click(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	$("#dot1").remove();
	$("#dot2").remove();
	//$("#chat1").delay(500).fadeOut(speed);
	//$("#chat11").delay(500).fadeOut(speed);
	setTimeout(function(){ 
		postMessageOnboarding(12);
	},800);
	setTimeout(function(){ 
		postMessageOnboarding(12.1);
	},1000);
	setTimeout(function(){ 
		postMessageOnboarding(12.2);
	},1200);
	// $("#chat2").delay(800).fadeIn(speed);
	// $("#chat21").delay(1000).fadeIn(speed);
	// $("#chat22").delay(1200).fadeIn(speed);
	setTimeout(function(){
		$(".advsearch").css("position","relative");
		$(".advsearch").css("float","right");
		$(".advsearch").append(bubble3); 
		//$("#dot3").css("left","270px");
		glow();
	},3000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$("#ex_name").click(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	$("#dot3").remove();
	//$("#chat1").delay(500).fadeOut(speed);
	//$("#chat11").delay(500).fadeOut(speed);
	//$("#chat2").delay(500).fadeOut(speed);
	//$("#chat21").delay(500).fadeOut(speed);
	//$("#chat22").delay(500).fadeOut(speed);
	setTimeout(function(){ 
		postMessageOnboarding(13);
	},800);
	setTimeout(function(){ 
		postMessageOnboarding(13.1);
	},1000);
	//$("#chat3").delay(800).fadeIn(speed);
	//$("#chat31").delay(1000).fadeIn(speed);
	setTimeout(function(){
		$("#selectExercisesLink").css("position","relative");
		$("#section2").append("<div class='clearfix'></div>");
		$("#selectExercisesLink").css("float","right");
		$("#selectExercisesLink").append(bubble4);
		glow();
	
	},3000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$("#selectExercisesLink").click(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	$("#dot3").remove();
	// $("#chat3").delay(500).fadeOut(speed);
	// $("#chat31").delay(500).fadeOut(speed);
	// $("#chat4").delay(800).fadeIn(speed);
	// $("#chat41").delay(1000).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(14);
	},800);
	setTimeout(function(){ 
		postMessageOnboarding(14.1);
	},1000);
	setTimeout(function(){
		$(".pull").css("position","relative");
		$(".pull").append(bubble5);
		$(".dot5").css("left","20px");
		$(".dot5").css("top","20px");
		glow();
	},3000);
	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$(".editform input").click(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	$(".dot5").remove();
	// $("#chat4").delay(500).fadeOut(speed);
	// $("#chat41").delay(500).fadeOut(speed);
	// $("#chat5").delay(800).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(15);
	},800);
	setTimeout(function(){
		$("#editExercisesLink").css("position","relative");
		$("#section3").append("<div class='clearfix'></div>");
		$("#editExercisesLink").css("float","right");
		$("#editExercisesLink").append(bubble6);
		glow();
	},3000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

$("#editExercisesLink").click(function(){
	//$(".chatbox-container").css({ opacity: 1 });
	$("#chat4").delay(500).fadeOut(speed);
	$("#dot6").remove();
	$(".dot5").remove();
	// $("#chat41").delay(500).fadeOut(speed);
	// $("#chat5").delay(500).fadeOut(speed);
	// $("#chat6").delay(800).fadeIn(speed);
	setTimeout(function(){ 
		postMessageOnboarding(16);
	},800);
	setTimeout(function(){
		$("#editExercisesLink").css("position","relative");
		$("#orderList li").append(bubble7);
		glow();
	},3000);
	setTimeout(function(){
		$("#dot7").remove();
		$("#createWorkoutLink").css("position","relative");
		$("#createWorkoutLink").append(bubble8);
		$("#createWorkoutLink").css("float","right");
		$("#section4").append("<div class='clearfix'></div>");
		$("#chat7").delay(400).fadeIn(speed);
		glow();
	},6000);

	//$(".chatbox-container").delay(12000).fadeTo( "slow", 0.33 );
});

function initilizeStop(){
	$(".dot").hover(function() {
	  $('.dot').stop(true).fadeTo(200, 1);
	}, function() {
	  $('.dot').stop(true).fadeTo(200, 0);
	});

	$(".dot").mouseout(glow);
}


</script>