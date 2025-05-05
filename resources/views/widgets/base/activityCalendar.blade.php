@use('App\Http\Libraries\Helper')
@use('App\Http\Libraries\Messages')
<div class="calendar_navigation">
	<div class="calendarDays">
	<?php
		$dateS = new DateTime($dateStart);
		$dateE = new DateTime($dateEnd);
		
	?>
		<p class="date"><span id="calendarFrom">{{ $dateS->format('F') }} {{ $dateS->format('d') }}</span> {{ Lang::get("content.to") }} <span id="calendarTo">{{ $dateE->format('F') }} {{ $dateE->format('d') }}</span></p>
	</div>
	<div class="calendarOptions">
		<div class="selections">
			<a href="javascript:void(0)" class="{{ ($default) ? "option-selected" : "" }}" onClick="updateCalendar(this,'last30Days')">{{ Lang::get("content.Last 30 Days") }}</a>
			<a href="javascript:void(0)" class="{{ ($default) ? "" : "option-selected" }}" onClick="$('.calendar_customDates').toggle(); $(this).prev().removeClass('option-selected'); $(this).addClass('option-selected');">{{ Lang::get("content.Custom dates") }}</a>
		</div>
		<div class="calendar_customDates">
			<div>
				<label for="startDateActivity">{{ Lang::get("content.Custom Date Start") }}</label>
				<input name="startDateActivity" id="startDateActivity" value="{{ $dateStart }}" class="datepicker" />
			</div>
			<div>
				<label for="endDateActivity">{{ Lang::get("content.Custom Date End") }}</label>
				<input name="endDateActivity" id="endDateActivity" value="{{ $dateEnd }}" class="datepicker" />
			</div>
			<button id="submit_calendar" type="submit" onClick="updateCalendar(this);">{{ Lang::get("content.Update dates") }}</button>
		</div>
	</div>
</div>


<div class="calendar_wrapper">

	<div class="weekRow">
		<div class="calendar_day calendar_Sunday calendar_day_header"> Sun</div>
		<div class="calendar_day calendar_Monday calendar_day_header"> Mon</div>
		<div class="calendar_day calendar_Tuesday calendar_day_header"> Tue</div>
		<div class="calendar_day calendar_Wednesday calendar_day_header"> Wed</div>
		<div class="calendar_day calendar_Thursday calendar_day_header"> Thur</div>
		<div class="calendar_day calendar_Friday calendar_day_header"> Fri</div>
		<div class="calendar_day calendar_Saturday calendar_day_header"> Sat</div>
	</div>

	<div class="weekRow">

	<?php $started = false; ?>
	@foreach ($activities as $activitiy=>$data)

	<?php $timestamp = strtotime($activitiy); ?>
   <?php $day = date('l', $timestamp); ?>
   <?php $dayW = date('l', $timestamp); ?>

    @if($day == "Sunday" and $started)
      </div>
      <div class="weekRow">
    @endif
    @if(!$started and $day == "Monday")
        <div class="calendar_day"></div>
    @endif
    @if(!$started and $day == "Tuesday")
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
    @endif
    @if(!$started and $day == "Wednesday")
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
    @endif
    @if(!$started and $day == "Thursday")
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
    @endif
    @if(!$started and $day == "Friday")
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>

    @endif
    @if(!$started and $day == "Saturday")
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>
        <div class="calendar_day"></div>

    @endif
    <?php $started = true; ?>

	<?php $nbPerformanceDay = 0; ?>
	<?php $day = date('d', $timestamp); ?>

	<div class="calendar_day calendar_{{ $dayW }} @if($activitiy == date("Y-m-d")) calendar_day_today @endif  @if(isset($data["performance"]) && count($data["performance"]) > 0) calendar_day_activity @endif" @if(isset($data["performance"]) && count($data["performance"]) > 0) onclick="showDetails(this)" @endif>
			@if(count($data["performance"]) > 0)
			{{ $day }}
					<div class="hide">
						<div class="calendarPerformanceContainer">
							<div onclick="closePop()" class="close">
								<img src="/assets/img/svg/closeDark.svg">
							</div>
							<div class="calendarPerformanceHeader">
								@if(count($data["performance"]) > 1)
								<div class="workoutSwitcher">
									<!-- <p>workout of the day </p> -->
									<?php $performanceNb = 0; ?>
									@foreach($data["performance"] as $performance)
										<?php $performanceNb++; ?>
										<a class="Anchorperformance <?php if($performanceNb == 1) {echo "active";} ?>" href="javascript:void(0)" onclick="toggleTo(this, '{{ $performance->id }}')">{{ Lang::get("content.workout") }} <?php echo $performanceNb; ?> / {{count($data["performance"])}} </a><span>, </span>
									@endforeach
								</div>
								@endif

							</div>

							<?php $performanceId = 0; ?>



							@foreach($data["performance"] as $performance)

							<?php $performanceId++; ?>
							<div class="calendarPerformance--Details <?php if($performanceId == 1) {echo "calendarPerformance--Details--active";} ?> performance_{{ $performance->id }}">
								<h1>{{ ($performance->workout) ? $performance->workout->name : "No name to this workout" }}</h1>
								<div class="calendarPerformance--Content">
									<div class="calendarPerformance--Info">
										<p><span>{{ Lang::get("content.Client") }}</span>: {{ ($performance->user) ? ($performance->user->getCompleteName() == "" ? $performance->user->email : $performance->user->getCompleteName()) : "" }}</p>
										<p><span>{{ Lang::get("content.Date") }}</span>: {{ Helper::date($performance->dateCompleted) }}</p>
										<p><span>{{ Lang::get("content.Rating") }}</span>: {{ $performance->rating->name }}</p>
										<p><span>{{ Lang::get("content.Times Performed") }}</span>: {{ ($performance->workout) ? $performance->workout->getCountPerformed($performance->workout->user->id) : 0 }} </p>
										<p><span>{{ Lang::get("content.Duration") }}</span>: {{ number_format($performance->timeInSeconds/60,1) }} min</p>
									</div>
									@if($performance->comments != "")
									<div class="calendarPerformanceComment">
										<p>{{ ($performance->user) ? $performance->user->getCompleteName() : "User Not Found" }} {{ Lang::get("content.says") }}:</p>
										<p>{{ $performance->comments}}</p>
									</div>

								@endif
								</div>
							</div>
							@endforeach
						</div>
					</div>


			@else
					{{ $day }}
			@endif
	</div>




	@endforeach

	</div>

</div>

<script>

function updateCalendar(object,interval){

	$(".selections a").removeClass("option-selected");
	$(object).addClass("option-selected");

	var beginingDate = $("#startDateActivity").val();
	var endDate = $("#endDateActivity").val();


	callWidgetExternal("w_calendarActivity_full",null,{{ $userId }},$(object),{ currentEndDate: '{{ $currentEndDate }}' ,dateStart:$("#startDateActivity").val(), dateEnd:$("#endDateActivity").val(),interval: interval });


	$("#calendarFrom").text(beginingDate);
	$("#calendarTo").text(endDate);


}

$(document).ready(function(){
	$(function(datepicker) {
		$( ".datepicker" ).datepicker({
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			yearRange: '1920:2019',
		});

	});

});


function toggleTo(obj, id){
	$(".calendarPerformance--Details").removeClass("calendarPerformance--Details--active");
	$(".Anchorperformance").removeClass("active");
	$(obj).addClass("active");
	var $element = $(".pop-up .performance_" + id);
	$element.addClass("calendarPerformance--Details--active");
}
</script>
































