@php
    use App\Http\Libraries\Helper;
    use App\Http\Libraries\Messages;
@endphp
        <!-- This page shows the workouts in the page workouts.  -->


<?php $ids = 0; ?>
@if($permissions["view"])
    @if ($workouts->count() > 0)
        @foreach ($workouts as $workout)

                <?php $images = $workout->getExercisesImagesWidget(); ?>
                <?php $ids = $ids + 1; ?>

            <div id="empty_container<?php echo $ids; ?>" class="parentWorkout {{ ($workout->archived_at != "") ? "archived" : "" }}" onclick="showHover(this);">

                <div class="workout_main_container" id="workout_box<?php echo $ids; ?>">
                        <?php /*  <a href="/{{ $workout->getURL() }}"> */ ?>
                            <!--  <div class="upper_container"> -->
                    <!-- This div is the hover effect on each workout in the workouts page-->
                    <div class="workoutsHover">
                        <div class="workoutsHover_status">
                            <img src="{{asset('assets/img/exitPopup.svg')}}" class="hoverExit" onclick="showHover(this)">

                            @if($startFrom)
                                <a class="WorkoutsHoverView" href="/Client/editWorkout/{{ $client }}/{{ $workout->id }}">{{ Lang::get("content.SelectWorkout") }}</a>
                            @else
                                <a class="WorkoutsHoverView" href="/Client/AssignWorkout/{{ $client }}/{{ $workout->id }}">{{ Lang::get("content.SelectWorkout") }}</a>
                            @endif

                        </div>

                    </div>
                    <!-- The end of the hover div -->


                    <!-- This is the delete Portion that needs to replace the div with the class workout_overlay -->

                    <div class="deleting">
                        <img src="{{asset('assets/img/tw-gif.gif')}}">
                        <div class="deletingMessage">{{ Lang::get("content.deleting") }}</div>
                    </div>

                    <!-- end of the div -->

                    <div class="workout_overlay {{ $workout->status == 'Draft' ? 'draft' : '' }}">
                        <div class="exe_imgs">
                            <img src="{{ asset(Helper::image($images[0])) }}">
                            <img src="{{ asset(Helper::image($images[1])) }}">
                            <img src="{{ asset(Helper::image($images[2])) }}">
                        </div>
                        <span class="workout_title"
                              title="{{ $workout->name }}">{{ Helper::text($workout->name,70) }}</span>
                        <div class="workout_info">
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
                        <?php /*    </a> */ ?>
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
                            <img src="{{ asset(Helper::image($images[0])) }}">
                            <img src="{{ asset(Helper::image($images[1])) }}">
                            <img src="{{ asset(Helper::image($images[2])) }}">
                        </div>
                    </div>
                </div>
            </div><!-- End empty_container -->

        @endforeach
        {{ HTML::script(asset('assets/js/verify.notify.js')) }}
        <script>

            function showHover(object) {
                $(object).find(".workoutsHover").toggleClass("workout_main_containerAlways");
            }


            function hideHover() {
                $(this).css("display", "none");
            }


            var selectedItems = [];

            function putAllWorkoutsOnSelectMode(object, event) {

                $(".workoutsHover").each(function () {
                    $(this).addClass("workout_main_containerAlways");
                });
                var attr = $(object).find(".selectable").attr('selected');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $(object).find(".selectable").removeAttr("selected");
                    $(object).find(".selectable").attr("src", "/assets/img/selectableWorkoutIcon.svg");
                    selectedItems.splice($.inArray($(object).find(".selectable").attr("workoutid"), selectedItems), 1);
                } else {
                    var object2 = $(object).find(".selectable");
                    $(object).find(".selectable").attr('selected', "1");
                    $(object).find(".selectable").addClass('objectSelected');
                    object2.attr("src", "/assets/img/selectedWorkoutIcon.svg");
                    selectedItems.push($(object).find(".selectable").attr("workoutid"));
                }
                $(object).closest(".workoutsHover").css("display", "block");
                event.stopPropagation();

                if (selectedItems.length == 0) {
                    $(".workoutsHover").each(function () {
                        $(this).hide();
                        $(this).removeAttr("style");
                        showLess();
                        $(this).removeClass("workout_main_containerAlways");
                    });
                } else {
                    showMore();
                }
            }


            function showDuplicatePopup(obj) {
                div = obj.closest(".parentWorkout");
                div.find(".new_workout").show();
                form = obj.find("form");
                $(form).verify();
                return false;
            }

            function hideDuplicatePopup(object) {
                $(object).closest('.new_workout').hide();
            }


            function deleteWorkout(id, obj) {
                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {
                    showTopLoader();
                    $.ajax({
                        url: "/widgets/workouts/" + id,
                        type: "DELETE",

                        success: function (data, textStatus, jqXHR) {
                            successMessage(data);
                            hideTopLoader();
                            callWidget("w_workouts", null, null, null, {archive: '{{ ($archive) ? "true" : "false" }}'});
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            errorMessage(jqXHR.responseText);
                            hideTopLoader();
                        },
                    });
                }
            }


            function deleteWorkouts() {
                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {

                    count = $(".objectSelected").length;
                    $(".objectSelected").each(function (i) {
                        showTopLoader();
                        $.ajax({
                            url: "/widgets/workouts/" + $(this).attr("workoutid"),
                            type: "DELETE",

                            success: function (data, textStatus, jqXHR) {
                                successMessage(data);
                                if (!--count) {
                                    hideTopLoader();
                                    callWidget("w_workouts", null, null, null, {archive: '{{ ($archive) ? "true" : "false" }}'});
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                errorMessage(jqXHR.responseText);
                                hideTopLoader();
                                if (!--count) {
                                    widgetsToReload.push("w_workouts");
                                    refreshWidgets();
                                }
                            },
                        });
                    });
                }
            }


            function archiveWorkouts() {

                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {

                    count = $(".objectSelected").length;
                    $(".objectSelected").each(function (i) {
                        showTopLoader();
                        $.ajax({
                            url: "/widgets/workouts/archive/" + $(this).attr("workoutid"),
                            type: "post",

                            success: function (data, textStatus, jqXHR) {
                                successMessage(data);
                                if (!--count) {
                                    hideTopLoader();
                                    //widgetsToReload.push("w_workouts");
                                    //refreshWidgets();
                                    callWidget("w_workouts", null, null, null, {archive: '{{ ($archive) ? "true" : "false" }}'});

                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                errorMessage(jqXHR.responseText);
                                hideTopLoader();
                                if (!--count) {
                                    widgetsToReload.push("w_workouts");
                                    refreshWidgets();
                                }
                            },
                        });
                    });
                }
            }


            function unarchiveWorkout(id, obj) {
                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {
                    $(obj).closest(".loadingParent").find(".loading").show();
                    $.ajax({
                        url: "/widgets/workouts/unarchive/" + id,
                        type: "post",

                        success: function (data, textStatus, jqXHR) {
                            successMessage(data);
                            callWidget("w_workouts", null, null, null, {archive: '{{ ($archive) ? "true" : "false" }}'});
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            errorMessage(jqXHR.responseText);
                            $(".loading").hide();
                        },
                    });
                }
            }

            function archiveWorkout(id, obj) {
                if (confirm('{{ Lang::get("messages.Confirmation")  }}')) {
                    $(obj).closest(".loadingParent").find(".loading").show();
                    $.ajax({
                        url: "/widgets/workouts/archive/" + id,
                        type: "post",

                        success: function (data, textStatus, jqXHR) {
                            successMessage(data);
                            callWidget("w_workouts", null, null, null, {archive: '{{ ($archive) ? "true" : "false" }}'});
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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
        @endif
    @endif

@else
    {{ Messages::showEmptyMessage("NoPermissions") }}
@endif


<div class="viewMore_holder">

    @if(isset($countArchiveWorkouts) and $countArchiveWorkouts > 0)
        <a id="viewArchivedWorkouts" class="archive" href="javascript:void(0)" onClick="viewArchivedWorkouts()" style="{{ ($archive) ? "display: none" : ""; }}">{{ Lang::get("content.viewArchivedWorkouts") }}</a>
        <a id="viewUnArchivedWorkouts" class="archive" href="javascript:void(0)" onClick="viewUnArchivedWorkouts()" style="display: {{ ($archive) ? "inline-block" : "none" }}">{{ Lang::get("content.viewNotArchivedWorkouts") }}</a>
    @endif
    @if($total > $workouts->count())
        <a href="javascript:void(0)"
           onclick="callWidget('w_workouts',{{ $workouts->count()}},null,$(this),{archive: '{{ ($archive) ? "true" : "false" }}', search:'{{{ (isset($search) ? $search : "") }}}'})"
           class="viewMore">{{ Lang::get("content.ViewMore") }}</a>
    @endif

</div>

<script type="text/javascript">
    $(document).ready(function () {
        //get number of workouts on the page
        var $nbWorkouts = $(".parentWorkout ").length;
        //create variable for the html
        var $createWorkout = "";
        //check if the number of workout is inferior to 3
        if ($nbWorkouts < 3) {
            //create the html with the paramater for the class name differentiation
            function createWorkout(className, othClassName) {
                return '<link href="https://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet">' +
                    '<div class="onboardingCreateWorkoutContainer ' + othClassName + '">' +
                    '<a href="{{ Lang::get("routes./Trainer/CreateWorkout") }}" class="onbordingMessage ' +
                    className
                    + '">{{ Lang::get("content.CreateYourOwnWorkouts") }}</a>' +
                    '</div>';
            }

            //place the right className and load the html into the variable
            switch ($nbWorkouts) {
                case 0:
                    $createWorkout = createWorkout("noWorkout", "");
                    break;
                case 1:
                    $createWorkout = createWorkout("oneWorkout", "");
                    break;
                case 2:
                    $createWorkout = createWorkout("twoWorkout", "orderSwitch");
                    break;
            }
            //place the html at the right place
            $(".viewMore_holder").before($createWorkout);
        }
    });
</script>













