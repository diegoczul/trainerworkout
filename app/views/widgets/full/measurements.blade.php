@if($permissions["view"])  
@if ($measurements->count() > 0)

               
 <div class="bodymsrdetails">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabulardata">
 <?php $lastMeasurement = null;  ?>    
  <?php $laslastMeasurement = null;  ?>                          
 @foreach ($measurements as $measurement)
  <?php if($laslastMeasurement == null and $lastMeasurement != null) $laslastMeasurement = $measurement;  ?>   
  <?php if($lastMeasurement == null) $lastMeasurement = $measurement;  ?>   
   
                  <tr class="rowDelete">
                    <td width="70%">{{ Helper::date($measurement->recordDate) }}</td>
                    <td width="15%"><a href="javascript:void(0)" onclick="viewMeasurements({{ $measurement->id }},'{{ Helper::date($measurement->recordDate) }}')" >View</a></td>
                    <td width="15%" class="alignright"><a href="javascript:void(0)" onClick="return deleteMeasurement({{ $measurement->id }}, $(this));" class="deleteicon">deleteicon</a></td>
                  </tr>
@endforeach
                    </table>
                    <div style="display:none">
                    <h4>Comparing</h4>
                    <form action="" method="get" class="calendar">
                    	<fieldset class="clearfix">

                       	  <input type="text" id="date1" class="inputbox-middle datePickerRestricted" placeholder="Enter date" value="" />
                      </fieldset>
                      <fieldset class="clearfix">
                       	  <input type="text"  id="date2" class="inputbox-middle datePickerRestricted" placeholder="Enter date" value="" />
                      </fieldset>
                     

                        <a href="javascript:void(0)" class="bluebtn" onClick="return viewMeasurementsDif();" id="lnk-compare-measurement">Compare</a>

              
                    </form>
                    </div>
                </div>
    <div class="bdymsr fltleft marginleft">
                	
                     <?php $na_label = '<span style="color: gray;">N/A</span>'; ?>
                   

                @if($laslastMeasurement)
                    <div class="bodydetails" id="w_body_changes">
   
                    
                        <div class="bodydetails" id="w_body">
                       <div class="measure point1" id="__chest">{{ (($lastMeasurement->chest != 0) ? $lastMeasurement->chest- $laslastMeasurement->chest : 0) }}</div>
                        <div class="measure point5" id="__bicepsLeft">{{ (($lastMeasurement->bicepsLeft != 0) ? $lastMeasurement->bicepsLeft- $laslastMeasurement->bicepsLeft : 0 ) }}</div>
                        <div class="measure point6" id="__forearmLeft">{{ (($lastMeasurement->forearmLeft != 0) ? $lastMeasurement->forearmLeft- $laslastMeasurement->forearmLeft : 0 )}}</div>
                        <div class="measure point9" id="__waist">{{ (($lastMeasurement->waist != 0) ? $lastMeasurement->waist- $laslastMeasurement->waist : 0 ) }}</div>
                        <div class="measure point10" id="__legsLeft">{{ (($lastMeasurement->legsLeft != 0) ? $lastMeasurement->legsLeft- $laslastMeasurement->legsLeft : 0)  }}</div>
                        <div class="measure point11" id="__calfLeft">{{ (($lastMeasurement->calfLeft != 0) ? $lastMeasurement->calfLeft- $laslastMeasurement->calfLeft : 0 ) }}</div>
                        <div class="measure point13" id="__bicepsRight">{{ (($lastMeasurement->bicepsRight != 0) ? $lastMeasurement->bicepsRight- $laslastMeasurement->bicepsRight : 0)  }}</div>
                        <div class="measure point14" id="__forearmRight">{{ (($lastMeasurement->forearmRight != 0) ? $lastMeasurement->forearmRight- $laslastMeasurement->forearmRight : 0) }}</div>
                        <div class="measure point15" id="__abdominals">{{ (($lastMeasurement->abdominals != 0) ? $lastMeasurement->abdominals- $laslastMeasurement->abdominals : 0 ) }}</div>
                        <div class="measure point18" id="__legsRight">{{ (($lastMeasurement->legsRight != 0) ? $lastMeasurement->legsRight- $laslastMeasurement->legsRight : 0)  }}</div>
                        <div class="measure point19" id="__calfRight">{{ (($lastMeasurement->calfRight != 0) ? $lastMeasurement->calfRight- $laslastMeasurement->calfRight : 0)  }}</div>           
                        <img alt="body" src="/img/body.jpg">
                        
               	  		<div class="changes">Change since last measurement</div>
                  	</div>
                </div>
                @endif
                
                <div class="bdymsr fltright">
                	
                	
                     <div class="bodydetails" id="w_body_changes">
                    
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
                        
               	  		
                        <div class="changes" id="record_date">{{ Helper::date($lastMeasurement->recordDate) }}</div>
                  	</div>
                        
                </div>


                 <script>
    var availableDates = [];
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
                            id:'{{ $measurement->id }}',
                            recordDate:'{{ $measurement->recordDate }}'
                    };
        availableDates.push({{ Helper::date($measurement->recordDate) }});
     @endforeach

     function viewMeasurements(id,date){
        $("#record_date").html(date);
        $("#_chest").html(measurements[id].chest);
        $("#_abdominals").html(measurements[id].abdominals);
        $("#_bicepsLeft").html(measurements[id].bicepsLeft);
        $("#_bicepsRight").html(measurements[id].bicepsRight);
        $("#_forearmLeft").html(measurements[id].forearmLeft);
        $("#_forearmRight").html(measurements[id].forearmRight);
        $("#_legsLeft").html(measurements[id].legsLeft);
        $("#_legsRight").html(measurements[id].legsRight);
        $("#_calfLeft").html(measurements[id].calfLeft);
        $("#_calfRight").html(measurements[id].calfRight);
        $("#_waist").html(measurements[id].waist);
     }

     function viewMeasurementsDif(id){
        var id1 = findIdDate($("#date1").val());
        var id2 = findIdDate($("#date2").val());

        $("#__chest").html( (parseFloat(measurements[id].chest) - parseFloat( measurements[id].chest)).toFixed(1));
        $("#__abdominals").html( (parseFloat(measurements[id].abdominals) - parseFloat( measurements[id].abdominals)).toFixed(1));
        $("#__bicepsLeft").html( (parseFloat(measurements[id].bicepsLeft) - parseFloat( measurements[id].bicepsLeft)).toFixed(1));
        $("#__bicepsRight").html( (parseFloat(measurements[id].bicepsRight) - parseFloat( measurements[id].bicepsRight)).toFixed(1));
        $("#__forearmLeft").html( (parseFloat(measurements[id].forearmLeft) - parseFloat( measurements[id].forearmLeft)).toFixed(1));
        $("#__forearmRight").html( (parseFloat(measurements[id].forearmRight) - parseFloat( measurements[id].forearmRight)).toFixed(1));
        $("#__legsLeft").html( (parseFloat(measurements[id].legsLeft) - parseFloat( measurements[id].legsLeft)).toFixed(1));
        $("#__calfLeft").html( (parseFloat(measurements[id].calfLeft) - parseFloat( measurements[id].calfLeft)).toFixed(1));
        $("#__calfRight").html( (parseFloat(measurements[id].calfRight) - parseFloat( measurements[id].calfRight)).toFixed(1));
        $("#__waist").html( (parseFloat(measurements[id].waist) - parseFloat( measurements[id].waist)).toFixed(1));
     }

     function findIdDate(dat){
        var result = 0;
        for (var key in measurements) {
            if(dat == measurements[key].recordDate){
                result = key;
            }
        }
        return result;
     }

    function deleteMeasurement(id,obj){
         $.ajax(
            {
                url : "/widgets/measurements/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_measurements_full");
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
    {{ Messages::showEmptyMessage("MeasurementsEmpty",$permissions["self"]) }}
@endif
          

@if($total > $measurements->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_measurements_full',{{ $measurements->count() }},null,$(this))" class="greybtn">More Measurements</a>
                </div>
@endif
@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif

              
                <!-- bottom button holder -->