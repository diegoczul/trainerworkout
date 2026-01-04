@use(App\Http\Libraries\Helper)
@use(App\Http\Libraries\Messages)
@use(App\Models\ExercisesImages)
@extends('layouts.visitor')
@section('content')
    <?php
    $sum_ex = 0;
    $sum_sets = 0;
    $sum_time = 0;
    $sum_reps = 0;
    $sum_ex_2 = 0;
    $sum_sets_2 = 0;
    $sum_time_2 = 0;
    $sum_reps_2 = 0;

    ?>
    <section id="content" class="clearfix">
        <div class="Trainee">
            <div class="workoutHeaderContainer">
                <div class="workoutHeaderWrapper">
                    <div class="workoutHedaer">
                        <h1>{{ $workout->name }}</h1>
                        <!-- Message from the personal trainer about the workout -->
                        @if($workout->notes != "")
                            <div class="trainerWorkoutMessageContainer">
                                <div class="trainerWorkoutMessage">
                                    <p>{{ $workout->notes }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="workoutData">
                            <div class="workoutPT">
                                <img src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                <div class="workoutPTname">
                                    <p>{{{ $workout->author->firstName??"N/A" }}}</p>
                                    <p>{{{ $workout->author->lastName??"" }}}</p>
                                </div>
                            </div>
                            <div class="workoutDate">
                                <p>{{ Lang::get("content.received") }} </p>
                                <p>{{ Helper::date($workout->created_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Workout Legend -->
            <div class="lgContainer">
                <div class="lg-circuit">
                    <h4>{{{ Lang::get("content.Circuit") }}}</h4>
                </div>
                <div class="lg-cardio">
                    <h4>{{{ Lang::get("content.Cardio") }}}</h4>
                </div>
                <div class="lg-muscle">
                    <h4>{{{ Lang::get("content.Muscle") }}}</h4>
                </div>
            </div>

            <div class="wrapper">

                @foreach($groups as $group)
                    <?php
                        $exercises = $group->getExercises()->get();
                        $restTimeBetweenExercises = unserialize($group->restBetweenCircuitExercises);
                        $circuitExercisesCounter = 0;
                        $numberOfCircuitsInWorkout = 1;
                    ?>

                    @if(count($exercises) > 1 or $group->type == "circuit")
                        <!------------------------ CIRCUIT ------------------------>
                        <div class="exercise circuit circuitContainer">
                            <!-- <div class="circuitLine"></div> -->
                            <div class="exercise_Header">

                                <h2>Circuit
                                    # {{ $numberOfCircuitsInWorkout }} <?php $numberOfCircuitsInWorkout++ ?></h2>
                                <div class="exercise_Header_btw">
                                    <div class="exercise_Header_container circuitInfo">

                                        @if($group->circuitType == "emom")
                                            <!-- If this is a emom -->
                                            <div class="emom">
                                                <div class="circleInstruction">
                                                    <p class="emomMeasure">{{{ $group->emom }}}</p>
                                                    <span>minutes</span>
                                                </div>
                                                <span>EMOM</span>
                                            </div>

                                        @elseif($group->circuitType == "amrap")
                                            <!-- If this is AMRAP type circuit -->
                                            <div class="amrap">
                                                <div class="circleInstruction">
                                                    <p class="amrapMeasure">{{{ $group->maxTime }}}</p>
                                                    <span>minutes</span>
                                                </div>
                                                <span>AMRAP</span>
                                            </div>
                                            <!-- If this is EMOM type circuit -->
                                        @else
                                            <div class="nbrounds">
                                                <div class="circleInstruction">
                                                    <p class="roundsMeasure" style="color: #ffffff">{{{ $group->intervals }}}</p>
                                                    <span>rounds</span>
                                                </div>
                                            </div>

                                        @endif
                                    </div>
                                    <div class="exercise_Header_container">
                                        <div class="circuitDetails">
                                            @if($group->circuitType != "amrap")
                                                <h3>{{ $group->intervals }} X</h3>
                                            @endif

                                                <?php
                                                $images = $group->getExercisesImagesCircuit();
                                                ?>
                                            @if(count($images) > 0)
                                                <div class="circuitImg">
                                                    @foreach($images as $image)
                                                        <img src="/{{{ Helper::image($image) }}}">
                                                    @endforeach
                                                    @if(count($images) > 5)
                                                        <div class="extraDiv"></div>
                                                    @endif
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                                <?php $circuitCount = 1; ?>
                            @foreach($exercises as $exercise)

                                @if($exercise->exercises->bodygroupId != 18)

                                    <!-- -- MUSCLE Circuit -- -->
                                    <div class="cExercise" data-exercise-id="{{ $exercise->exercises->id }}">

                                        <!-- LEFT AREA WITH THE EXERCISE DESCRIPTION -->
                                        <div class="cExercise_header">
                                            <div class="cExercise_header_top">
                                                <div class="cExercise_header_icon">
                                                    <svg width="27" height="27" viewBox="0 0 27 27"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <title>
                                                            Play Icon
                                                        </title>
                                                        <g transform="translate(1 1)" fill="none"
                                                           fill-rule="evenodd">
                                                            <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5"
                                                                    r="12.5"/>
                                                            <path fill="#2C3E50" d="M19 12.5L8 20V5z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="cExercise_header_info">
                                                    <p>{{ $circuitCount }}
                                                        <span>/{{ count($exercises) }}</span></p>
                                                    @if($exercise->equipmentId != "" and $exercise->equipment)
                                                        <img class="equip_img"
                                                             src="/{{ $exercise->equipment->thumb }}">
                                                    @endif
                                                    <h5>{{ $exercise->exercises->name }}
                                                        @if($exercise->equipmentId != "" and $exercise->equipment)
                                                            {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="exerciseAITrainer" style="display: inline-block; vertical-align: top;">
                                                    <div class="spanContainer" onclick="openAITrainerChat(this);">
                                                        <span>AI<br>Trainer</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cExercise_header_bottom">


                                                @if($exercise->notes != "")
                                                    <div class="exerciseNote">
                                                        <div class="noteContainer" onclick="exerciseNote(this);">
                                                            <!-- Diego!! Need to link the trainer's profile image here -->
                                                            <img class="traineeNote"
                                                                 src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                            <span>1</span>
                                                        </div>
                                                        <div class="note">
                                                            <img class="exitNote"
                                                                 src="{{asset('assets/img/exitPopup.svg')}}"
                                                                 onclick="exerciseNote(this);">
                                                            <!-- Diego, here is the notes from trainer -->
                                                            <p name="noteToExercise"
                                                               class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                            <div class="traineePicContainer">
                                                                <!-- Diego!! Need to link the trainer's profile image here -->
                                                                <img class="trainerProfilePic"
                                                                     src="{{asset('/')}}/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="respExeContainer">
                                            <!-- EXERCISE IMAGE -->

                                            <div class="tabSwitcherParent exerciseVisualContainer">
                                                <div class="tabSwitcherContainer">
                                                        <?php $activeButton = "tabSelected"; $active = "showTab"; ?>
                                                    @if($exercise->exercises->image != "")
                                                            <?php $active = "images"; ?>
                                                    @endif
                                                    @if($exercise->exercises->video != "")
                                                            <?php $active = "video"; ?>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                            <?php $active = "youtube"; ?>
                                                    @endif
                                                    @if(($exercise->exercises->image != "" && $exercise->exercises->video != "") || ($exercise->exercises->video != "" && $exercise->exercises->youtube != "") || ($exercise->exercises->image != "" && $exercise->exercises->youtube != ""))
                                                        @if($exercise->exercises->image != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'images')"
                                                               class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                                <?php $activeButton = ""; $active = "images"; ?>
                                                        @endif
                                                        @if($exercise->exercises->video != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'video')"
                                                               class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                                <?php $activeButton = ""; $active = "video"; ?>
                                                        @endif
                                                        @if($exercise->exercises->youtube != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'youtube')"
                                                               class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                                <?php $activeButton = ""; $active = "youtube"; ?>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="exercise_image_container">
                                                    <div
                                                            class="tabs exerciseImageTab  {{ ($active == "images" ? "showTab" : "") }} imagesTab">
                                                        <a href="/{{ Helper::image($exercise->exercises->image) }}"
                                                           data-lightbox="ex_{{ $exercise->id }}"><img
                                                                    src="/{{ Helper::image($exercise->exercises->image) }}"
                                                                    alt="{{ $exercise->exercises->name }}"></a>
                                                        @if($exercise->exercises->image2 != "")
                                                            <a href="/{{ Helper::image($exercise->exercises->image2) }}"
                                                               data-lightbox="ex_{{ $exercise->id }}"><img
                                                                        src="/{{ Helper::image($exercise->exercises->image2) }}"
                                                                        alt="{{ $exercise->exercises->name }}"></a>
                                                        @endif
                                                    </div>
                                                    @if($exercise->exercises->video != "")
                                                        <div
                                                                class="tabs exerciseVideoTab videoTab {{ ($active == "video" ? "showTab" : "") }}">
                                                            <div class="exercise_video_container"
                                                                 style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                <video id="my-video" class="video-js" controls
                                                                       preload="auto"
                                                                       style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                       poster="/{{ Helper::image(null,"video") }}"
                                                                       data-setup="{}">
                                                                    <source src="/{{ $exercise->exercises->video}}"
                                                                            type='video/mp4'>
                                                                    <source src="MY_VIDEO.webm" type='video/webm'>
                                                                    <p class="vjs-no-js">To view this video please
                                                                        enable JavaScript, and consider upgrading to a
                                                                        web browser that
                                                                        <a href="http://videojs.com/html5-video-support/"
                                                                           target="_blank">supports HTML5 video</a>
                                                                    </p>
                                                                </video>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                        <div
                                                                class="tabs exerciseYoutubeTab youtubeTab {{ ($active == "youtube" ? "showTab" : "") }}">
                                                            <div class="exercise_video_container"
                                                                 style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                @if($exercise->exercises->youtube != "")
                                                                    <iframe id="ytplayer" type="text/html"
                                                                            width="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}"
                                                                            height="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                            src="https://www.youtube.com/embed/{{$exercise->exercises->youtube }}"></iframe>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="exeData">
                                                <div class="exeData_top">
                                                    @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                                        <div class="exeTempo">
                                                            <p>{{ Lang::get("content.Tempo") }}</p>
                                                            <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                                        </div>
                                                    @endif

                                                    <div class="unitSwitcherContainer">
                                                        <input type="hidden" id="exercise_units_{{ $exercise->id }}"
                                                               value="{{ $exercise->units }}"/>
                                                        <p>{{ Lang::get("content.Lbs") }}</p>
                                                        <label class="unitToggleLabel">
                                                            <input type="checkbox" class="unitToggleInput"
                                                                   onChange="changeUnits({{ $exercise->id }},this.value,this)"
                                                                   value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                                            <div class="unitToggleControl"></div>
                                                        </label>
                                                        <p>{{ Lang::get("content.Kg") }}</p>
                                                    </div>
                                                </div>
                                                <table>
                                                    <caption>muscle exercise</caption>
                                                    <thead>
                                                    <tr>
                                                        <th class="tbRound" scope="col">Set</th>
                                                        <th class="tbWeight"
                                                            scope="col">{{ Lang::get("content.Weight") }}</th>
                                                        <th class="tbRep" scope="col">
                                                            @if($exercise->metricVisual == "rep")
                                                                {{ Lang::get("content.Repetitions")  }}
                                                            @elseif($exercise->metricVisual == "time")
                                                                {{ Lang::get("content.Time")  }}
                                                            @elseif($exercise->metricVisual == "maxRep")
                                                                {{ Lang::get("content.maxRep")  }}
                                                            @elseif($exercise->metricVisual == "range")
                                                                {{ Lang::get("content.range")  }}
                                                            @else
                                                                {{ Lang::get("content.exerciseMode")  }}
                                                            @endif
                                                        </th>
                                                        <th class="tbMode">Mode</th>
                                                    </tr>
                                                    </thead>

                                                        <?php $sum_sets += $exercise->sets; ?>
                                                        <?php
                                                        $sets = $workout->getSets($exercise->id);
                                                        $sum_sets += $exercise->sets;
                                                        $counter = 0;
                                                        ?>

                                                    <tbody>
                                                    @foreach($sets as $set)
                                                        <tr>
                                                            <th scope="row">{{ Helper::setNumber($set->number,$set->workoutsExercises->sets) }}</th>
                                                            <td>
                                                                <span class="exercise_units_weight_{{ $exercise->id }}">{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}</span>&nbsp;<span
                                                                        class="exercise_units_weight_unit_{{ $exercise->id }}">Lbs</span>
                                                            </td>
                                                            @if(($exercise->metric == "time" || $set->metric == "time" || $set->metric == "temps") and ($set->metric != "maxRep" and $set->metric != "range"))
                                                                <td>{{ $set->reps }}<span> sec</span></td>
                                                            @else
                                                                <td>{{ $set->reps }}<span></span></td>
                                                            @endif

                                                            @if($set->metric == "range" || $set->metric == "rep")
                                                                <td>{{ Lang::get("content.reps") }}</td>
                                                            @else
                                                                <td>{{ $set->metric }}</td>
                                                            @endif
                                                        </tr>
                                                        @if($set->rest != "")
                                                            <tr>
                                                                <td class="restBtwSet"
                                                                    colspan="4">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextsets") }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <div style="margin-top: 10px">
                                                    <a href="{{route('trainee-invite-with-workout',['workout_id' => $workoutId])}}" class="bluebtn workout-bluebtn main-btn" style="padding: 5px">Create a FREE account and save your weights and workout</a>
                                                </div>
                                            </div>
                                        </div>


                                        @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                            <!-- Rest Between Exercise in Circuit -->
                                            <div class="circuitRestBtwExe">
                                                <svg width="27" height="27" viewBox="0 0 27 27"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <title>
                                                        Pause Icon
                                                    </title>
                                                    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                                        <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5"
                                                                r="12.5"/>
                                                        <path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/>
                                                    </g>
                                                </svg>
                                                <p>{{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                            </div>
                                        @endif
                                    </div>  <!-- End MUSCLE Circuit -->
                                        <?php $circuitExercisesCounter++; ?>
                                        <?php $circuitCount++; ?>
                                @else

                                    <!---- CARDIO  Circuit ---->
                                    <div class="cExercise">

                                        <!-- LEFT AREA WITH THE EXERCISE DESCRIPTION -->
                                        <div class="cExercise_header">
                                            <div class="cExercise_header_top">
                                                <div class="cExercise_header_icon">
                                                    <svg width="27" height="27" viewBox="0 0 27 27"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <title>
                                                            Play Icon
                                                        </title>
                                                        <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                                            <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5"
                                                                    r="12.5"/>
                                                            <path fill="#2C3E50" d="M19 12.5L8 20V5z"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                                <div class="cExercise_header_info">
                                                    <p>{{ $circuitCount }}
                                                        <span>/{{ count($exercises) }}</span></p>
                                                    @if($exercise->equipmentId != "" and $exercise->equipment)
                                                        <img class="equip_img" src="/{{ $exercise->equipment->thumb }}">
                                                    @endif
                                                    <h5>{{ $exercise->exercises->name }}
                                                        @if($exercise->equipmentId != "" and $exercise->equipment)
                                                            {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="cExercise_header_bottom">

                                                @if($exercise->notes != "")
                                                    <div class="exerciseNote">
                                                        <div class="noteContainer"
                                                             onclick="exerciseNote(this);">
                                                            <!-- Diego!! Need to link the trainer's profile image here -->
                                                            <img class="traineeNote"
                                                                 src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                            <span>1</span>
                                                        </div>
                                                        <div class="note">
                                                            <img class="exitNote"
                                                                 src="{{asset('assets/img/exitPopup.svg')}}"
                                                                 onclick="exerciseNote(this);">
                                                            <!-- Diego, here is the notes from trainer -->
                                                            <p name="noteToExercise"
                                                               class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                            <div class="traineePicContainer">
                                                                <!-- Diego!! Need to link the trainer's profile image here -->
                                                                <img class="trainerProfilePic"
                                                                     src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- EXERCISE IMAGE -->
                                        <div class="respExeContainer">
                                            <div class="tabSwitcherParent exerciseVisualContainer">
                                                <div class="tabSwitcherContainer">
                                                        <?php $activeButton = "tabSelected"; $active = "showTab"; ?>
                                                    @if($exercise->exercises->image != "")
                                                            <?php $active = "images"; ?>
                                                    @endif
                                                    @if($exercise->exercises->video != "")
                                                            <?php $active = "video"; ?>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                            <?php $active = "youtube"; ?>
                                                    @endif
                                                    @if(($exercise->exercises->image != "" && $exercise->exercises->video != "") || ($exercise->exercises->video != "" && $exercise->exercises->youtube != "") || ($exercise->exercises->image != "" && $exercise->exercises->youtube != ""))
                                                        @if($exercise->exercises->image != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'images')"
                                                               class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                                <?php $activeButton = ""; $active = "images"; ?>
                                                        @endif
                                                        @if($exercise->exercises->video != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'video')"
                                                               class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                                <?php $activeButton = ""; $active = "video"; ?>
                                                        @endif
                                                        @if($exercise->exercises->youtube != "")
                                                            <a href="javascript:void(0)"
                                                               onclick="tabSwitcher(this,'youtube')"
                                                               class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                                <?php $activeButton = ""; $active = "youtube"; ?>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="exercise_image_container">
                                                    <div class="tabs exerciseImageTab  {{ ($active == "images" ? "showTab" : "") }} imagesTab">
                                                        <a href="/{{ Helper::image($exercise->exercises->image) }}"
                                                           data-lightbox="ex_{{ $exercise->id }}"><img
                                                                    src="/{{ Helper::image($exercise->exercises->image) }}"
                                                                    alt="{{ $exercise->exercises->name }}"></a>
                                                        @if($exercise->exercises->image2 != "")
                                                            <a href="/{{ Helper::image($exercise->exercises->image2) }}"
                                                               data-lightbox="ex_{{ $exercise->id }}"><img
                                                                        src="/{{ Helper::image($exercise->exercises->image2) }}"
                                                                        alt="{{ $exercise->exercises->name }}"></a>
                                                        @endif
                                                    </div>
                                                    @if($exercise->exercises->video != "")
                                                        <div
                                                                class="tabs exerciseVideoTab videoTab {{ ($active == "video" ? "showTab" : "") }}">
                                                            <div class="exercise_video_container"
                                                                 style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                <video id="my-video" class="video-js" controls
                                                                       preload="auto"
                                                                       style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                       poster="/{{ Helper::image(null,"video") }}"
                                                                       data-setup="{}">
                                                                    <source src="/{{ $exercise->exercises->video}}"
                                                                            type='video/mp4'>
                                                                    <source src="MY_VIDEO.webm" type='video/webm'>
                                                                    <p class="vjs-no-js">To view this video please
                                                                        enable JavaScript, and consider upgrading to a
                                                                        web browser that
                                                                        <a href="http://videojs.com/html5-video-support/"
                                                                           target="_blank">supports HTML5 video</a>
                                                                    </p>
                                                                </video>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                        <div
                                                                class="tabs exerciseYoutubeTab youtubeTab {{ ($active == "youtube" ? "showTab" : "") }}">
                                                            <div class="exercise_video_container"
                                                                 style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                @if($exercise->exercises->youtube != "")
                                                                    <iframe id="ytplayer" type="text/html"
                                                                            width="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}"
                                                                            height="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                            src="https://www.youtube.com/embed/{{$exercise->exercises->youtube }}"
                                                                    ">?autoplay=1"
                                                                    frameborder="0"> </iframe>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="exeData">
                                                <div class="exeData_top">
                                                    <div class="unitSwitcherContainer">
                                                        <input type="hidden"
                                                               id="exercise_units_{{ $exercise->id }}"
                                                               value="{{ $exercise->units }}"/>
                                                        <p>mi</p>
                                                        <label class="unitToggleLabel">
                                                            <input type="checkbox" class="unitToggleInput"
                                                                   onChange="changeUnits({{ $exercise->id }},this.value,this)"
                                                                   value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                                            <div class="unitToggleControl"></div>
                                                        </label>
                                                        <p>km</p>
                                                    </div>
                                                </div>
                                                <table class="">
                                                    <caption>cardio exercise</caption>
                                                    <thead>
                                                    <tr>
                                                        <th class="tbInt" scope="col">
                                                            <p>{{ Lang::get("content.Interval") }}
                                                            </p></th>
                                                        <th class="tbHr" scope="col">
                                                            @if($exercise->metricVisual == "hr" || $exercise->metricVisual =='rep')
                                                                {{ Lang::get("content.hr") }}
                                                            @elseif($exercise->metricVisual == "effort")
                                                                {{ Lang::get("content.effort") }}
                                                            @elseif($exercise->metricVisual == "Vo2Max")
                                                                {{ Lang::get("content.Vo2Max") }}
                                                            @elseif($exercise->metricVisual == "reserve")
                                                                {{ Lang::get("content.reserve") }}
                                                            @elseif($exercise->metricVisual == "range")
                                                                {{ Lang::get("content.HRrange") }}
                                                            @elseif($exercise->metricVisual == "max")
                                                                {{ Lang::get("content.max") }}
                                                            @else
                                                                {{ Lang::get("content.exerciseMode")  }}
                                                            @endif
                                                        </th>
                                                        <th class="tbSpeed"
                                                            scope="col">{{ Lang::get("content.Speed") }}
                                                            <span
                                                                    class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span>
                                                        </th>
                                                        <th class="tbDist"
                                                            scope="col">{{ Lang::get("content.Distance") }}</th>
                                                        <th class="tbTime"
                                                            scope="col">{{ Lang::get("content.Time") }}</th>
                                                        <th class="tbMode">Mode</th>
                                                    </tr>
                                                    </thead>
                                                        <?php
                                                        $sets = $workout->getSets($exercise->id);
                                                        $sum_sets += $exercise->sets;
                                                        $counter = 0;
                                                        ?>
                                                    <tbody>
                                                    @foreach($sets as $set)
                                                        <tr>
                                                            <th scope="row">{{ Helper::setNumber($set->number,$set->workoutsExercises->sets)  }}</th>
                                                            <td>{{ ($set->bpm == "" || $set->bpm == "0" ? "-" : "$set->bpm")}}
                                                                <span>
                                    @if($set->metric == "Vo2Max" || $set->metric == "effort")
                                                                        %
                                                                    @elseif($set->metric == "reserve")
                                                                        bpm
                                                                    @elseif($set->metric == "range")
                                                                        bpm
                                                                    @else
                                                                        bpm
                                                                    @endif
                                    </span></td>
                                                            <td><span
                                                                        class="exercise_units_speed_{{ $exercise->id }}">{{ ($set->speed == "" || $set->speed == "0" ? "-" : $set->speed) }}</span><span
                                                                        class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span>
                                                            </td>
                                                            <td><span
                                                                        class="exercise_units_distance_{{ $exercise->id }}">{{ $set->distance == "" || $set->distance == "0" ? "-" : $set->distance }}</span><span
                                                                        class="exercise_units_distance_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span>
                                                            </td>
                                                            <td>{{ ($set->time == "" || $set->time == "0" ? "-" : "$set->time")}}
                                                                <span> min</span></td>
                                                            <!-- <td>{{ $set->metric }}</td> -->
                                                            <td>
                                                                @if($set->metric == "hr" || $set->metric=='rep')
                                                                    {{ Lang::get("content.hr") }}
                                                                @elseif($set->metric == "effort")
                                                                    {{ Lang::get("content.effort") }}
                                                                @elseif($set->metric == "Vo2Max")
                                                                    {{ Lang::get("content.Vo2Max") }}
                                                                @elseif($set->metric == "reserve")
                                                                    {{ Lang::get("content.reserve") }}
                                                                @elseif($set->metric == "range")
                                                                    {{ Lang::get("content.HRrange") }}
                                                                @else
                                                                    {{ Lang::get("content.max") }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @if($set->rest != "")
                                                            <tr>
                                                                <td class="restBtwSet"
                                                                    colspan="6">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <div style="margin-top: 10px">
                                                    <a href="{{route('trainee-invite-with-workout',['workout_id' => $workoutId])}}" class="bluebtn workout-bluebtn main-btn" style="padding: 5px">Create a FREE account and save your weights and workout</a>
                                                </div>
                                            </div>
                                        </div>

                                        @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                            <!-- Rest Between Exercise in Circuit -->
                                            <div class="circuitRestBtwExe">
                                                <svg width="27" height="27" viewBox="0 0 27 27"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <title>
                                                        Pause Icon
                                                    </title>
                                                    <g transform="translate(1 1)" fill="none"
                                                       fill-rule="evenodd">
                                                        <circle stroke="#2C3E50" fill="#FFF" cx="12.5"
                                                                cy="12.5" r="12.5"/>
                                                        </circle>
                                                        <path fill="#2C3E50"
                                                              d="M7 6h4v13H7zM14 6h4v13h-4z"/>
                                                        </path>
                                                    </g>
                                                </svg>
                                                <p>{{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                            </div>
                                        @endif
                                    </div>  <!-- End CARDIO  Circuit -->
                                        <?php $circuitExercisesCounter++; ?>
                                        <?php $circuitCount++; ?>
                                @endif
                            @endforeach


                            <!-- Rewind Icons end of Circuit -->
                            <div class="endCircuit">
                                <svg width="27" height="27" viewBox="0 0 27 27"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <title>
                                        Rewind Icon
                                    </title>
                                    <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                        <circle stroke="#2C3E50" stroke-width=".926" fill="#FFF" cx="12.5"
                                                cy="12.5" r="12.5"/>
                                        </circle>
                                        <path
                                                d="M19.136 12.963c0-.815-.158-1.595-.445-2.313-.975-2.438-3.448-4.17-6.344-4.17A6.95 6.95 0 0 0 7.84 8.115m-2.284 4.85c0 3.58 3.04 6.48 6.79 6.48 1.332 0 3.623-1 3.623-1"
                                                stroke="#2C3E50" stroke-width="1.852"/>
                                        </path>
                                        <path fill="#2C3E50"
                                              d="M20.103 16.754l.96-5.646-5.055 1.642zM6.354 8.118L3.18 12.886l5.287.555z"/>
                                        </path>
                                    </g>
                                </svg>
                                @if($group->circuitType == "emom")
                                    <p>{{ Lang::get("content.Rest for the remainder of the minute") }}</p>
                                @elseif($group->rest == "")
                                    <p>{{ Lang::get("content.donextround") }}</p>
                                @else
                                    <p> {{ $group->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</p>
                                @endif
                            </div>


                        </div>  <!-- End CIRCUIT -->
                    @else
                        @foreach($exercises as $exercise)
                            @if($exercise->exercises->bodygroupId != 18)
                                <!------------------------ MUSCLE EXERCISE ------------------------>
                                <div class="exercise muscle" exercise="">
                                    <div class="exercise_Header">
                                        <div class="exercise_Header_imgContainer">
                                            @if($exercise->equipmentId != "" and $exercise->equipment)
                                                <img class="equip_img" onerror="this.src = '{{asset('assets/img/placeholder.jpg')}}'" src="/{{ $exercise->equipment->thumb }}">
                                            @endif
                                        </div>
                                        <h5>{{ $exercise->exercises->name }}
                                            @if(isset($exercise->equipmentId) && !empty($exercise->equipmentId) && isset($exercise->equipment) && !empty($exercise->equipment))
                                                {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                            @endif
                                        </h5>
                                    </div>

                                    <!-- EXERCISE DESCRIPTION -->
                                    <div class="exeInfo">


                                        <!-- Trainee view notes -->

                                        @if($exercise->notes != "")
                                            <div class="exerciseNote">
                                                <div class="noteContainer" onclick="exerciseNote(this);">
                                                    <!-- Diego!! Need to link the trainer's profile image here -->
                                                    <img class="traineeNote"
                                                         src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                    <span>1</span>
                                                </div>
                                                <div class="note">
                                                    <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}"
                                                         onclick="exerciseNote(this);">
                                                    <!-- Diego, here is the notes from trainer -->
                                                    <p name="noteToExercise"
                                                       class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                    <div class="traineePicContainer">
                                                        <!-- Diego!! Need to link the trainer's profile image here -->
                                                        <img class="trainerProfilePic"
                                                             src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- ///////////////// -->


                                        <div class="unitSwitcherContainer">
                                            <input type="hidden" id="exercise_units_{{ $exercise->id }}"
                                                   value="{{ $exercise->units }}"/>
                                            <p>{{ Lang::get("content.Lbs") }}</p>
                                            <label class="unitToggleLabel">
                                                <input type="checkbox" class="unitToggleInput"
                                                       onChange="changeUnits({{ $exercise->id }},this.value,this)"
                                                       value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                                <div class="unitToggleControl"></div>
                                            </label>
                                            <p>{{ Lang::get("content.Kg") }}</p>
                                        </div>

                                    </div>

                                    <div class="respExeContainer">
                                        <!-- EXERCISE IMAGE -->

                                        <div class="tabSwitcherParent exerciseVisualContainer">
                                            <div class="tabSwitcherContainer">
                                                    <?php $activeButton = "tabSelected"; $active = "showTab"; ?>
                                                @if($exercise->exercises->image != "")
                                                        <?php $active = "images"; ?>
                                                @endif
                                                @if($exercise->exercises->video != "")
                                                        <?php $active = "video"; ?>
                                                @endif
                                                @if($exercise->exercises->youtube != "")
                                                        <?php $active = "youtube"; ?>
                                                @endif
                                                @if(($exercise->exercises->image != "" && $exercise->exercises->video != "") || ($exercise->exercises->video != "" && $exercise->exercises->youtube != "") || ($exercise->exercises->image != "" && $exercise->exercises->youtube != ""))
                                                    @if($exercise->exercises->image != "")
                                                        <a href="javascript:void(0)"
                                                           onclick="tabSwitcher(this,'images')"
                                                           class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                            <?php $activeButton = ""; $active = "images"; ?>
                                                    @endif
                                                    @if($exercise->exercises->video != "")
                                                        <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')"
                                                           class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                            <?php $activeButton = ""; $active = "video"; ?>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                        <a href="javascript:void(0)"
                                                           onclick="tabSwitcher(this,'youtube')"
                                                           class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                            <?php $activeButton = ""; $active = "youtube"; ?>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="exercise_image_container">
                                                <div
                                                        class="tabs exerciseImageTab  {{ ($active == "images" ? "showTab" : "") }} imagesTab">
                                                    <a href="/{{ Helper::image($exercise->exercises->image) }}"
                                                       data-lightbox="ex_{{ $exercise->id }}"><img
                                                                src="/{{ Helper::image($exercise->exercises->image) }}"
                                                                alt="{{ $exercise->exercises->name }}"></a>
                                                    @if($exercise->exercises->image2 != "")
                                                        <a href="/{{ Helper::image($exercise->exercises->image2) }}"
                                                           data-lightbox="ex_{{ $exercise->id }}"><img
                                                                    src="/{{ Helper::image($exercise->exercises->image2) }}"
                                                                    alt="{{ $exercise->exercises->name }}"></a>
                                                    @endif
                                                </div>
                                                @if($exercise->exercises->video != "")
                                                    <div
                                                            class="tabs exerciseVideoTab videoTab {{ ($active == "video" ? "showTab" : "") }}">
                                                        <div class="exercise_video_container"
                                                             style="background-color: #000; max-width:450px; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                            <video id="my-video" class="video-js" controls
                                                                   preload="auto"
                                                                   style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"

                                                                   poster="/{{ Helper::image(null,"video") }}"
                                                                   data-setup="{'fluid': true}">
                                                                <source
                                                                        src="/{{ $exercise->exercises->video}}"
                                                                        type='video/mp4'>
                                                                <source src="MY_VIDEO.webm"
                                                                        type='video/webm'>
                                                                <p class="vjs-no-js">
                                                                    To view this video please enable
                                                                    JavaScript, and consider upgrading to a
                                                                    web browser that
                                                                    <a href="http://videojs.com/html5-video-support/"
                                                                       target="_blank">supports HTML5
                                                                        video</a>
                                                                </p>
                                                            </video>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($exercise->exercises->youtube != "")
                                                    <div
                                                            class="tabs exerciseYoutubeTab youtubeTab {{ ($active == "youtube" ? "showTab" : "") }}">
                                                        <div class="exercise_video_container"
                                                             style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                            @if($exercise->exercises->youtube != "")
                                                                <iframe id="ytplayer" type="text/html"
                                                                        width="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}"
                                                                        height="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                        src="https://www.youtube.com/embed/{{$exercise->exercises->youtube }}"
                                                                ">?autoplay=1" frameborder="0"> </iframe>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- ---- EXERCISE DATA ---- -->

                                        <div class="exeData">
                                            <!-- EXERCISE TEMPO -->

                                            @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                                <div class="exeTempo">
                                                    <p>{{ Lang::get("content.Tempo") }}</p>
                                                    <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                                    <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                                    <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                                    <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                                </div>
                                            @endif

                                            <table class="exeData_table">
                                                <caption>muscle exercise</caption>
                                                <thead>
                                                <tr>
                                                    <th class="tbSet"
                                                        scope="col">{{ Lang::get("content.Set") }}</th>
                                                    <th class="tbWeight"
                                                        scope="col">{{ Lang::get("content.Weight") }}</th>
                                                    <th class="tbRep" scope="col">
                                                        @if($exercise->metricVisual == "rep")
                                                            {{ Lang::get("content.Repetitions")  }}
                                                        @elseif($exercise->metricVisual == "time")
                                                            {{ Lang::get("content.Time")  }}
                                                        @elseif($exercise->metricVisual == "maxRep")
                                                            {{ Lang::get("content.maxRep")  }}
                                                        @elseif($exercise->metricVisual == "range")
                                                            {{ Lang::get("content.range")  }}
                                                        @else
                                                            {{ Lang::get("content.exerciseMode")  }}
                                                        @endif

                                                    </th>
                                                    <th class="tbMode">{{ Lang::get("content.Mode") }}</th>
                                                </tr>
                                                </thead>

                                                    <?php $sum_sets += $exercise->sets; ?>
                                                    <?php $counter = 0; ?>
                                                    <?php $sets = $workout->getSets($exercise->id); ?>
                                                    <?php $allDone = 1; ?>

                                                <tbody>
                                                @foreach($sets as $set)
                                                    <tr>
                                                        <th scope="row">{{ Helper::setNumber($set->number,$set->workoutsExercises->sets) }}</th>
                                                        <td><span
                                                                    class="exercise_units_weight_{{ $exercise->id }}">{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}</span>&nbsp;<span
                                                                    class="exercise_units_weight_unit_{{ $exercise->id }}"> Lbs</span>
                                                        </td>
                                                        @if(($exercise->metric == "time" || $set->metric == "time" || $set->metric == "temps") and ($set->metric != "maxRep" and $set->metric != "range"))
                                                            <td>{{ $set->reps }}<span> sec</span></td>
                                                        @else
                                                            <td>{{ $set->reps }}<span></span></td>
                                                        @endif

                                                        @if($set->metric == "range" || $set->metric == "rep")
                                                            <td>{{ Lang::get("content.reps") }}</td>
                                                        @else
                                                            <td>{{ $set->metric }}</td>
                                                        @endif
                                                        <!--<td>{{ $set->metric }}</td>-->
                                                    </tr>
                                                    @if($set->rest != "")
                                                        <tr>
                                                            <td class="restBtwSet"
                                                                colspan="4">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextsets") }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div style="margin-top: 10px">
                                                <a href="{{route('trainee-invite-with-workout',['workout_id' => $workoutId])}}" class="bluebtn workout-bluebtn main-btn" style="padding: 5px">Create a FREE account and save your weights and workout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>   <!-- End MUSCLE EXERCISE -->
                            @else
                                <!------------------------ CARDIO EXERCISE ------------------------>
                                <div class="exercise cardio" exercise="">
                                    <div class="exercise_Header">
                                        <div class="exercise_Header_imgContainer">
                                            @if($exercise->equipmentId != "" and $exercise->equipment)
                                                <img class="equip_img" onerror="this.src = '{{asset('assets/img/placeholder.jpg')}}'" src="/{{ $exercise->equipment->thumb }}">
                                            @endif
                                        </div>
                                        <h5>{{ $exercise->exercises->name }}
                                            @if(isset($exercise->equipmentId) && !empty($exercise->equipmentId) && isset($exercise->equipment) && !empty($exercise->equipment))
                                                {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="exeInfo">


                                        @if($exercise->notes != "")
                                            <div class="exerciseNote">
                                                <div class="noteContainer" onclick="exerciseNote(this);">
                                                    <!-- Diego!! Need to link the trainer's profile image here -->
                                                    <img class="traineeNote"
                                                         src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                    <span>1</span>
                                                </div>
                                                <div class="note">
                                                    <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}"
                                                         onclick="exerciseNote(this);">
                                                    <!-- Diego, here is the notes from trainer -->
                                                    <p name="noteToExercise"
                                                       class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                    <div class="traineePicContainer">
                                                        <!-- Diego!! Need to link the trainer's profile image here -->
                                                        <img class="trainerProfilePic"
                                                             src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="unitSwitcherContainer">
                                            <input type="hidden" id="exercise_units_{{ $exercise->id }}"
                                                   value="{{ $exercise->units }}"/>
                                            <p>mi</p>
                                            <label class="unitToggleLabel">
                                                <input type="checkbox" class="unitToggleInput"
                                                       onChange="changeUnits({{ $exercise->id }},this.value,this)"
                                                       value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                                <div class="unitToggleControl"></div>
                                            </label>
                                            <p>km</p>
                                        </div>
                                    </div>

                                    <div class="respExeContainer">
                                        <div class="tabSwitcherParent exerciseVisualContainer">
                                            <div class="tabSwitcherContainer">
                                                    <?php $activeButton = "tabSelected"; $active = "showTab"; ?>
                                                @if($exercise->exercises->image != "")
                                                        <?php $active = "images"; ?>
                                                @endif
                                                @if($exercise->exercises->video != "")
                                                        <?php $active = "video"; ?>
                                                @endif
                                                @if($exercise->exercises->youtube != "")
                                                        <?php $active = "youtube"; ?>
                                                @endif
                                                @if(($exercise->exercises->image != "" && $exercise->exercises->video != "") || ($exercise->exercises->video != "" && $exercise->exercises->youtube != "") || ($exercise->exercises->image != "" && $exercise->exercises->youtube != ""))
                                                    @if($exercise->exercises->image != "")
                                                        <a href="javascript:void(0)"
                                                           onclick="tabSwitcher(this,'images')"
                                                           class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                            <?php $activeButton = ""; $active = "images"; ?>
                                                    @endif
                                                    @if($exercise->exercises->video != "")
                                                        <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')"
                                                           class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                            <?php $activeButton = ""; $active = "video"; ?>
                                                    @endif
                                                    @if($exercise->exercises->youtube != "")
                                                        <a href="javascript:void(0)"
                                                           onclick="tabSwitcher(this,'youtube')"
                                                           class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                            <?php $activeButton = ""; $active = "youtube"; ?>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="exercise_image_container">
                                                <div
                                                        class="tabs exerciseImageTab imagesTab  {{ ($active == "images" ? "showTab" : "") }}">
                                                    <a href="/{{ Helper::image($exercise->exercises->image) }}"
                                                       data-lightbox="ex_{{ $exercise->id }}"><img
                                                                src="/{{ Helper::image($exercise->exercises->image) }}"
                                                                alt="{{ $exercise->exercises->name }}"></a>
                                                    @if($exercise->exercises->image2 != "")
                                                        <a href="/{{ Helper::image($exercise->exercises->image2) }}"
                                                           data-lightbox="ex_{{ $exercise->id }}"><img
                                                                    src="/{{ Helper::image($exercise->exercises->image2) }}"
                                                                    alt="{{ $exercise->exercises->name }}"></a>
                                                    @endif
                                                </div>
                                                @if($exercise->exercises->video != "")
                                                    <div
                                                            class="tabs exerciseVideoTab videoTab {{ ($active == "images" ? "video" : "") }}">
                                                        <div class="exercise_video_container"
                                                             style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">

                                                            <video id="my-video" class="video-js" controls
                                                                   preload="auto"
                                                                   style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"

                                                                   poster="/{{ Helper::image(null,"video") }}"
                                                                   data-setup="{}">
                                                                <source src="/{{ $exercise->exercises->video}}"
                                                                        type='video/mp4'>
                                                                <source src="MY_VIDEO.webm" type='video/webm'>
                                                                <p class="vjs-no-js">
                                                                    To view this video please enable JavaScript,
                                                                    and consider upgrading to a web browser that
                                                                    <a href="http://videojs.com/html5-video-support/"
                                                                       target="_blank">supports HTML5 video</a>
                                                                </p>
                                                            </video>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($exercise->exercises->youtube != "")
                                                    <div
                                                            class="tabs exerciseYoutubeTab youtubeTab {{ ($active == "youtube" ? "showTab" : "") }}">
                                                        <div class="exercise_video_container"
                                                             style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                            @if($exercise->youtube != "")
                                                                <iframe id="ytplayer" type="text/html"
                                                                        width="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}"
                                                                        height="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}"
                                                                        src="https://www.youtube.com/embed/{{$exercise->youtube }}"
                                                                ">?autoplay=1" frameborder="0"> </iframe>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>


                                        <!-- ---- EXERCISE DATA ---- -->

                                        <div class="exeData">
                                            <table class="">
                                                <caption>cardio exercise</caption>
                                                <thead>
                                                <tr>
                                                    <th class="tbInt" scope="col">
                                                        <p>{{ Lang::get("content.Interval") }}</p></th>
                                                    <th class="tbHr" scope="col">
                                                        @if($exercise->metricVisual == "hr" || $exercise->metricVisual=='rep')
                                                            {{ Lang::get("content.hr") }}
                                                        @elseif($exercise->metricVisual == "effort")
                                                            {{ Lang::get("content.effort") }}
                                                        @elseif($exercise->metricVisual == "Vo2Max")
                                                            {{ Lang::get("content.Vo2Max") }}
                                                        @elseif($exercise->metricVisual == "reserve")
                                                            {{ Lang::get("content.reserve") }}
                                                        @elseif($exercise->metricVisual == "range")
                                                            {{ Lang::get("content.HRrange") }}
                                                        @elseif($exercise->metricVisual == "max")
                                                            {{ Lang::get("content.max") }}
                                                        @else
                                                            {{ Lang::get("content.exerciseMode") }}
                                                        @endif
                                                    </th>
                                                    <th class="tbSpeed"
                                                        scope="col">{{ Lang::get("content.Speed") }}</th>
                                                    <th class="tbDist"
                                                        scope="col">{{ Lang::get("content.Distance") }}</th>
                                                    <th class="tbTime"
                                                        scope="col">{{ Lang::get("content.Time") }}</th>
                                                    <th class="tbMode">{{ Lang::get("content.Mode") }}</th>
                                                </tr>
                                                </thead>
                                                    <?php
                                                    $sets = $workout->getSets($exercise->id);
                                                    $sum_sets += $exercise->sets;
                                                    $counter = 0;
                                                    ?>
                                                <tbody>
                                                @foreach($sets as $set)

                                                    <tr>
                                                        <th scope="row">{{ Helper::setNumber($set->number,$set->workoutsExercises->sets)  }}</th>
                                                        <td>{{ ($set->bpm == "" || $set->bpm == "0" ? "-" : "$set->bpm")}}
                                                            <span>
                                    @if($set->metric == "Vo2Max" || $set->metric == "effort")
                                                                    %
                                                                @elseif($set->metric == "reserve")
                                                                    bpm
                                                                @elseif($set->metric == "range")
                                                                    bpm
                                                                @else
                                                                    bpm
                                                                @endif
                                </span></td>
                                                        <td><span
                                                                    class="exercise_units_speed_{{ $exercise->id }}">{{ ($set->speed == "" || $set->speed == "0" ? "-" : $set->speed) }}</span><span
                                                                    class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span>
                                                        </td>
                                                        <td><span
                                                                    class="exercise_units_distance_{{ $exercise->id }}">{{ $set->distance == "" || $set->distance == "0" ? "-" : $set->distance }}</span><span
                                                                    class="exercise_units_distance_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span>
                                                        </td>
                                                        <td>{{ ($set->time == "" || $set->time == "0" ? "-" : "$set->time")}}
                                                            <span> min</span></td>
                                                        <!-- <td>{{ $set->metric }}</td> -->
                                                        <td>
                                                            @if($set->metric == "hr" || $set->metric=='rep')
                                                                {{ Lang::get("content.hr") }}
                                                            @elseif($set->metric == "effort")
                                                                {{ Lang::get("content.effort") }}
                                                            @elseif($set->metric == "Vo2Max")
                                                                {{ Lang::get("content.Vo2Max") }}
                                                            @elseif($set->metric == "reserve")
                                                                {{ Lang::get("content.reserve") }}
                                                            @elseif($set->metric == "range")
                                                                {{ Lang::get("content.HRrange") }}
                                                            @else
                                                                {{ Lang::get("content.max") }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if($set->rest != "")
                                                        <tr>
                                                            <td class="restBtwSet"
                                                                colspan="6">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach


                                                @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                                    <tr>
                                                        <td class="td_lightBlue setsRest">{{ Lang::get("content.Rest") }}</td>
                                                        <td class="td_lightBlue setsRestValue"
                                                            colspan="4"> {{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.sec") }}</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- End CARDIO EXERCISE -->
                            @endif
                        @endforeach
                    @endif
                @endforeach

            </div> <!--End Wrapper -->
        </div>
    </section>
    <div id="performanceHeader">
        <div id="step1">
            <a href="{{route('trainee-invite-with-workout',['workout_id' => $workoutId])}}" class="bluebtn workout-bluebtn">Create account and add to my workouts</a>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function tabSwitcher(el, tab) {
            var $tabs = $(el).closest(".tabSwitcherParent").find("[class^='tab_']");
            var $tabsBtns = $(el).closest(".tabSwitcherParent").find(".tabBtn");


            //TAB BUTTON
            $tabsBtns.removeClass("tabSelected");
            $(el).closest(".tabSwitcherParent").find(".tab_button_" + tab).addClass("tabSelected");

            //Tab
            $(el).closest(".tabSwitcherParent").find(".tabs").removeClass("showTab");
            $(el).closest(".tabSwitcherParent").find("." + tab + "Tab").addClass("showTab");
        }
    </script>

<!-- AI Trainer Chat Modal -->
<div id="aiTrainerModal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="position: relative; background-color: #fefefe; margin: 2% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 600px; height: 85vh; display: flex; flex-direction: column; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
            <div>
                <h3 id="aiTrainerExerciseName" style="margin: 0; font-size: 20px; font-weight: 600; color:#FFFFFF">AI Trainer</h3>
                <p style="margin: 5px 0 0 0; font-size: 12px; opacity: 0.9;; color:#ffffff">Ask me anything about this exercise</p>
            </div>
            <button onclick="closeAITrainerChat()" style="background: transparent; border: none; color: white; font-size: 28px; font-weight: 300; cursor: pointer; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        <div id="aiChatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background-color: #f8f9fa;"></div>
        <div style="padding: 15px; border-top: 1px solid #e0e0e0; background-color: white; border-radius: 0 0 12px 12px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="text" id="aiChatInput" placeholder="Ask about form, technique, variations..." style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 25px; font-size: 14px; outline: none; height: 38px; box-sizing: border-box;" onkeypress="if(event.key==='Enter') sendAITrainerMessage();">
                <button onclick="sendAITrainerMessage()" id="aiSendBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 24px; border-radius: 25px; cursor: pointer; font-weight: 500; font-size: 14px; transition: opacity 0.3s; height: 38px; box-sizing: border-box;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Send</button>
            </div>
        </div>
    </div>
</div>

<style>
    .exerciseAITrainer { position: absolute; width: 50px; height: 50px; display: inline-block; margin-right: 10px; top:10px; right:60px }
    .exerciseAITrainer .spanContainer { width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3); }
    .exerciseAITrainer .spanContainer:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5); }
    .exerciseAITrainer span { color: white; font-size: 10px; font-weight: 600; text-align: center; line-height: 1.2; text-transform: uppercase; }
    #aiChatMessages::-webkit-scrollbar { width: 6px; }
    #aiChatMessages::-webkit-scrollbar-track { background: #f1f1f1; }
    #aiChatMessages::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
    #aiChatMessages::-webkit-scrollbar-thumb:hover { background: #555; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<script>
    let currentExerciseId = '';
    let currentExerciseName = '';

    function openAITrainerChat(element) {
        const exerciseContainer = element.closest('.cExercise');
        currentExerciseId = exerciseContainer.getAttribute('data-exercise-id');
        if (!currentExerciseId) { alert('Exercise ID not found'); return; }
        const exerciseNameElement = exerciseContainer.querySelector('.cExercise_header_info h5');
        currentExerciseName = exerciseNameElement ? exerciseNameElement.textContent.trim() : 'Exercise';
        document.getElementById('aiTrainerExerciseName').textContent = currentExerciseName;
        loadChatHistory();
        document.getElementById('aiTrainerModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        setTimeout(() => { document.getElementById('aiChatInput').focus(); }, 100);
    }

    function closeAITrainerChat() {
        document.getElementById('aiTrainerModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function loadChatHistory() {
        const messagesContainer = document.getElementById('aiChatMessages');
        messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;"><i class="fas fa-spinner fa-spin"></i> Loading chat...</div>';
        fetch(`/trainer/exercise-chat/${currentExerciseId}`, { method: 'GET', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.messages.length === 0) {
                    messagesContainer.innerHTML = `<div style="text-align: center; padding: 40px 20px; color: #999;"><div style="font-size: 48px; margin-bottom: 15px;">💪</div><p style="font-size: 16px; margin: 0;">Ask me anything about <strong>${currentExerciseName}</strong></p><p style="font-size: 13px; margin-top: 8px;">Form tips, variations, common mistakes, and more!</p></div>`;
                } else {
                    messagesContainer.innerHTML = '';
                    data.messages.forEach(msg => { appendMessage(msg.sender, msg.message, false); });
                    scrollToBottom();
                }
            } else { messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #e74c3c;">Failed to load chat history</div>'; }
        })
        .catch(error => { console.error('Error:', error); messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #e74c3c;">Error loading chat</div>'; });
    }

    function sendAITrainerMessage() {
        const input = document.getElementById('aiChatInput');
        const message = input.value.trim();
        if (!message) return;
        const sendBtn = document.getElementById('aiSendBtn');
        const originalBtnText = sendBtn.innerHTML;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        input.disabled = true;
        appendMessage('user', message, true);
        input.value = '';
        showTypingIndicator();
        fetch(`/trainer/exercise-chat/${currentExerciseId}`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ message: message }) })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            if (data.success) { appendMessage('ai', data.aiMessage.message, true); }
            else { appendMessage('ai', 'Sorry, I encountered an error. Please try again.', true); }
        })
        .catch(error => { console.error('Error:', error); hideTypingIndicator(); appendMessage('ai', 'Sorry, I encountered an error. Please try again.', true); })
        .finally(() => { sendBtn.disabled = false; sendBtn.innerHTML = originalBtnText; input.disabled = false; input.focus(); });
    }

    function appendMessage(sender, text, shouldScroll) {
        const messagesContainer = document.getElementById('aiChatMessages');
        const messageDiv = document.createElement('div');
        if (sender === 'user') {
            messageDiv.style.cssText = 'display: flex; justify-content: flex-end; margin-bottom: 15px;';
            messageDiv.innerHTML = `<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 16px; border-radius: 18px 18px 4px 18px; max-width: 70%; word-wrap: break-word; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">${text}</div>`;
        } else {
            messageDiv.style.cssText = 'display: flex; justify-content: flex-start; margin-bottom: 15px;';
            messageDiv.innerHTML = `<div style="background: white; color: #333; padding: 12px 16px; border-radius: 18px 18px 18px 4px; max-width: 70%; word-wrap: break-word; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;">${text.replace(/\n/g, '<br>')}</div>`;
        }
        messagesContainer.appendChild(messageDiv);
        if (shouldScroll) { scrollToBottom(); }
    }

    function showTypingIndicator() {
        const messagesContainer = document.getElementById('aiChatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.style.cssText = 'display: flex; justify-content: flex-start; margin-bottom: 15px;';
        typingDiv.innerHTML = `<div style="background: white; padding: 12px 16px; border-radius: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;"><div style="display: flex; gap: 4px;"><div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.32s;"></div><div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.16s;"></div><div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both;"></div></div></div>`;
        const style = document.createElement('style');
        style.textContent = `@keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }`;
        if (!document.querySelector('style[data-typing-animation]')) { style.setAttribute('data-typing-animation', 'true'); document.head.appendChild(style); }
        messagesContainer.appendChild(typingDiv);
        scrollToBottom();
    }

    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) { typingIndicator.remove(); }
    }

    function scrollToBottom() {
        const messagesContainer = document.getElementById('aiChatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
</script>
@endsection
