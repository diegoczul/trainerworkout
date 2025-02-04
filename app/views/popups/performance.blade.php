{{ HTML::style('css/innerStyles.css') }}
    {{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
    {{ HTML::style('fw/chosen_v1/chosen.css'); }}
    {{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
    
@extends('layouts.popup')

@section('content')
<div class="graphPerformance">
<h2>Exercise Weight Performance</h2>

@if ($sets->count() > 1)
<div class="graphholder" style="width:470px"> 
	<canvas id="myChart" width="470" height="300"></canvas>
</div>
<span style="font-size:12px; color:rgba(0,204,102,1); display:block;margin-left:3px"><strong>Weight</strong></span> <span style="font-size:12px; color:rgba(0,102,204,1); display:block; ;margin-left:3px"><strong>Reps</strong></span>
@else 
<span style="font-size:14px; color:#666; display:block">If no graph appears is because you cannot graph exercises that have no weights/reps entries. Please insert an entry into the exercise.</span>
@endif



<h2>Effort Performance</h2>

@if ($sets->count() > 1)
<div class="graphholder" style="width:470px"> 
	<canvas id="myChart2" width="470" height="300"></canvas>
</div>
<span style="font-size:12px; color:rgba(0,204,102,1); display:block;margin-left:3px"><strong>Effort</strong></span>
@else 
<span style="font-size:14px; color:#666; display:block">If no graph appears is because you cannot graph exercises that have no weights/reps entries. Please insert an entry into the exercise.</span>

@endif
</div>

@endsection

@section("scripts")
{{ HTML::script('fw/chartjs/Chart.js'); }}

<script type="text/javascript">

@if ($sets->count() > 1)

	      
	      	var ctx = document.getElementById("myChart").getContext("2d");
			var data = {
				labels: {{ json_encode($y1) }},
				scaleShowLabels: true,
				datasets: [
					{
						fillColor: "rgba(0,204,102,0.2)",
						strokeColor: "rgba(0,204,102,1)",
						pointColor: "rgba(0,170,68,1)",
						pointStrokeColor: "#fff",
						label: "Weight",
						//tooltipXPadding: 12,
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: {{json_encode($datay1)}}
					},
					{
						fillColor: "rgba(0,102,204,0.2)",
						strokeColor: "rgba(0,102,204,1)",
						pointColor: "rgba(0,68,170,1)",
						pointStrokeColor: "#fff",
						label: "Reps",
						//tooltipXPadding: 12,
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: {{json_encode($datayReps1)}}
					}
					
				]
			};
			var myLineChart = new Chart(ctx).Line(data, {
				//scaleShowLabels : false,
				 // String - Tooltip title font weight style
				tooltipTitleFontStyle: "bold",
				scaleFontSize: 10,
				// String - Tooltip title font colour
				tooltipTitleFontColor: "#fff",
			
				// Number - pixel width of padding around tooltip text
				tooltipYPadding: 6,
			
				// Number - pixel width of padding around tooltip text
				tooltipXPadding: 6,
			
				// Number - Size of the caret on the tooltip
				tooltipCaretSize: 8,
			
				// Number - Pixel radius of the tooltip border
				tooltipCornerRadius: 6,
			
				// Number - Pixel offset from point x to tooltip edge
				scaleLabel: " <%=value%>",
				tooltipFontSize: 10,
			});

			var ctx2= document.getElementById("myChart2").getContext("2d");
			var data2 = {
				labels: {{ json_encode($y1) }},
				scaleShowLabels: true,
				datasets: [
					{
						fillColor: "rgba(0,204,102,0.2)",
						strokeColor: "rgba(0,204,102,1)",
						pointColor: "rgba(0,170,68,1)",
						pointStrokeColor: "#fff",
						label: "Weight",
						//tooltipXPadding: 12,
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: {{json_encode($datay2)}}
					}
					
				]
			};
			var myLineChart2 = new Chart(ctx2).Line(data2, {
				//scaleShowLabels : false,
				 // String - Tooltip title font weight style
				tooltipTitleFontStyle: "bold",
				scaleFontSize: 10,
				// String - Tooltip title font colour
				tooltipTitleFontColor: "#fff",
			
				// Number - pixel width of padding around tooltip text
				tooltipYPadding: 6,
			
				// Number - pixel width of padding around tooltip text
				tooltipXPadding: 6,
			
				// Number - Size of the caret on the tooltip
				tooltipCaretSize: 8,
			
				// Number - Pixel radius of the tooltip border
				tooltipCornerRadius: 6,
			
				// Number - Pixel offset from point x to tooltip edge
				scaleLabel: " <%=value%>",
				tooltipFontSize: 10,
			});

@endif
			
	</script>


</script>
@endsection