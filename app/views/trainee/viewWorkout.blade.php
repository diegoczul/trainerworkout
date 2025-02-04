@extends('layouts.respTrainee')

 <!--------------------------    Page for consume to VIEW their received workouts     ---------------------------->



@section('content')
<div class="workoutBg">
<!-- Workout Header --> 
<div class="workoutHeaderContainer">
	<div class="workoutHeaderWrapper">
		<div class="workoutHedaer">
			<h1>Workout Title</h1>
			<!-- Message from the personal trainer about the workout -->
			<div class="trainerWorkoutMessageContainer">
				<div class="trainerWorkoutMessage">
					<p>This is the very long mesasge for a personal trainer to his client about the whole workout and what the trainee needs to know about the workout. I know I am being repetitive here but I don't have a choice in order to make enought text.</p>
				</div>
			</div>
			<div class="workoutData">
				<div class="workoutPT">
					<img src="/img/Trainee/expPic/PTimg.jpg">
					<div class="workoutPTname">
						<p>first name</p>
						<p>last name</p>
					</div>
				</div>
				<div class="workoutDate">
					<p>received </p>
					<p>Jan 2, 2015</p>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Workout Legend --> 
<div class="lgContainer">
	<div class="lg-circuit">
		<h4>Circuit</h4>
	</div>
	<div class="lg-cardio">
		<h4>Cardio</h4>
	</div>
	<div class="lg-muscle">
		<h4>Muscle</h4>
	</div>
</div>


<!-- Cardio Exercise Widget -->

<div class="exerciseContainer">
	<div class="exerciseWid cardio">

	<!-- Header of exercise -->
		<div class="exeHeader hd">
			<h3>Exercise Name</h3>
		</div>

	<!-- Notification from the personal trainer about the exercise -->
		<div class="ptNotification">
			<div class="ptNotificationWrapper">
				<img src="/img/Trainee/expPic/PTimg.jpg">
				<div class="notification">1</div>
			</div>
		</div>

	<!-- INFO of exercise [description & units] -->	
		<div class="exeInfo">

		<!-- Exercise Description -->
			<div class="exeDescription">
				<div class="exeDescriptionExp">
					<svg width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
					    <title>
					        See Exercise Description
					    </title>
					    <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
					        <path d="M.5 4.5 h8"/></path>
					        <path d="M4.5 .5 v8"/></path>
					    </g>
					</svg>
				</div>
				<p>exercise description</p>

			<!-- exercise description expanded -->
				<div class="exerciseDescription">
					<p>This is an exercise description of an exercise that describes the exercise you are seeing above. This is really boring to type some seriously dummy text. I am trying to keep this text PG 13</p>
				</div>
			</div>

		<!--  Switch for units used -->
			<div class="unitSwitcherContainer">
				<p>km</p>
				<label class="unitToggleLabel">
					<input type="checkbox" class="unitToggleInput">
					<div class="unitToggleControl"></div>
				</label>
				<p>mi.</p>
			</div>
		</div>

	<!-- Exercise [Image % Data Table ] -->
		<div class="exeDetails">

		<!-- Exercise Image -->
			<div class="exeImg">
				<img src="/img/Trainee/expPic/ex1.jpg">
				<img src="/img/Trainee/expPic/ex2.jpg">
			</div>

		<!-- Exercise Data -->
			<div class="exeData">
				<table class="exercise">
					<caption>cardio exercise</caption>
					<thead>
						<tr>
							<th class="tbInt" scope="col">Int</th>
							<th class="tbHr" scope="col">Heart Rate</th>
							<th class="tbSpeed" scope="col">Speed</th>
							<th class="tbDist" scope="col">Distance</th>
							<th class="tbTime" scope="col">Time</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">1</td>
							<td>70<span> % max</span></td>
							<td>15<span> km / h</span></td>
							<td>500<span> m</span></td>
							<td>12<span> min</span></td>
						</tr>
						<tr>
							<th scope="row">2</td>
							<td>120<span> bpm</span></td>
							<td>-<span></span></td>
							<td>500<span> m</span></td>
							<td>-<span></span></td>
						</tr>
						<tr>
							<th scope="row">3</td>
							<td>120<span> bpm</span></td>
							<td>-<span></span></td>
							<td>500<span> m</span></td>
							<td>-<span></span></td>
						</tr>
						<tr>
							<th scope="row">4</td>
							<td>120<span> bpm</span></td>
							<td>-<span></span></td>
							<td>500<span> m</span></td>
							<td>-<span></span></td>
						</tr>
					</tbody>	
				</table>
			</div>
		<!-- End of Exercise Data -->	
		</div>	
	</div>
