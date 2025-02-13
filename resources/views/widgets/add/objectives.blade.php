{{ Form::open(array('url' => '/widgets/objectives/addEdit/')); }}
<div class="objectives">
 <textarea id="objective" name="objective" class="descriptionObjectives border-radius" placeholder="Objective"></textarea>
  <textarea id="measureable"  name="measureable" class="descriptionObjectives border-radius" placeholder="Measureable"></textarea>
                    <div class="objectivedetails clearfix">
                    	<ul>
                        	<li>Start date <?php echo date("y-m-d"); ?></li>
                            <li>End date  <input type="text" id="objectivedate_end" name="dateRecord"  class="inputbox-middle datepickerToday" readonly placeholder="Enter date" /></li>
                           
                            <li><input type="submit" class="bluebtn ajaxSave" value="Save" widget="w_objectives"></ul>
                        </ul>
                        
                    </div>
                    </div>
                    {{Form::close() }}