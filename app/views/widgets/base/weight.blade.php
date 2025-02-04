@if($permissions["view"]) 
@if ($weights->count() > 0)
	@if ($weights->count() > 1)

	<div class="graphholder" style="width:270px"> 
	<canvas id="myChart" width="270" height="200"></canvas>
	<canvas id="myChartKilo" width="270" height="200" style="display:none"></canvas>
	</div>
	@else
	{{ Messages::showEmptyMessage("WeightOneRecord")}}
	@endif
	<div class="weighttable">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
			<tr>
						<th>Date</th>
						<th>Weight</th>
						<th>Weight</th>
						<th></th>
						</tr>
	                    @foreach ($weights as $weight)
	                            <tr class="rowDelete">
	                    			<td width="40%">{{ Helper::date($weight->recordDate) }}</td>
	                    			<td width="60%" class="pounds weights" style="display:block"> {{ $weight->weightPounds }} Lbs</td>
	                    			<td width="60%" class="kilograms weights" style="display:none"> {{ $weight->weightKilograms }} Kgs</td>
	                   			 	<td width="10%" class="alignright">
	                                <a href="javascript:void(0)" class="deleteicon"  onClick="return deleteWeight('{{$weight->id}}', $(this));">edit</a>
	                                </td>
	                  			</tr>
	                    @endforeach  
	                    </table>   
	                    <div class="form-control">
	<label> Pounds <input type="radio" name="type" id="type" value="pounds" onclick="toggleWeightType(this.value)" checked="checked" ></label> <label> Kilograms <input type="radio" name="type" id="type" value="kilograms"  onclick="toggleWeightType(this.value)"></label>
	</div>
	</div>







	@if ($weights->count() > 1)

	{{ HTML::script('fw/chartjs/Chart.js'); }}


	<script>
	      
	      	var ctx = document.getElementById("myChart").getContext("2d");
			var data = {
				labels: {{ json_encode($y1) }},
				datasets: [
					{
						fillColor: "rgba(0,102,204,0.2)",
						strokeColor: "rgba(0,102,204,1)",
						pointColor: "rgba(0,68,170,1)",
						pointStrokeColor: "#fff",
						//tooltipXPadding: 12,
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: {{json_encode($datay1)}}
					},
					
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


			var ctxKilo = document.getElementById("myChartKilo").getContext("2d");
			var dataKilo = {
				labels: {{ json_encode($y1) }},
				datasets: [
					{
						fillColor: "rgba(0,102,204,0.2)",
						strokeColor: "rgba(0,102,204,1)",
						pointColor: "rgba(0,68,170,1)",
						pointStrokeColor: "#fff",
						//tooltipXPadding: 12,
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: {{json_encode($datay2)}}
					},
					
				]
			};
			var myLineChartKilo = new Chart(ctxKilo).Line(dataKilo, {
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
			
	</script>

	@endif

	<script>

	function toggleWeightType(toChange){
		$(".weights").hide();
		$("."+toChange).show();
		if(toChange == "pounds"){
			$("#myChartKilo").hide();
			$("#myChart").show();
		} else {
			$("#myChartKilo").show();
			$("#myChart").hide();
		}
	}

	function deleteWeight(id,obj){
		 $.ajax(
	        {
	            url : "/widgets/weight/"+id,
	            type: "DELETE",

	            success:function(data, textStatus, jqXHR) 
	            {
	                successMessage(data);
	                widgetsToReload.push("w_weights");
	                refreshWidgets();
	            },
	            error: function(jqXHR, textStatus, errorThrown) 
	            {
	                errorMessage(jqXHR.responseText);
	            },
	        });
	}

	</script>
@else
	{{ Messages::showEmptyMessage("WeightEmpty",$permissions["self"]) }}
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif

@if($total > $weights->count())
<div class="clearfix"></div>
	<div class="btmbuttonholder">
                <div class="clearfix"></div>
                	<span class="hrborder"></span>
                	<a href="javascript:void(0)" onclick="callWidget{{ Helper::getTypeOfCall($user) }}('w_weights',{{ $weights->count() }},{{ $user->id }},$(this))" class="greybtn">More Weights</a>
                </div>
@endif




