@extends("layouts.trainer")

@section("header")
    {{ Helper::seo("clients") }}
@endsection


@section("content")
 <section id="content" class="clearfix">
    @if(Auth::user()->getNumberOfClients() > 6)
    <div class="searchContainer">
        <div class="searchWrapper">
            <h4>{{Lang::get("content.YourClients")}}</h4>
            <div class="searchField">
                <input id="client_search" name="client_search" placeholder="{{ Lang::get("content.search") }}" >
                <button onClick="searchClient()">{{ Lang::get("content.Search") }}</button>
            </div>
        </div>
    </div>
    @else
    <div class="exerciseHeaderNoSearch">

    </div>
    @endif
    <div class="wrapper">
    <div class="widget">
        <div class="workouts">
            <div class="workoutHeader">
                <div class="workoutHeader_description">
                    <h1>{{ Lang::get("content.MyClients") }}</h1>
                    <p>{{ Lang::get("content.Hereresidesallofyourconnectedclients") }}</p>
                </div>
                @if(Auth::user()->getNumberOfClients() > 0)
                <div class="workouts_options clients_options workouts_options_down">
                    <a title="Delete" href="javascript:void(0)" class="moreOptionsButton" id="deleteWorkouts" onclick="deleteClients()">
                        <svg width="13" height="18" viewBox="0 0 13 18" xmlns="https://www.w3.org/2000/svg">
                            <title>
                                Delete Icon
                            </title>
                            <g stroke-width=".5" stroke="#369AD8" fill="none" fill-rule="evenodd">
                                <g>
                                    <rect y="1.702" width="13" height="1.702" rx=".413"/></rect>
                                    <rect x="4.875" width="3.25" height="1.276" rx=".413"/></rect>
                                    <path d="M1.22 3.523c0-.23.182-.414.413-.414h9.734c.23 0 .414.187.414.413V16.35c0 .91-.74 1.65-1.65 1.65H2.87a1.65 1.65 0 0 1-1.65-1.65V3.523z"/></path>
                                </g>
                                <g stroke-linecap="square">
                                    <path d="M9.14 6.3v8.51M6.5 6.3v8.51M3.86 6.3v8.51"/></path>
                                </g>
                            </g>
                        </svg>
                    </a>
                    {{-- <a href="javascript:void(0)" class="lessOptionsWorkoutButton" id="moreOptions" onclick="showMore()"></a> --}}
                    <!-- <a href="{{ Lang::get("routes./Trainer/CreateWorkout") }}" id="createWorkout">{{ Lang::get("content.CreateNewWorkout") }}</a> -->
                    <!-- <p class="messageClients">{{ Lang::get("content.messageClientPage") }}</p> -->
                    <!-- <button class="addElementButton">+ Add Client</button> -->
                </div>
                @endif
                <a href="javascript:void(0)" class="addElementButton" id="createClient" onclick="lightBox();">{{ Lang::get("content.NewClient") }}</a>
            </div>
            <div id="w_clients">
                <!-- /widgets/base/clients.blade.php -->

            </div>
        </div>
    </div> <!-- End Widget -->
    </div> <!-- End Wrapper -->

</section>

@include('popups.newClient')



@endsection

@section("scripts")
<script type="text/javascript">
  onLoad="self.focus();document.exercise.name.focus()"
</script>

<script>callWidget("w_clients");</script>


<script type="text/javascript">

$(document).ready(function() {
    var text_max = 500;
    $('#textarea_counter').html(text_max + ' characters remaining');

    $('#description').keyup(function() {
        var text_length = $('#description').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_counter').html(text_remaining + ' characters remaining');
    });
});


$(document).ready(function(){ $(".chosen-container-multi").css("width", "100%")});



function searchClient(){
    $.ajax(
    {
        url: widgetsList["w_clients"],
        type: "POST",
        data: { search:$("#client_search").val() },
        success:function(data, textStatus, jqXHR)
        {
          $("#w_clients").html(data);

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


var selectedItems = [];

function putAllWorkoutsOnSelectMode(object,event){

    $(".clientsHover").each(function(){
        $(this).addClass("client_main_containerAlways");
    });
    var attr = $(object).find(".selectable").attr('selected');
    if (typeof attr !== typeof undefined && attr !== false) {
         $(object).find(".selectable").removeAttr("selected");
         $(object).find(".selectable").attr("src","/assets/img/selectableWorkoutIcon.svg");
        selectedItems.splice( $.inArray($(object).find(".selectable").attr("clientid"), selectedItems), 1 );
    } else {
        var object2 = $(object).find(".selectable");
        $(object).find(".selectable").attr('selected',"1");
        $(object).find(".selectable").addClass('objectSelected');
        object2.attr("src","/assets/img/selectedWorkoutIcon.svg");
        selectedItems.push($(object).find(".selectable").attr("workoutid"));
    }
    // $(object).closest(".clientsHover").css("display","block");
    event.stopPropagation();

    if(selectedItems.length == 0){
        $(".clientsHover").each(function(){
            $(this).hide();
            $(this).removeAttr("style");
            showLess();
            $(this).removeClass("client_main_containerAlways");
        });
    } else {
        showMore();
    }
}


$(document).ready(function(){
    $(".menu_clients").addClass("selected");
});


</script>




@endsection
