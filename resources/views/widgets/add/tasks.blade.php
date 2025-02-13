       <!--Begin dropdownmenu--> 
     {{ Form::open(array('url' => '/widgets/tasks/addEdit/')); }} 
       <div class="dashboardone addingtask">
        <h1>Adding a Task</h1>
        {{ Form::hidden("userId",$user->id) }} 
          <fieldset>
          <p>Note of the task</p>
            <input type="text" class="fullwidthinput extratextarea" name="value" placeholder="Note"  />
            <input type="hidden" name="type" value="task" />
          </fieldset>
          <fieldset>
          <p>Due date</p>
            <input style="width:100px;" type="text" class="mdwidthinput datepicker" name="dateStart" placeholder="Date" />  <input style="width:100px;" type="text" class="mdwidthinput time" name="timeStart" placeholder="Time" />
          </fieldset>
         <fieldset class="left">
         
            <!--<input type="text" class="smlwidthinput fltright" placeholder="Due Date (Optional)" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Due Date (Optional)'" />-->
          </fieldset>
          <fieldset class="suggestionactive">

            <input type="text" class="fullwidthinput margintop" name="searchTaskTarget" id="searchTaskTarget" autocomplete="off" placeholder="Client’s name (optional)" />
          <input type="hidden" id="taskTarget" name="taskTarget">
                <p id="resultsTargetTask"></p>
               <div class="friendholder"></div>
          

</fieldset>
          <fieldset>
              <input type="hidden" class="mdwidthinput" value="yes" name="status"  />
             <input name="" type="submit" value="save"  class="lightgreybtn ajaxSave" widget='w_tasks'>
          </fieldset>
      
       </div>
       {{ Form::close()}}
<script>
     $(function() {

        $( "#searchTaskTarget" ).autocomplete({
                source: "/widgets/people/suggestWithClient",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#resultsTargetTask").text("No results found");
                        $("#taskTarget").val("");

                    }
                },
                select: function( event, ui ) {
                    $( "#searchTaskTarget" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#taskTarget" ).val( ui.item.id );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#resultsTargetTask").text("");
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
