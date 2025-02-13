@extends('layouts.'.strtolower($user->userType))

@section('content')
  <section id="content" class="clearfix">

		<div class="wrapper">

            <div class="widgets fullwidthwidget shadow" style="float:left">

            		<div class="topsearch advsearch">

                        <form id="searchform">

                            <input type="text" id="searchWorkout" class="srchinput" name="searchWorkout"  placeholder="advanced search" value="" />

                            

                            <button type="button" class="srchbutton" onClick="searchWorkout()">

                                <i class="fa  fa-search"></i>

                            </button>

                        </form>

                    </div>

            	<h1>Workout Market</h1>
                
                
                
           	 	<div id="w_workoutMarket_full" style="float:left">
                
                </div>
            

                

                

           
            </div>

        </div>

    </section>
@endsection

@section("scripts")
<script>
    $(document).ready(function(){
    $("#searchWorkout").keypress(function(e) {
        if(e.keyCode == 13) {
            e.preventDefault();
            searchForWorkout();
        }
    });
});

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

</script>
<script>callWidget("w_workoutMarket_full");</script> 
<script>$(document).ready(function(){ $("#m_workouts").addClass('current'); });</script>

@endsection
