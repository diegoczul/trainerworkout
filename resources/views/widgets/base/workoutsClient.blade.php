<!-- This page shows the workouts in the page workouts.  -->



<?php $ids = 0; ?>
@if($permissions["view"])
 @if ($workouts->count() > 0)
    @foreach ($workouts as $workout)

<?php $images = $workout->getExercisesImagesWidget(); ?>
<?php $ids = $ids + 1; ?>

<div id="empty_container{{ $ids }}" class="parentWorkout {{ ($workout->archived_at != "") ? "archived" : "" }}"  onclick="showHover(this);">

<div class="workout_main_container clientView" id="workout_box{{ $ids }}">

    <div class="workoutsHover">
        <div class="workoutsHover_status">
        <img src="/img/exitPopup.svg" class="hoverExit" onclick="showHover(this)">

            <p title="{{ $workout->name }}">{{ Helper::text($workout->name,50) }}</p>

            <a class="WorkoutsHoverView" href="javascript:void(0)" onClick='window.location = "/{{ $workout->getURL() }}"'>{{ Lang::get("content.ViewWorkout") }}</a>

        </div>

        <div class="workoutsHover_BTNsContainer">

            <div class="workoutsHover">
                <div class="workoutsHover_status">
                    <img src="/img/exitPopup.svg" class="hoverExit" onclick="showHover(this)">
<!-- *****  Workout Name   ****** -->
                    <p title="{{ $workout->name }}">{{ Helper::text($workout->name,50) }}</p>
<!-- ******  View Workout  *****-->
                    <a class="WorkoutsHoverView" href="javascript:void(0)" onClick='window.location = "/{{ $workout->getURL() }}"'>{{ Lang::get("content.ViewWorkout") }}</a>
                </div>

                <div class="workoutsHover_BTNsContainer">
<!-- ******  Archive  ***** -->
                    <div class="workoutsHover_BTNContainer hvr-grow {{ ($workout->status == "Draft") ? "disableDiv" : "" }}" onclick="event.cancelBubble = true;">
                        <a href="javascript:void(0)" onClick="{{ ($workout->archived_at) ? "un" : "" }}archiveWorkout({{ $workout->id }}, $(this), ''); return false;" class="workoutsHoverBTN {{ ($workout->status == "Draft") ? "disableButton" : "" }}">
                            <div class="workoutsHover_BTNimg">
                                <img src="/img/svg/archive.svg">
                            </div>
                            <div class="workoutsHover_BTNtxt">
                                <span>{{ ($workout->archived_at) ? Lang::get("content.UnArchive") : Lang::get("content.Archive")  }}</span>
                            </div>
                        </a>
                    </div>
<!-- ******  Edit  ***** -->
                    <div class="workoutsHover_BTNContainer hvr-grow">
                        <a href="{{ $workout->getEditURL() }}" class="workoutsHoverBTN">
                            <div class="workoutsHover_BTNimg">
                                <img src="/img/editWorkoutIcon.svg">
                            </div>
                            <div class="workoutsHover_BTNtxt">
                                <span>{{ Lang::get("content.Edit") }}</span>
                            </div>
                        </a>
                    </div>

