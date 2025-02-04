  @if ($measurements->count() > 0)

               
 <div class="bodymsrdetails">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
 <?php $lastMeasurement = null;  ?>                          
 @foreach ($measurements as $measurement)
  <?php if($lastMeasurement == null) $lastMeasurement = $measurement;  ?>   
                  <tr class="rowDelete">
                    <td width="70%">{{ Helper::date($measurement->recordDate) }}</td>
                    <td width="15%"><a href="javascript:void(0)" onclick="viewMeasurements({{ $measurement->id }})" >View</a></td>
                    <td width="15%" class="alignright"><a href="javascript:void(0)" onClick="return deleteMeasurement({{ $measurement->id }}, $(this));" class="deleteicon">deleteicon</a></td>
                  </tr>
@endforeach
                    </table>
                    </div>


                    
                    <?php $na_label = '<span style="color: gray;">N/A</span>'; ?>
                   


                    
                        <div class="bodydetails" id="w_body">
                       <div class="measure point1" id="_chest">{{ $lastMeasurement->chest }}</div>
                        <div class="measure point5" id="_bicepsLeft">{{ $lastMeasurement->bicepsLeft }}</div>
                        <div class="measure point6" id="_forearmLeft">{{ $lastMeasurement->forearmLeft }}</div>
                        <div class="measure point9" id="_waist">{{ $lastMeasurement->waist }}</div>
                        <div class="measure point10" id="_legsLeft">{{ $lastMeasurement->legsLeft }}</div>
                        <div class="measure point11" id="_calfLeft">{{ $lastMeasurement->calfLeft }}</div>
                        <div class="measure point13" id="_bicepsRight">{{ $lastMeasurement->bicepsRight }}</div>
                        <div class="measure point14" id="_forearmRight">{{ $lastMeasurement->forearmRight }}</div>
                        <div class="measure point15" id="_abdominals">{{ $lastMeasurement->abdominals }}</div>
                        <div class="measure point18" id="_legsRight">{{ $lastMeasurement->legsRight }}</div>
                        <div class="measure point19" id="_calfRight">{{ $lastMeasurement->calfRight }}</div>           
                        <img alt="body" src="/img/body.jpg">


                        </div>

                        <script>

    var measurements = {};
     @foreach ($measurements as $measurement)
        measurements[{{ $measurement->id }}] = {  
                            chest:'{{ $measurement->chest }}',
                            abdominals:'{{ $measurement->abdominals }}',
                            bicepsLeft:'{{ $measurement->bicepsLeft }}',
                            bicepsRight:'{{ $measurement->bicepsRight }}',
                            forearmLeft:'{{ $measurement->forearmLeft }}',
                            forearmRight:'{{ $measurement->forearmRight }}',
                            legsLeft:'{{ $measurement->legsLeft }}',
                            legsRight:'{{ $measurement->legsRight }}',
                            calfLeft:'{{ $measurement->calfLeft }}',
                            calfRight:'{{ $measurement->calfRight }}',
                            waist:'{{ $measurement->waist }}',
                    };
     @endforeach

     function viewMeasurements(id){
        $("#_chest").html(measurements[id].chest);
        $("#_abdominals").html(measurements[id].abdominals);
        $("#_bicepsLeft").html(measurements[id].bicepsLeft);
        $("#_bicepsRight").html(measurements[id].bicepsRight);
        $("#_forearmLeft").html(measurements[id].forearmLeft);
        $("#_forearmRight").html(measurements[id].forearmRight);
        $("#_legsLeft").html(measurements[id].legsLeft);
        $("#_calfLeft").html(measurements[id].calfLeft);
        $("#_calfRight").html(measurements[id].calfRight);
        $("#_waist").html(measurements[id].waist);
     }

    function deleteMeasurement(id,obj){
         $.ajax(
            {
                url : "/widgets/measurements/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_measurements");
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
    {{ Messages::showEmptyMessage("MeasurementsEmpty") }}
@endif

@if($total > $measurements->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget{{ Helper::getTypeOfCall($user) }}('w_measurements',{{ $measurements->count() }},{{ $user->id }},$(this))" class="greybtn">More Measurements</a>
                </div>
@endif

          
              
                <!-- bottom button holder -->
         
 