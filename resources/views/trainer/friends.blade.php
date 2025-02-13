@extends("layouts.trainer")

@section("content")
 <section id="content" class="clearfix">
        <div class="wrapper">
            <div class="widwrapper clearfix">
                <div class="widgets fullwidthwidget shadow clearfix">
                    <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_add_clients')"  class="bluebtn"><i class="fa fa-plus"></i>Add Clients</a></div>
                  <h1>Clients</h1>
                           <div class="addclients clearfix">
                    <div id="w_add_clients" style="display:none">
                  {{ View::make("widgets.add.clients")}}  
                </div>
                    </div>
                    <div class="clients clearfix">
                        <div id="w_clients"></div>
                    </div>
                 
                  <!-- bottom button holder -->
                 
                </div>
                <!--Clients Feed-->

                   
                <div class="clearfix"></div>
            </div>

            <div class="widgets fullwidthwidget shadow">
            <div class="fltright" style="margin-left:20px;"><a href="javascript:void(0)" onClick="toggle('w_addFriends');" class="bluebtn"><i class="fa fa-plus"></i>Add Friends</a></div>
                    <div class="topsearch advsearch">
                        <form>
                            <input type="text" class="srchinput" name="searchFriend" id="searchFriend" placeholder="Search" value=""/>
                            
                            <button type="button" class="srchbutton" onClick="searchForFriend();" >
                                <!--<i class="fa fa-search"></i>-->
                                Search
                            </button>
                        </form>
                    </div>
                    
                <h1>People</h1>
                <!-- Friend starts here -->
                <div class="friend">
                <!-- friend-list holder -->
                <div id="w_addFriends" class="add">
                    {{ View::make("widgets.addfull.friends")}}
               </div>
                <div id="w_friends_full">

               </div>
                  
                </div>
                <br>
     
         </div>
    </section>
@endsection

@section("scripts")

<script>callWidget("w_friends_full");</script> 

<script>

function searchForFriend(){
    $.ajax(
    {
        url: '/Friends/Search',
        type: "POST",
        data: { search:$("#searchFriend").val() },
        success:function(data, textStatus, jqXHR) 
        {
          $("#w_friends_full").html(data);

        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
          errorMessage(jqXHR.responseText);
        },
        statusCode: {
          500: function() {
            if(jqXHR.responseText != ""){
              errorMessage(jqXHR.responseText);
            }else {
              
            }
              
          }
      }
    });
}
</script>



<script type="text/javascript">
$(document).ready(function(){ $("#m_friends").addClass('active'); });
</script>
<script>callWidget("w_clients");</script> 


@endsection