</div>


<!-- Rest Between Exercise -->

<div class="restBtwExe">
	<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
	    <title>
	        Pause Icon
	    </title>
	    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
	        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
	        <path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/>
	    </g>
	</svg>
	<h5>30 sec rest before your next exercise</h5>
</div>



<!-- Muscle Exercise Widget -->

<div class="exerciseContainer">
	<div class="exerciseWid muscle">

	<!-- Header of exercise -->
		<div class="exeHeader hd">
			<h3>Exercise Name</h3>
		</div>

	<!-- INFO of exercise [description & units] -->	
		<div class="exeInfo">

		<!-- Exercise Description -->
			<div class="exeDescription">
				<div class="exeDescriptionExp">
					<svg width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
					    <title>
					        See Exercise Description
					    </title>
					    <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
					        <path d="M.5 4.5 h8"/></path>
					        <path d="M4.5 .5 v8"/></path>
					    </g>
					</svg>
				</div>
				<p>exercise description</p>

			<!-- exercise description expanded -->
				<div class="exerciseDescription">
					<p>This is an exercise description of an exercise that describes the exercise you are seeing above. This is really boring to type some seriously dummy text. I am trying to keep this text PG 13</p>
				</div>
			</div>

		<!--  Switch for units used -->
			<div class="unitSwitcherContainer">
				<p>kg</p>
				<label class="unitToggleLabel">
					<input type="checkbox" class="unitToggleInput">
					<div class="unitToggleControl"></div>
				</label>
				<p>Lbs</p>
			</div>
		</div>

	<!-- Exercise [Image % Data Table ] -->
		<div class="exeDetails">

		<!-- Exercise Image -->
			<div class="exeImg">
				<img src="/img/Trainee/expPic/ex1.jpg">
				<img src="/img/Trainee/expPic/ex2.jpg">
			</div>

		<!-- Exercise Data -->
			<div class="exeData">
				<div class="exeTempo">
					<p>exercise tempo</p>
					<p>1</p>
					<p>0.5</p>
					<p>3</p>
					<p>0.5</p>
				</div>

				<table class="exeData_table">
					<caption>muscle exercise</caption>
					<thead>
						<tr>
							<th class="tbSet" scope="col">Set</th>
							<th class="tbWeight" scope="col">Weight</th>
							<th class="tbRep" scope="col">Rep</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th scope="row">1</td>
							<td>35<span> Lbs</span></td>
							<td>15<span></span></td>
						</tr>
						<tr><td class="restBtwSet" colspan="3">20 sec rest before next rounds</td></tr>
						<tr>
							<th scope="row">2</td>
							<td>35<span> Lbs</span></td>
							<td>-<span></span></td>
						</tr>

					</tbody>	
				</table>
			</div>
		<!-- End of Exercise Data -->	
		</div>	
	</div>
</div>

<!-- Rest Between Exercise -->

<div class="restBtwExe">
	<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
	    <title>
	        Pause Icon
	    </title>
	    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
	        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
	        <path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/>
	    </g>
	</svg>
	<h5>30 sec rest before your next exercise</h5>
</div>




<!-- CIRCUIT Exercises -->

