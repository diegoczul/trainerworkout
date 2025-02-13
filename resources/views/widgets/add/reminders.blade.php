   {{ Form::open(array('url' => '/widgets/tasks/addEdit/')); }} 
   <!--Begin dropdownmenu--> 
       
      <div class="dashboardone addingreminder">
        <h1>Adding a Reminder</h1>
        {{ Form::hidden("userId",$user->id) }} 
          <fieldset>
          <p>Note of the reminder</p>
            <textarea type="text" class="extratextarea fullwidthinput" name="value" placeholder="Note" ></textarea>
            <input type="hidden" name="type" value="reminder" />
          </fieldset>
        
            <fieldset class="left">
            <input style="width:100px" type="text" class="mdwidthinput datepicker" name="dateStart" placeholder="Date"  />  <input style="width:100px" type="text" class="mdwidthinput time" name="timeStart" placeholder="Time" />
              </fieldset>
            <fieldset class="left">
         
            <!--<input type="text" class="smlwidthinput fltright" placeholder="Due Date (Optional)" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Due Date (Optional)'" />-->
          </fieldset>
        
          <fieldset class="left suggestionactive">
          <input type="text" class="fullwidthinput margintop" name="searchTaskTarget" id="searchReminderTarget" autocomplete="off" placeholder="Client’s name (optional)" />
          <input type="hidden" id="reminderTarget" name="taskTarget">
                <p id="resultsTargetReminder"></p>
               <div class="friendholder"></div>
          </fieldset>
          <div class="row clearfix">

            <div class="fltright">
              <fieldset>
                <input class="mdwidthinput" type="hidden" name="status" value="yes">
                <input name="" type="submit" value="save"  class="lightgreybtn ajaxSave" widget='w_tasks'>
              </fieldset>
            </div>
            
          </div>
          
   
       </div>
{{ Form::close() }}
<script>
     $(function() {

        $( "#searchReminderTarget" ).autocomplete({
                source: "/widgets/people/suggestWithClient",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#resultsTargetReminder").text("No results found");
                        $("#reminderTarget").val("");

                    }
                },
                select: function( event, ui ) {
                    $( "#searchReminderTarget" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#reminderTarget" ).val( ui.item.id );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#resultsTargetReminder").text("");
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
