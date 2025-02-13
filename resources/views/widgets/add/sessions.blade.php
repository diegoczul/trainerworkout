{{ Form::open(array('url' => '/widgets/sessions/addEdit/')); }}
<div class="dashboardone addingsession">
        <h1>Adding a Session/Package</h1>
        
          <fieldset>
          <p>How will you name it?</p>
          
            <input type="text" class="fullwidthinput" placeholder="Session/ Package Name" name="name"  />
          </fieldset>
          <fieldset>
          <p>How many hours / session?</p>
            <input type="text" class="mdwidthinput" placeholder="minutes / Session" name="timePerSession"  /><span class="hours">mins</span>
          </fieldset>
          <fieldset>
            <input type="text" class="smlwidthinput" name="numberOfSessions" placeholder="Number of Sesions" />
          </fieldset>
          <fieldset>
          <p>Small description *<span style="font-size:9px;">optional</span></p>
            <textarea type="text" class="fullwidthinput extratextarea" name="description" placeholder="Description" ></textarea>
          </fieldset>
          <fieldset>
          <p>What is the price of the package?</p>
            <input type="text" class="smlwidthinput" placeholder="Package Price" name="price" /><span class="pricing">$</span>
          </fieldset>
          <fieldset>
            <input name="" type="submit" value="save"  class="lightgreybtn ajaxSave" widget='w_sessions'>
          </fieldset>
    
        </div>

{{Form::close() }}