<!-- ******   Select   ***** -->
                    <div class="workoutsHover_BTNContainer hvr-grow" onclick="event.cancelBubble = true;">
                        <a href="javascript:void(0)" class="workoutsHoverBTN" onclick="putAllWorkoutsOnSelectMode(this,event);event.stopPropagation(); ">
                            <div class="workoutsHover_BTNimg">
                                <img workoutid="{{ $workout->id }}" class="selectable" src="/assets/img/selectableWorkoutIcon.svg">
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



    <!-- This is the delete Portion that needs to replace the div with the class workout_overlay -->

    <div class="deleting">
        <img src="{{asset('assets/img/tw-gif.gif')}}">
        <div class="deletingMessage">{{ Lang::get("content.deleting") }}</div>
    </div>

   <!-- end of the div -->

    <div class="workout_overlay">

        <div class="exe_imgs">
            <img src="/{{ Helper::image($images[0]) }}">
            <img src="/{{ Helper::image($images[1]) }}">
            <img src="/{{ Helper::image($images[2]) }}">
        </div>
        <span class="workout_title" title="{{ $workout->name }}">{{ Helper::text($workout->name,20) }}</span>
        <div class="workout_info" >
            <div class="workout_date">
                <span id="workourCreated">{{ Lang::get("content.Created") }} </span>
                <span>{{ Helper::date($workout->created_at) }}</span>
            </div>
            <div class="workout_status">
                @if($workout->status == "Draft")
                <span id="workoutInProgress">{{ Lang::get("content.WorkinProgress") }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="new_workout" style="display:none">
    <div class="workout_main_container" id="replacement_container">
        <div class="workout_overlay">
            <div id="duplicate_overlay" class="duplicate_container">
                <form method="post" enctype="multipart/encode" action="{{ Lang::get("routes./Workout/Duplicate") }}">
                    <label class="duplicate_workout_rename">{{ Lang::get("content.Renameyourworkout") }}</label>
                    <input type="text" class="duplicate_inputbox" value="" name="name" data-validate="required">
                    <input type="hidden" class="duplicate_inputbox" name="workoutId" value="{{ $workout->id }}">
                    <button type="submit">{{ Lang::get("content.SaveAs") }}</button>
                </form>
                <div class="duplicate_container_cancel" onclick="event.cancelBubble = true;">
                    <a href="javascript:void(0);" onclick="hideDuplicatePopup(this)">cancel</a>
                </div>
            </div>
        </div>
        <div class="duplicate_img">
            <img src="/{{ Helper::image($images[0]) }}">
            <img src="/{{ Helper::image($images[1]) }}">
            <img src="/{{ Helper::image($images[2]) }}">
        </div>
    </div>
</div>
</div><!-- End empty_container -->

@endforeach

{{ HTML::script('js/verify.notify.js'); }}


    <script>

function showHover(object) {
    $(object).find(".workoutsHover").toggleClass("workout_main_containerAlways");
}


function hideHover() {
    $(this).css("display", "none");
    // closest(".workoutsHover").removeClass("workout_main_containerAlways");
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


    function showDuplicatePopup(obj){
        div = obj.closest(".parentWorkout");
        div.find(".new_workout").show();
        form = obj.find("form");
        $(form).verify();
        return false;
    }

    function hideDuplicatePopup(object){
        $(object).closest('.new_workout').hide();
    }



    function deleteWorkout(id,obj){
        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){
        $(obj).closest(".loadingParent").find(".loading").show();
         $.ajax(
            {
                url : "/widgets/workouts/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    callWidget("w_workoutsClient",null,null,null,{userId: {{ $client->user->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});
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
            $(this).closest(".loadingParent").find(".loading").show();
            $.ajax(
                {
                    url : "/widgets/workouts/"+$(this).attr("workoutid"),
                    type: "DELETE",

                    success:function(data, textStatus, jqXHR)
                    {
                        successMessage(data);
                        if (!--count){
                            callWidget("w_workoutsClient",null,null,null,{client: {{ $client->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        errorMessage(jqXHR.responseText);
                        $(".loading").hide();
                        if (!--count){
                            callWidget("w_workoutsClient",null,null,null,{client: {{ $client->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});
                        }
                    },
                });
         });
        }
    }

     function archiveWorkouts(){

        if(confirm('{{ Lang::get("messages.Confirmation")  }}')){

        count = $(".objectSelected").length;
        $(".objectSelected").each( function(i) {
            $(this).closest(".loadingParent").find(".loading").show();
            $.ajax(
                {
                    url : "/widgets/workouts/archive/"+$(this).attr("workoutid"),
                    type: "post",

                    success:function(data, textStatus, jqXHR)
                    {
                        successMessage(data);
                        if (!--count){

                            //widgetsToReload.push("w_workouts");
                            //refreshWidgets();
                            callWidget("w_workoutsClient",null,null,null,{client: {{ $client->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        errorMessage(jqXHR.responseText);
                        $(".loading").hide();
                        if (!--count){
                            widgetsToReload.push("w_workoutsClient");
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
         $.ajax(
            {
                url : "/widgets/workouts/unarchive/"+id,
                type: "post",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    callWidget("w_workoutsClient",null,null,null,{client: {{ $client->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});
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
         $.ajax(
            {
                url : "/widgets/workouts/archive/"+id,
                type: "post",

                success:function(data, textStatus, jqXHR)
                {
                    successMessage(data);
                    callWidget("w_workoutsClient",null,null,null,{client: {{ $client->id }}, archive: '{{ ($archive) ? "true" : "false" }}'});
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

@else
    @if(isset($search) and $search != "")
    {{ Messages::showEmptyMessage("NothingFound",$permissions["self"]) }}
    @else
    {{ Messages::showEmptyMessage("WorkoutsEmptyClient",$permissions["self"]) }}
    @endif
@endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif


<div class="viewMore_holder here?">
    @if($countArchiveWorkouts > 0)
    <a id="viewArchivedWorkouts" class="archive" href="javascript:void(0)" onClick="viewArchivedWorkouts()"  style="{{ ($archive) ? "display: none" : ""; }}">{{ Lang::get("content.viewArchivedWorkouts") }}</a>
    <a id="viewUnArchivedWorkouts" class="archive"  href="javascript:void(0)" onClick="viewUnArchivedWorkouts()" style="display: {{ ($archive) ? "inline-block" : "none" }}">{{ Lang::get("content.viewNotArchivedWorkouts") }}</a>
    @endif
    @if($total > $workouts->count())
    <a href="javascript:void(0)" onclick="callWidget('w_workoutsClient',{{ $workouts->count()}},null,$(this),{archive: '{{ ($archive) ? "true" : "false" }}', client:{{{ $client->id }}},search:'{{{ (isset($search) ? addslashes($search) : "") }}}' })" class="viewMore">{{ Lang::get("content.ViewMore") }}</a>
    @endif
</div>















