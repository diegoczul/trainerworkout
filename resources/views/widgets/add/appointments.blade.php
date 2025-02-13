     <!--Begin dropdownmenu--> 
        {{ Form::open(array('url' => 'widgets/appointments/addEdit')); }}
       <div class="dashboardone addingtask">
        <h1>Adding an Appointment</h1>
        
          <fieldset>
          	<p>What is your appointment about?</p>
            <input type="text" class="fullwidthinput" name="appointment" placeholder="New Appointment"  />
          </fieldset>
          <fieldset>
          <p>From</p>
            <input  style="width:100px" type="text" class="mdwidthinput datepicker" name="dateStart" placeholder="Date"  /> <input  style="width:100px" type="text" class="mdwidthinput time" name="timeStart" placeholder="Time" />
          </fieldset>
        
          <fieldset>
          <p>To</p>
            <input style="width:100px" type="text" class="mdwidthinput datepicker" name="dateEnd" placeholder="Date" /> <input style="width:100px" type="text" class="mdwidthinput time" name="timeEnd" placeholder="Time" />
          </fieldset>
           
          <fieldset class="suggestionactive">
          
            <input type="text" class="fullwidthinput margintop" name="searchAppointmentTarget" id="searchAppointmentTarget" autocomplete="off" placeholder="Client’s name (optional)" />
          <input type="hidden" id="appointmentTarget" name="appointmentTarget">
                <p id="resultsTarget"></p>
               <div class="friendholder"></div>


</fieldset>
          <fieldset>
              <input type="hidden" class="mdwidthinput" value="yes" name="status"  />
            <input name="" type="submit"  value="save" class="lightgreybtn ajaxSave" widget="w_appointments">
          </fieldset>
        </form>
       </div>
       {{ Form::close() }}
<script>
     $(function() {

        $( "#searchAppointmentTarget" ).autocomplete({
                source: "/widgets/people/suggestWithClient",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#resultsTarget").text("No results found");
                        $("#appointmentTarget").val("");

                    }
                },
                select: function( event, ui ) {
                    $( "#searchAppointmentTarget" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#appointmentTarget" ).val( ui.item.id );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#resultsTarget").text("");
            var image = "/img/default.gif";
            if(item.thumb != null){
                image = "/"+item.thumb;
            }
            return $( "<li style='cursor:pointer' class='clientinfo marginleftnone clearfix'>" )
            .append( "<a class='image fltleft'><img width:45; height:45; src='"+image+"'/></a><div class='detail'>" + item.firstName + "<br>" + item.lastName + "</div>")
            .appendTo( ul );
        };
    });


</script>
