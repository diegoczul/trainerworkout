{{ Form::open(array('url' => '/widgets/weight/addEdit/')); }}
 <div class="weighttable" style="width:100%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formdata">
               			<tr>
                          	<td colspan="2"><label> Pounds <input type="radio" name="type" id="type" value="pounds" checked="checked"></label> <label> Kilograms <input type="radio" name="type" id="type" value="kilograms"> </label></td>
                          </tr>
                            <tr>
                    			<td width="25%"><label>Date <input type="text" name="dateRecord" id="calendarWeight" class="datepickerPast inputbox-small" placeholder="Date" value="{{ date("Y-m-d") }}" /></label></td>
                    			<td width="55%"><label>Weight <input type="hidden" name="size" value="full" /> <input type="text" name="weight" id="txt-weight" value="" class="inputbox-small" placeholder="Weight" maxlength="7" style="width:70px"></label></td>
                   			 	
                            	<td  width="20%" class="alignright" ><input type="submit" class="bluebtn ajaxSave" value="Save" widget='w_weights_full'></td>
                            </tr>
            
                </table>
               </div>

{{Form::close() }}
