@php
	use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;

	$nbPerformanceDay = 0;
@endphp


<table class="datatables reportTable" cellspacing="0" width="100%">
<thead>
	<tr>
		<th>Clients</th>
		@foreach($dates as $date)
			<th scope="col">{{ Helper::date($date) }}</th>
		@endforeach
</tr>
</thead>
<tbody>
	@foreach($clients as $client)
	@if($client->user)
	<tr>
		<td scope="row">{{ ($client->user) ? (($client->user->getCompleteName() == "") ? $client->user->email : $client->user->getCompleteName()) : "User not found" }}</td>
		
		@foreach($dates as $date)
			<td class="workoutPerformance">
				
				@foreach($performances[(string)$client->userId][$date] as $performance)

				@if($performance->workout)
				<div class="workoutPerformanceContainer cursor-pointer">
				<p class="workoutPerformancep">{{ ($performance->workout) ? $performance->workout->name : "" }}</p>
			
				<div class="workoutPerformance--Details">
					<div class="workoutPerformance--Each">
					<p class="title">{{ $performance->workout->name }}</p>
					
						<p><span>Client:</span> {{ ($performance->user) ? $performance->user->getCompleteName() : "User Not Found" }}</p>
						<p><span>Rating:</span> {{ $performance->rating->name }}</p>
						<p><span>Duration:</span> {{ number_format($performance->timeInSeconds/60,1) }} min</p>
						@if($performance->comments != "")
							<p><span>Comments:</span> {{ $performance->comments}}</p>
						@endif
					</div>
				</div>

				</div>
				
				@endif
				@endforeach
			</td>
		@endforeach
	</tr>
	@endif
@endforeach
</tbody>
</table>



<script type="text/javascript">
$(document).ready(function() {
    $('.reportTable').dataTable( {
        "scrollX": true,
        "iDisplayLength": 100,
         "language": {
            "lengthMenu": "{{ Lang::get("content.Display -- records per page") }}",
            "zeroRecords": "{{ Lang::get("content.No matching records found") }}",
            "infoEmpty": "{{ Lang::get("content.No records available") }}"
        }
    } );
} );
</script>


<script>
	$(document).ready(function () {
	  // Handle click and touchstart for iOS and other mobile devices
	  $('.workoutPerformancep')
		.off('click touchstart') 
		.on('click touchstart', function (e) {
		  e.preventDefault(); 
		  $(this)
			.closest('.workoutPerformanceContainer')
			.find('.workoutPerformance--Details')
			.toggle();
		});
  
	  // Only bind hover handlers for non-touch (desktop) devices
		if (!('ontouchstart' in window)) {
			$('.workoutPerformancep')
			.off('mouseenter mouseleave')
			.on('mouseenter', function () {
				$(this)
				.closest('.workoutPerformanceContainer')
				.find('.workoutPerformance--Details')
				.show();
			})
			.on('mouseleave', function () {
				$(this)
				.closest('.workoutPerformanceContainer')
				.find('.workoutPerformance--Details')
				.hide();
			});
		}
	});
</script>
  

