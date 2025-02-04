@extends('layouts.trainer')

@section('content')
<!-- content area starts here -->
    <section id="content" class="clearfix">
		<div class="wrapper">
            <div class="widgets fullwidthwidget shadow">
            <div class="fltright" style="margin-left:20px;"><a href="javascript:void(0)" onClick="toggle('w_addFriends');" class="bluebtn"><i class="fa fa-plus"></i>Add Friends</a></div>
                    <div class="topsearch advsearch">
                        <form>
                            <input type="text" class="srchinput" name="searchFriend" id="searchFriend" placeholder="Search" value="{{ $search }}"/>
                            
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
     <div class="clearfix"></div>
         </div>
                 <div class="widgets fullwidthwidget shadow" style="float:left">

            		<div class="topsearch advsearch">

                        <form id="searchform">

                            <input type="text" id="searchWorkout" class="srchinput" name="searchWorkout"  placeholder="advanced search" value="{{ $search }}" />

                            

                            <button type="button" class="srchbutton" onClick="searchForWorkout()">

                                <i class="fa  fa-search"></i>

                            </button>

                        </form>

                    </div>

            	<h1>Workout Market</h1>
                
                
                
           	 	<div id="w_workoutMarket" style="float:left">
              
                </div>
            

                

                

           
            </div>
         </div>
    </section>
@endsection

@section('scripts')


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


 function deleteFriends(id,obj){
                         $.ajax(
                            {
                                url : "/widgets/friends/"+id,
                                type: "DELETE",

                                success:function(data, textStatus, jqXHR) 
                                {
                                    successMessage(data);
                                    widgetsToReload.push("w_friends_full");
                                    refreshWidgets();
                                },
                                error: function(jqXHR, textStatus, errorThrown) 
                                {
                                    errorMessage(jqXHR.responseText);
                                },
                            });
                    }

  function followFriend(id,obj){
       $.ajax(
          {
              url : "/widgets/friends/addEdit",
              type: "POST",
              data: {
                        followingId:id
              },
              success:function(data, textStatus, jqXHR) 
              {

                  successMessage(data);
                  widgetsToReload.push("w_friends_full");
                  refreshWidgets();
              },
              error: function(jqXHR, textStatus, errorThrown) 
              {
                  errorMessage(jqXHR.responseText);
              },
          });
  }


function searchForWorkout(){
    $.ajax(
    {
        url: '{{ Lang::get('routes./Workouts/Search') }}',
        type: "POST",
        data: { search:$("#searchWorkout").val() },
        success:function(data, textStatus, jqXHR) 
        {
          $("#w_workoutMarket").html(data);

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

$(document).ready(function(){
    $("#searchFriend").keypress(function(e) {
        if(e.keyCode == 13) {
            e.preventDefault();
            searchForFriend();
        }
    });
});

$(document).ready(function(){
    $("#searchWorkout").keypress(function(e) {
        if(e.keyCode == 13) {
            e.preventDefault();
            searchForWorkout();
        }
    });
});


$(document).ready(function(){
  $("#searchForm").val('{{ $search }}');
  searchForFriend();
  searchForWorkout();
});
</script>


@endsection