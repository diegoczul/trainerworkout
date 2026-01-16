@php
    use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
@endphp
<div class="workoutsContainer">
    @if (count($workouts) > 0)
        @foreach($workouts as $workout)
                <?php $images = $workout->getExercisesImagesWidget2(); ?>
            <div class="workout_main_container workoutContainer {{ ($workout->archived_at != "") ? "archived" : "" }}">
                <div class="workoutsHover">
                    <div class="workoutsHover_status">
                        <img src="{{asset('assets/img/exitPopup.svg')}}" class="hoverExit" onclick="showHover(this)">
                        <p title="{{ $workout->name }}">{{ Helper::text($workout->name,50) }}</p>
                        <a class="WorkoutsHoverView" href="javascript:void(0)" onClick='window.location = "/{{ $workout->getURL() }}"'>{{ Lang::get("content.ViewWorkout") }}</a>
                    </div>

                    <div class="workoutsHover_BTNsContainer">

                        <div class="workoutsHover">
                            <div class="workoutsHover_status">
                                <img src="{{asset('assets/img/exitPopup.svg')}}" class="hoverExit" onclick="showHover(this)">
                                <!-- *****  Workout Name   ****** -->
                                <p title="{{ $workout->name }}">{{ Helper::text($workout->name,50) }}</p>
                                <!-- ******  View Workout  *****-->
                                <a class="WorkoutsHoverView" href="javascript:void(0)" onClick='window.location = "/{{ $workout->getURL() }}"'>{{ Lang::get("content.ViewWorkout") }}</a>
                            </div>

                            <div class="workoutsHover_BTNsContainer">
                                <!-- ******  Edit  ***** -->
                                <div class="workoutsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="{{ $workout->getEditURL() }}" class="workoutsHoverBTN">
                                        <div class="workoutsHover_BTNimg">
                                            <img src="{{asset('/assets/img/editWorkoutIcon.svg')}}">
                                        </div>
                                        <div class="workoutsHover_BTNtxt">
                                            <span>{{ Lang::get("content.Edit") }}</span>
                                        </div>
                                    </a>
                                </div>
                                
                                <!-- ******  Archive  ***** -->
                                <div class="workoutsHover_BTNContainer hvr-grow {{ ($workout->status == "Draft") ? "disableDiv" : "" }}" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)" onClick="{{ ($workout->archived_at) ? "un" : "" }}archiveWorkout({{ $workout->id }}, $(this), ''); return false;" class="workoutsHoverBTN">
                                        <div class="workoutsHover_BTNimg">
                                            <img src="{{asset('/assets/img/svg/archive.svg')}}">
                                        </div>
                                        <div class="workoutsHover_BTNtxt">
                                            <span>{{ ($workout->archived_at) ? Lang::get("content.UnArchive") : Lang::get("content.Archive")  }}</span>
                                        </div>
                                    </a>
                                </div>

                                <!-- *****  Delete   ***** -->
                                <div class="workoutsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)" onClick="deleteWorkout({{ $workout->id }}, $(this), ''); return false;" class="workoutsHoverBTN">
                                        <div class="workoutsHover_BTNimg">
                                            <img src="{{asset('/assets/img/deleteWorkoutIcon.svg')}}">
                                        </div>
                                        <div class="workoutsHover_BTNtxt">
                                            <span>{{ Lang::get("content.Delete") }}</span>
                                        </div>
                                    </a>
                                </div>


                                <!-- ******   Select   ***** -->
                                <div class="workoutsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                                    <a href="javascript:void(0)" class="workoutsHoverBTN" onclick="putAllWorkoutsOnSelectMode(this,event);event.stopPropagation(); ">
                                        <div class="workoutsHover_BTNimg">
                                            <img workoutid="{{ $workout->id }}" class="selectable" src="{{asset('/assets/img/selectableWorkoutIcon.svg')}}">
                                        </div>
                                        <div class="workoutsHover_BTNtxt">
                                            <span>{{ Lang::get("content.Select") }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="deleting">
                    <img src="{{asset('assets/img/tw-gif.gif')}}">
                    <div class="deletingMessage">{{ Lang::get("content.deleting") }}</div>
                </div>
                <div class="workout">
                    <div class="workoutImages">
                        <img src="{{asset($images[0])}}" onerror="this.onerror=null;this.src='{{ asset('assets/img/client.png') }}';">
                        <img src="{{asset($images[1])}}" onerror="this.onerror=null;this.src='{{ asset('assets/img/client.png') }}';">
                        <img src="{{asset($images[2])}}" onerror="this.onerror=null;this.src='{{ asset('assets/img/client.png') }}';">
                    </div>
                    <div class="workoutInfo">
                        <h1 class="workout_title">{{{ Helper::text($workout->name,20) }}}</h1>

                        <p>{{{ Helper::date($workout->created_at) }}}</p>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="viewMore_holder">
            @if($workoutsTotal > count($workouts))
                <a href="javascript:void(0)" onclick="callWidget('w_workoutsTrainee',{{ count($workouts) }},null,$(this),{archive: '{{ ($archive) ? "true" : "false" }}', search:'{{{ (isset($search) ? $search : "") }}}'})" class="viewMore">{{ Lang::get("content.ViewMore") }}</a>
            @endif
        </div>
    @else
        <div class="trendingworkout" style="position:relative">
            {!! Messages::showEmptyMessage("TrendingWorkoutsEmptyTrainer") !!}
        </div>
    @endif

    @if(isset($countArchiveWorkouts) and $countArchiveWorkouts > 0)
        <div class="viewMore_holder">
            <a id="viewArchivedWorkouts" class="archive" href="javascript:void(0)" onClick="viewArchivedWorkouts()"  style="{{ ($archive) ? "display: none" : "" }}">{{ Lang::get("content.viewArchivedWorkouts") }}</a>
            <a id="viewUnArchivedWorkouts"  class="archive" href="javascript:void(0)" onClick="viewUnArchivedWorkouts()" style="display: {{ ($archive) ? "inline-block" : "none" }}">{{ Lang::get("content.viewNotArchivedWorkouts") }}</a>
        </div>
    @endif
</div>


<script>

    function viewArchivedWorkouts(){
        $("#viewUnArchivedWorkouts").show();
        $("#viewArchivedWorkouts").hide();
        callWidget("w_workoutsTrainee",null,null,null,{ archive:'true' });

    }

    function viewUnArchivedWorkouts(){
        $("#viewUnArchivedWorkouts").hide();
        $("#viewArchivedWorkouts").show();
        callWidget("w_workoutsTrainee",null,null,null,null);

    }

    function deleteWorkout(id,obj){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){
            showTopLoader();
            $.ajax({
                url : "/widgets/workouts/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    widgetsToReload.push("w_workoutsTrainee");
                    refreshWidgets();
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    errorMessage(jqXHR.responseText);
                    $(".loading").hide();
                },
            });
        }
    }

    function deleteWorkouts(){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){

            count = $(".objectSelected").length;
            $(".objectSelected").each( function(i) {
                showTopLoader();
                $.ajax({
                    url : "/widgets/workouts/"+$(this).attr("workoutid"),
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR)
                    {
                        successMessage(data);
                        if (!--count){
                            widgetsToReload.push("w_workoutsTrainee");
                            refreshWidgets();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        errorMessage(jqXHR.responseText);
                        $(".loading").hide();
                        if (!--count){
                            widgetsToReload.push("w_workouts");
                            refreshWidgets();
                        }
                    },
                });
            });
        }
    }

    var selectedItems = [];

    function putAllWorkoutsOnSelectMode(object,event){

        $(".workoutsHover").each(function(){
            $(this).addClass("workout_main_containerAlways");
        });
        var attr = $(object).find(".selectable").attr('selected');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(object).find(".selectable").removeAttr("selected");
            $(object).find(".selectable").attr("src","/assets/img/selectableWorkoutIcon.svg");
            selectedItems.splice( $.inArray($(object).find(".selectable").attr("workoutid"), selectedItems), 1 );
        } else {
            var object2 = $(object).find(".selectable");
            $(object).find(".selectable").attr('selected',"1");
            $(object).find(".selectable").addClass('objectSelected');
            object2.attr("src","/assets/img/selectedWorkoutIcon.svg");
            selectedItems.push($(object).find(".selectable").attr("workoutid"));
        }
        $(object).closest(".workoutsHover").css("display","block");
        event.stopPropagation();

        if(selectedItems.length == 0){
            $(".workoutsHover").each(function(){
                $(this).hide();
                $(this).removeAttr("style");
                showLess();
                $(this).removeClass("workout_main_containerAlways");
            });
        } else {
            showMore();
        }
    }

    function archiveWorkouts(){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){

            count = $(".objectSelected").length;
            $(".objectSelected").each( function(i) {
                $(this).closest(".loadingParent").find(".loading").show();
                $.ajax({
                    url : "/widgets/workouts/archive/"+$(this).attr("workoutid"),
                    type: "post",

                    success:function(data, textStatus, jqXHR)
                    {
                        successMessage(data);
                        if (!--count){
                            //widgetsToReload.push("w_workouts");
                            //refreshWidgets();
                            callWidget("w_workoutsTrainee",null,null,null,{archive: '{{ ($archive) ? "true" : "false" }}'});
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        errorMessage(jqXHR.responseText);
                        $(".loading").hide();
                        if (!--count){
                            widgetsToReload.push("w_workoutsTrainee");
                            refreshWidgets();
                        }
                    },
                });
            });
        }
    }

    function unarchiveWorkout(id,obj){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){
            $(obj).closest(".loadingParent").find(".loading").show();
            $.ajax({
                url : "/widgets/workouts/unarchive/"+id,
                type: "post",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    callWidget("w_workoutsTrainee",null,null,null,{archive: '{{ ($archive) ? "true" : "false" }}'});
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    errorMessage(jqXHR.responseText);
                    $(".loading").hide();
                },
            });
        }
    }

    function archiveWorkout(id,obj){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){
            $(obj).closest(".loadingParent").find(".loading").show();
            $.ajax({
                url : "/widgets/workouts/archive/"+id,
                type: "post",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    callWidget("w_workoutsTrainee",null,null,null,{archive: '{{ ($archive) ? "true" : "false" }}'});
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    errorMessage(jqXHR.responseText);
                    $(".loading").hide();
                },
            });
        }
    }

</script>
