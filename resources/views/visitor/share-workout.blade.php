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
                                                <img class="equip_img" src="/{{ $exercise->equipment->thumb }}">
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
                                        </div>
                                    </div>
                                </div>   <!-- End MUSCLE EXERCISE -->
                            @else
                                <!------------------------ CARDIO EXERCISE ------------------------>
                                <div class="exercise cardio" exercise="">
                                    <div class="exercise_Header">
                                        <div class="exercise_Header_imgContainer">
                                            @if($exercise->equipmentId != "" and $exercise->equipment)
                                                <img class="equip_img" src="/{{ $exercise->equipment->thumb }}">
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
            <a href="{{route('trainee-invite-with-workout',['workout_id' => $workoutId])}}" class="bluebtn" style="padding: 5px">Create account and add to my workouts</a>
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
@endsection