<div class="exerciseContainer circuit">
	<div class="circuitLine"></div>
	<div class="exerciseWid circuit">
	
	<!-- Header of exercise -->
		<div class="circuitHeader hd">
			<h3>Circuit #1</h3>
			<div class="circuitDetails">
				<h3>3 X</h3>
				<div class="circuitImg">
					<img src="/img/Trainee/expPic/ex1.jpg">
					<img src="/img/Trainee/expPic/ex2.jpg">
					<img src="/img/Trainee/expPic/img1.jpg">
					<img src="/img/Trainee/expPic/img3.jpg">
				</div>
			</div>
		</div>

	<!-- Exercise 1 in Circuit -->
	<!-- INFO of exercise [description & units] -->	
		<div class="circuitExercise">
			<div class="exeInfo">

			<!-- Exercise Description -->
				<div class="exeName">
					<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
					    <title>
					        Play Icon
					    </title>
					    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
					        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
					        <path fill="#2C3E50" d="M19 12.5L8 20V5z"/>
					    </g>
					</svg>
					<h4><span>1</span>Exercise Name</h4>
				</div>
				
				<div class="exeDescription">
					<div class="exeDescriptionExp">
						<svg width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
						    <title>
						        See Exercise Description
						    </title>
						    <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
						        <path d="M.5 4.5 h8"/></path>
						        <path d="M4.5 .5 v8"/></path>
						    </g>
						</svg>
					</div>
					<p>exercise description</p>

				<!-- exercise description expanded -->
					<div class="exerciseDescription">
						<p>This is an exercise description of an exercise that describes the exercise you are seeing above. This is really boring to type some seriously dummy text. I am trying to keep this text PG 13</p>
					</div>
				</div>

			<!--  Switch for units used -->
				<div class="unitSwitcherContainer">
					<p>kg</p>
					<label class="unitToggleLabel">
						<input type="checkbox" class="unitToggleInput">
						<div class="unitToggleControl"></div>
					</label>
					<p>Lbs</p>
				</div>
			</div>

		<!-- Exercise [Image % Data Table ] -->
			<div class="exeDetails">

			<!-- Exercise Image -->
				<div class="exeImg">
					<img src="/img/Trainee/expPic/ex1.jpg">
					<img src="/img/Trainee/expPic/ex2.jpg">
				</div>

			<!-- Exercise Data -->
				<div class="exeData">
					<div class="exeTempo">
						<p>exercise tempo</p>
						<p>1</p>
						<p>0.5</p>
						<p>3</p>
						<p>0.5</p>
					</div>
					<table class="exercise">
						<caption>cardio exercise</caption>
						<thead>
							<tr>
								<th class="tbRound" scope="col">Round</th>
								<th class="tbWeight" scope="col">Weight</th>
								<th class="tbRep" scope="col">Time</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">1</td>
								<td>35<span> Lbs</span></td>
								<td>15<span></span></td>
							</tr>
							<tr>
								<th scope="row">2</td>
								<td>35<span> Lbs</span></td>
								<td>-<span></span></td>
							</tr>

						</tbody>	
					</table>
				</div>
			<!-- End of Exercise Data -->	
			</div>	
		</div>

		<!-- Rest Between Exercise in Circuit -->
		<div class="circuitRestBtwExe">
			<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
			    <title>
			        Pause Icon
			    </title>
			    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
			        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
			        <path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/>
			    </g>
			</svg>
			<h5>30 sec rest before next exercise</h5>
		</div>

		<!-- Exercise 2 in Circuit -->
		<div class="circuitExercise">
			<div class="exeInfo">

			<!-- Exercise Description -->
				<div class="exeName">
					<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
					    <title>
					        Play Icon
					    </title>
					    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
					        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
					        <path fill="#2C3E50" d="M19 12.5L8 20V5z"/>
					    </g>
					</svg>
					<h4><span>2</span>Exercise Name</h4>
				</div>
				
				<div class="exeDescription">
					<div class="exeDescriptionExp">
						<svg width="9" height="9" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
						    <title>
						        See Exercise Description
						    </title>
						    <g stroke-linecap="square" stroke="#2C3E50" fill="none" fill-rule="evenodd">
						        <path d="M.5 4.5 h8"/></path>
						        <path d="M4.5 .5 v8"/></path>
						    </g>
						</svg>
					</div>
					<p>exercise description</p>

				<!-- exercise description expanded -->
					<div class="exerciseDescription">
						<p>This is an exercise description of an exercise that describes the exercise you are seeing above. This is really boring to type some seriously dummy text. I am trying to keep this text PG 13</p>
					</div>
				</div>

			<!--  Switch for units used -->
				<div class="unitSwitcherContainer">
					<p>kg</p>
					<label class="unitToggleLabel">
						<input type="checkbox" class="unitToggleInput">
						<div class="unitToggleControl"></div>
					</label>
					<p>Lbs</p>
				</div>
			</div>

		<!-- Exercise [Image % Data Table ] -->
			<div class="exeDetails">

			<!-- Exercise Image -->
				<div class="exeImg">
					<img src="/img/Trainee/expPic/ex1.jpg">
					<img src="/img/Trainee/expPic/ex2.jpg">
				</div>

			<!-- Exercise Data -->
				<div class="exeData">
					<div class="exeTempo">
						<p>exercise tempo</p>
						<p>1</p>
						<p>0.5</p>
						<p>3</p>
						<p>0.5</p>
					</div>
					<table class="exercise">
						<caption>cardio exercise</caption>
						<thead>
							<tr>
								<th class="tbRound" scope="col">Round</th>
								<th class="tbWeight" scope="col">Weight</th>
								<th class="tbRep" scope="col">Time</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">1</td>
								<td>35<span> Lbs</span></td>
								<td>15<span></span></td>
							</tr>
							<tr>
								<th scope="row">2</td>
								<td>35<span> Lbs</span></td>
								<td>-<span></span></td>
							</tr>

						</tbody>	
					</table>
				</div>
			<!-- End of Exercise Data -->	
			</div>		
		</div>
		<!-- Rewind Icons end of Circuit -->
		<div class="endCircuit circuitRestBtwExe">
			<svg width="27" height="27" viewBox="0 0 27 27" xmlns="http://www.w3.org/2000/svg">
			    <title>
			        Rewind Icon
			    </title>
			    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
			        <circle stroke="#2C3E50" stroke-width=".926" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
			        <path d="M19.136 12.963c0-.815-.158-1.595-.445-2.313-.975-2.438-3.448-4.17-6.344-4.17A6.95 6.95 0 0 0 7.84 8.115m-2.284 4.85c0 3.58 3.04 6.48 6.79 6.48 1.332 0 3.623-1 3.623-1" stroke="#2C3E50" stroke-width="1.852"/>
			        <path fill="#2C3E50" d="M20.103 16.754l.96-5.646-5.055 1.642zM6.354 8.118L3.18 12.886l5.287.555z"/>
			    </g>
			</svg>
			<h5> 60 sec rest before next round</h5>
		</div>
	</div>
