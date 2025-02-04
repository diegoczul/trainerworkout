
      <div class="dashboardone addingclient">
        <h1>Adding a Client</h1>
        
         {{ Form::open(array('url' => Lang::get("routes./Trainer/addClient"),"class"=>"formholder clearfix", "style"=>"clear: both")); }}
               <div class="already suggestionactive">
            <h1>Already on TrainerWorkout</h1>
            <fieldset>
            <p class="addclient_label">Name</p>
              <input type="text" id="searchclient" name="searchclient" autocomplete="off"  placeholder="Name"  client='' />
              <input type="hidden" id="user" name="user">
                <p id="results"></p>
               <div class="friendholder"></div>
               <input type="submit" value="Invite" class="bluebtn ajaxSave" widget="w_clients">
            </fieldset>
            <div class="widgets suggestion border-radius" style="display:none;">
              
            </div>
          </div>
          <div class="new">
            <h1>New to TrainerWorkout</h1>
            <fieldset>
            <p class="addclient_label">First Name</p>
              <input type="text" name="firstName" placeholder="First Name" />
            </fieldset>
            <fieldset>
            <p class="addclient_label">Last Name</p>
              <input type="text" name="lastName" placeholder="Last Name"  />
            </fieldset>
            <fieldset>
            <p class="addclient_label">Email</p>
              <input type="text" placeholder="Email" name="email"  />
            </fieldset>
            <fieldset>
              <input type="submit" value="Invite" class="bluebtn ajaxSave" widget="w_clients">
            </fieldset>
               <input type="hidden" name="action" value="addclient" />
                <input type="hidden" name="invite" value="no" />
          </div>
       {{ Form::close() }}
        <div class="invitationsent ">
           
        </div>
      </div>


      <script>


   
     $(function() {
        
        $( "#searchclient" ).autocomplete({
                source: "/widgets/people/suggest",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#results").text("No results found");
                    }
                },
                select: function( event, ui ) {
                    $( "#searchclient" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#user" ).val( ui.item.id );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#results").text("");
            var image = "/img/holder.png";
            if(item.thumb != null){
                image = "/"+item.thumb;
            }
            return $( "<li style='cursor:pointer' class='clientinfo marginleftnone clearfix'>" )
            .append( "<a class='image fltleft'><img width:45; height:45; src='"+image+"'/></a><div class='detail'>" + item.firstName + "<br>" + item.lastName + "</div>")
            .appendTo( ul );
        };
    });

</script>