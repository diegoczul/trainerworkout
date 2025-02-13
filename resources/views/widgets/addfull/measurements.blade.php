{{ Form::open(array('url' => '/widgets/measurements/addEdit/')); }}
<div class="bodymsrdetails" style=" width:100%; padding-bottom:30px">

                    	<table width="30%" border="0" cellspacing="0" cellpadding="0" class="tabulardata" style="float:left">

                  <tr>
                    <td width="50%">Date</td>
                    <td width="35%"><input type="text" name="recordDate" id="recordDate" class="inputbox-middle datepicker" value="{{ date('Y-m-d') }}"></td>

                    <td width="15%" class="alignright"><input type="submit" class="bluebtn ajaxSave" value="Save" widget="w_measurements_full"></td>

                    
                  </tr>
  
 				<!-- <tr>
                    <td width="70%" class="alignright" colspan="3"><a class="bluebtn"  href="javascript:void(0)" onClick="return AddMeasurements($(this));"  id="lnk-compare-measurement">Save</a></td>
                  </tr>-->
  
                </table>
                    
                    
                    <?php $na_label = '<span style="color: gray;">N/A</span>'; ?>
                   <div class="bodydetails">
             
                    
                      {{ Form::hidden("userId",$user->id) }}   
                    	 <div class="point1"><input type="text" class="smallinput measure" value="" name="chest" /></div>
                       <!-- <div class="point2"><input type="text" class="smallinput measure" value="" name="neck" /></div> -->
                       <!-- <div class="point3"><input type="text" class="smallinput measure" value="" name="back" /></div> -->
                       <!-- <div class="point4"><input type="text" class="smallinput measure" value="" name="shoulderL" /></div> -->
                       <div class="point5"><input type="text" class="smallinput measure" value="" name="bicepsLeft" /></div>
                       <div class="point6"><input type="text" class="smallinput measure" value="" name="forearmLeft" /></div>
                       <!-- <div class="point7"><input type="text" class="smallinput measure" value="" name="gluteous" /></div> -->
                       <!-- <div class="point8"><input type="text" class="smallinput measure" value="" name="abductor" /></div> -->
                       <div class="point9"><input type="text" class="smallinput measure" value="" name="waist" /></div>
                       <div class="point10"><input type="text" class="smallinput measure" value="" name="legsLeft" /></div>
                       <div class="point11"><input type="text" class="smallinput measure" value="" name="calfLeft" /></div>
                       <!-- <div class="point12"><input type="text" class="smallinput measure" value="" name="shoulderR" /></div> -->
                       <div class="point13"><input type="text" class="smallinput measure" value="" name="bicepsRight" /></div>
                       <div class="point14"><input type="text" class="smallinput measure" value="" name="forearmRight" /></div>
                       <div class="point15"><input type="text" class="smallinput measure" value="" name="abdominals" /></div>
                       <!-- <div class="point16"><input type="text" class="smallinput measure" value="" name="adductor" /></div> -->
                       <!-- <div class="point17"><input type="text" class="smallinput measure" value="" name="hamstringR" /></div> -->
                       <div class="point18"><input type="text" class="smallinput measure" value="" name="legsRight" /></div>
                       <div class="point19"><input type="text" class="smallinput measure" value="" name="calfRight" /></div>
                        
                  
                        
               	  		<img src="/img/body.jpg" alt="body">
                  	</div>    </div>

                    {{ Form::close() }}
                <div class="clearfix"></div>
         