</div>


<div class="overlayMessage">
	<div class="overlayMessageWrapper">
		<svg width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg">
		    <title>
		        Close Icon
		    </title>
		    <path d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z" fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
		</svg>
		<div class="trainerWorkoutMessageContainer">
			<div class="trainerWorkoutMessage">
				<p>This is the very long mesasge for a personal trainer to his client about the whole workout and what the trainee needs to know about the workout. I know I am being repetitive here but I don't have a choice in order to make enought text.</p>
			</div>
		</div>
		<div class="ptNotification">
			<div class="ptNotificationWrapper">
				<img src="/img/Trainee/expPic/PTimg.jpg">
				<div class="notification">1</div>
			</div>
		</div>
	</div>
</div>


</div>



<!-- opens and closes the exercise description -->
<script type="text/javascript">
	

$(".exeDescription").click (function () {
	if ($(this).find(".exerciseDescription").is(":visible")) {
		$(this).find(".exerciseDescription").slideUp(300);
		$(this).find(".exeDescriptionExp path:nth-child(2)").css("opacity", "1");
	} else {
		$(this).find(".exerciseDescription").slideDown(300);
		$(this).find(".exeDescriptionExp path:nth-child(2)").css("opacity", "0");
	}
});



//On click on a notification
$(".ptNotification").click (function () {
	// Show Overlay
	$('.overlayMessage').css("display", "flex");
	// Restrict Scroll of the body
	// $('body').css("overflow", "hidden").css("height", "100vh");
});



//On click of an overlay 
$('.overlayMessage').click(function(){
	//The overlay hides
	$('.overlayMessage').css("display", "none");
	//Enable body movement again
	// $('body').css("overflow", "auto").css("height", "auto");
})


</script>



@endsection