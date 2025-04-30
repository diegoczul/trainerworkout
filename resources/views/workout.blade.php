@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.'.strtolower($user->userType))
@section("header")
    {!! Helper::seo("workout",array("name"=>$workout->name)) !!}
@endsection
@section("headerExtra")
    {{ HTML::style(asset('assets/fw/awesomplete-gh-pages/awesomplete.css')) }}
    <link href="https://vjs.zencdn.net/5.17.0/video-js.css" rel="stylesheet">
@endsection


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
    $numberOfCircuitsInWorkout = 1;
    ?>

    <section id="workoutView" class="clearfix">

        @if(Auth::user()->userType == "Trainer")
            <div class="Trainer">
                <div class="wrapper">
                    <div class="moreOptions_view">
                        <!-- PRINT -->
                        <a title="Print" target="_blank" href="{{ Lang::get("routes./Workout/PrintWorkout/") }}{{ $workout->id }}/" id="printWorkouts"
                           class="moreOptionsButton moreOptionsWorkoutButton">
                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="https://www.w3.org/2000/svg">
                                <title>print</title>
                                <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                    <rect x="3.2" width="14" height="10" rx="1.2"/>
                                    <rect fill="#F2F2F2" y="4.8" width="20" height="10" rx="1.2"/>
                                    <circle stroke-width=".5" fill="#FFF" cx="14.2" cy="7.8" r=".8"/>
                                    <circle stroke-width=".5" fill="#FFF" cx="17" cy="7.8" r=".8"/>
                                    <g transform="translate(5.6 10.4)">
                                        <rect fill="#F2F2F2" width="8.8" height="9.6" rx="1.14"/>
                                        <path d="M2.167 2.5h4.678M2.167 4.5h4.678M2.167 6.5h4.678" stroke-width=".5" stroke-linecap="square"/>
                                    </g>
                                </g>
                            </svg>
                            <div class="printMenu loadingParent" id="downloadMenu">
                                <ul>
                                    <li id="printJpeg">
                                        <a href="javascript:void(0)" onclick="downloadJPEG(this)">
                                            <img src="{{asset('assets/img/printWorkout/jpegIcon.svg')}}">
                                            <p>JPEG {{ Lang::get("content.file") }}</p>
                                        </a>
                                    </li>
                                    <li id="printPdf">
                                        <a href="javascript:void(0)" onclick="downloadPDF(this)">
                                            <img src="{{asset('assets/img/printWorkout/pdfIcon.svg')}}">
                                            <p>PDF {{ Lang::get("content.file") }}</p>
                                        </a>
                                    </li>
                                    <li id="printJpegPdf">
                                        <a href="javascript:void(0)" onclick="downloadBoth(this)">
                                            <img src="{{asset('assets/img/printWorkout/pdfIcon.svg')}}">
                                            <p>PDF</p> <span> + </span>
                                            <img src="{{asset('assets/img/printWorkout/jpegIcon.svg')}}">
                                            <p>JPEG {{ Lang::get("content.file") }}</p>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- {{ Lang::get("content.print") }} -->
                        </a>

                        <!-- SHARE -->
                        <!-- <a title="Share" href="{{ Lang::get("routes./Workout/ShareWorkout/") }}{{ $workout->id }}/" class="fancybox moreOptionsWorkoutButton"> -->
                        <a title="Share" href="javascript:void(0)" class="moreOptionsButton moreOptionsWorkoutButton" id="shareWorkouts" onClick="lightBox();">
                            <svg width="11" height="20" viewBox="0 0 11 20" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    Share
                                </title>
                                <path d="M7.136 7.43C9.386 7.43 10 8 10 8.71v9.007C10 18.427 9.45 19 8.772 19H2.228C1.55 19 1 18.426 1 17.717V8.71c0-.707.818-1.28 2.66-1.28m-2.25-3L5.5 1l4.295 3.43M5.5 1.7v11.9" stroke="#369AD8" stroke-width=".9" fill="none" fill-rule="evenodd"/>
                            </svg>
                            <!-- {{ Lang::get("content.share") }} -->
                        </a>

                        <!-- Edit -->
                        <a title="Edit" href="{{ $workout->getEditURL() }}" class="moreOptionsButton moreOptionsWorkoutButton" onclick="lightBoxLoadingTwSpinner()">
                            <svg width="17" height="24" viewBox="0 0 17 24" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    Edit icon
                                </title>
                                <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                    <path d="M9.714 1.38A1.7 1.7 0 0 1 12.01.675l3.187 1.695a1.7 1.7 0 0 1 .697 2.298l-8.166 15.36c-.442.83-1.478 1.864-2.296 2.3L2.894 23.68c-.827.44-1.577.034-1.673-.89l-.297-2.86c-.097-.93.184-2.362.624-3.19L9.714 1.38z" stroke-width="1.3"/>
                                    <path d="M2.11 18.477l3.863 2.054" stroke-width=".85" stroke-linecap="square"/>
                                </g>
                            </svg>
                            <!-- {{ Lang::get("content.edit") }} -->
                        </a>

                        <!-- Delete  -->
                        <a title="Delete" href="{{ Lang::get("routes./Workouts/removeWorkout/") }}{{ $workout->id }}/"
                           class="moreOptionsButton moreOptionsWorkoutButton" onclick="lightBoxLoadingTwSpinner()">
                            <svg width="13" height="18" viewBox="0 0 13 18" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    Delete Icon
                                </title>
                                <g stroke-width=".5" stroke="#369AD8" fill="none" fill-rule="evenodd">
                                    <g>
                                        <rect y="1.702" width="13" height="1.702" rx=".413"/>
                                        <rect x="4.875" width="3.25" height="1.276" rx=".413"/>
                                        <path d="M1.22 3.523c0-.23.182-.414.413-.414h9.734c.23 0 .414.187.414.413V16.35c0 .91-.74 1.65-1.65 1.65H2.87a1.65 1.65 0 0 1-1.65-1.65V3.523z"/>
                                    </g>
                                    <g stroke-linecap="square">
                                        <path d="M9.14 6.3v8.51M6.5 6.3v8.51M3.86 6.3v8.51"/>
                                    </g>
                                </g>
                            </svg>
                            <!-- {{ Lang::get("content.delete") }} -->
                        </a>

                        <!-- Download  -->
                        <a title="Download" class="moreOptionsButton moreOptionsWorkoutButton" href="javascript:void(0)" onclick="downloadBoth(this)">
                            <svg width="15" height="17" viewBox="0 0 15 17" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    Download
                                </title>
                                <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                                    <path d="M.5 12.5v2c0 1.105.897 2 2.006 2h9.988a1.998 1.998 0 0 0 2.006-2v-2" stroke-linecap="square"/>
                                    <path d="M3 9.43l4.09 3.427 4.296-3.428M7.09 12.157V.258" stroke-width=".9"/>
                                </g>
                            </svg>
                            <!-- {{ Lang::get("content.download") }} -->
                        </a>
                    </div>


                    <!-- Adding Tags to your workout -->
                    <div class="widget tag">
                        <h1>tags</h1>
                        <a href="javascript:void(0);" class="addTagBtn" onclick="$('#w_add_tags').slideToggle()" id="addTag">
                            <svg width="14" height="14" viewBox="0 0 14 14" xmlns="https://www.w3.org/2000/svg">
                                <title>ADD ICON</title>
                                <path d="M7.5 6.5V0h-1v6.5H0v1h6.5V14h1V7.5H14v-1H7.5z" fill="#FFF" fill-rule="evenodd"/>
                            </svg>
                            {{ Lang::get("content.addtags") }}
                        </a>

                        <div id="w_add_tags" class="tagdetails">
                            {{ View::make("widgets.add.tags")->with("tagsClient",$tagsClient)->with("tagsTags",$tagsTags)->with("selectedTags",$workout->tags)->with("workoutId",$workout->id)}}
                        </div>
                        <div id="w_tagsWorkout" style="position:relative; ">

                        </div>
                    </div>


                    <div class="widget aworkout">
                        <div class="viewWorkoutHeader">
                            <!-- WORKOUT TITLE -->
                            <div class="top">
                                @if(Auth::user()->activeLogo)
                                    <!-- DiegoLogo -->
                                    <img class="activeLogo" src="/{{ Helper::image(Auth::user()->activeLogo->thumb) }}" />
                                @endif
                                <div class="viewWorkoutHeader_powered">
                                    @if(Config::get("app.whitelabel") != "default")
                                        <span>{{ Lang::get("content.Created by") }}</span>
                                    @else
                                        <span>{{ Lang::get("content.Powered by") }}</span>
                                    @endif
                                    <img class="companyLogo" src="{{ asset('assets/'.Config::get("app.logo_on_image")) }}" />
                                </div>
                            </div>
                            <div class="viewWorkoutHeader_info">
                                <h2 id="title_VW">{{ $workout->name }}</h2>
                                <!-- CREATED BY -->
                                <h3 id="subtitle_VW">{{ $workout->author->firstName??"N/A" }} {{ $workout->author->lastName??"" }}</h3>
                                <hr>
                                @if(isset($workout->notes) &&!empty($workout->notes))
                                    <h4>Note: {{ $workout->notes }}</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        @else
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

        @endif
                <div class="wrapper">

                        @foreach($groups as $group)
                                <?php
                                $exercises = $group->getExercises()->get();
                                $restTimeBetweenExercises = unserialize($group->restBetweenCircuitExercises);
                                $circuitExercisesCounter = 0;
                                ?>

                            @if(count($exercises) > 1 or $group->type == "circuit")



                                <!------------------------ CIRCUIT ------------------------>
                                <div class="exercise circuit circuitContainer">
                                    <!-- <div class="circuitLine"></div> -->
                                    <div class="exercise_Header">

                                        <h2>Circuit # {{ $numberOfCircuitsInWorkout }} <?php $numberOfCircuitsInWorkout++ ?></h2>
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
                                                                 xmlns="https://www.w3.org/2000/svg">
                                                                <title>
                                                                    Play Icon
                                                                </title>
                                                                <g transform="translate(1 1)" fill="none"
                                                                   fill-rule="evenodd">
                                                                    <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
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
                                                                    <img class="traineeNote" src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="note">
                                                                    <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
                                                                    <!-- Diego, here is the notes from trainer -->
                                                                    <p name="noteToExercise" class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                                    <div class="traineePicContainer">
                                                                        <!-- Diego!! Need to link the trainer's profile image here -->
                                                                        <img class="trainerProfilePic" src="{{asset('/')}}/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
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
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'images')" class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                                    <?php $activeButton = ""; $active = "images"; ?>
                                                                @endif
                                                                @if($exercise->exercises->video != "")
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')" class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                                    <?php $activeButton = ""; $active = "video"; ?>
                                                                @endif
                                                                @if($exercise->exercises->youtube != "")
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'youtube')" class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                                    <?php $activeButton = ""; $active = "youtube"; ?>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="exercise_image_container">
                                                            <div
                                                                class="tabs exerciseImageTab  {{ ($active == "images" ? "showTab" : "") }} imagesTab">
                                                                <a href="/{{ Helper::image($exercise->exercises->image) }}" data-lightbox="ex_{{ $exercise->id }}"><img src="/{{ Helper::image($exercise->exercises->image) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                                @if($exercise->exercises->image2 != "")
                                                                    <a href="/{{ Helper::image($exercise->exercises->image2) }}" data-lightbox="ex_{{ $exercise->id }}"><img src="/{{ Helper::image($exercise->exercises->image2) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                                @endif
                                                            </div>
                                                            @if($exercise->exercises->video != "")
                                                                <div
                                                                    class="tabs exerciseVideoTab videoTab {{ ($active == "video" ? "showTab" : "") }}">
                                                                    <div class="exercise_video_container" style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                        <video id="my-video" class="video-js" controls preload="auto" style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}" poster="/{{ Helper::image(null,"video") }}" data-setup="{}">
                                                                            <source src="/{{ $exercise->exercises->video}}" type='video/mp4'>
                                                                            <source src="MY_VIDEO.webm" type='video/webm'>
                                                                            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
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
                                                                            <iframe id="ytplayer" type="text/html" width="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}" height="{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}" src="https://www.youtube.com/embed/{{$exercise->exercises->youtube }}"> </iframe>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="exeData">
                                                        <div class="exeData_top">
                                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; width: 100%;">
                                                                @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                                                    <div class="exeTempo">
                                                                        <p>{{ Lang::get("content.Tempo") }}</p>
                                                                        <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                                                        <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                                                        <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                                                        <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                                                    </div>
                                                                @endif
                                                                @if(Auth::user()->userType == "Trainee")
                                                                    <div style="margin: 0px 10px">
                                                                        <a href="javascript:void(0);" onclick="viewSetHistory(this, {{ $exercise->id }});" class="bluebtn" style="padding: 2px 4px; font-size: .7em">View History</a>
                                                                    </div>
                                                                @endif

                                                                <div class="unitSwitcherContainer" style="margin: 0px">
                                                                    <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                                                                    <p>{{ Lang::get("content.Lbs") }}</p>
                                                                    <label class="unitToggleLabel">
                                                                        <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                                                        <div class="unitToggleControl"></div>
                                                                    </label>
                                                                    <p>{{ Lang::get("content.Kg") }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <table>
                                                            <caption>muscle exercise</caption>
                                                            <thead>
                                                            <tr>
                                                                <th class="tbRound" scope="col">Set</th>
                                                                <th class="tbWeight" scope="col">{{ Lang::get("content.Weight") }}</th>
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
                                                                    <td style="display: flex; padding: 0">
                                                                        <input class="form-input exercise_units_weight_{{ $exercise->id }}" style="border: none; margin: 0px" type="number" onchange="updateWorkoutWeight(this, {{$exercise->id}}, {{$set->id}});" value="{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}" width="50%" @if($workout->isOwner() && Auth::user()->userType != "Trainee" ) readonly @endif>
                                                                        <span style="margin: 10px;" class="exercise_units_weight_unit_{{ $exercise->id }}">{{$exercise->units == "Metric"?Lang::get("content.Kg"):Lang::get("content.Lbs") }}</span>
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
                                                                        <td class="restBtwSet" colspan="4">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextsets") }}</td>
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
                                                        <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
                                                            <title>
                                                                Pause Icon
                                                            </title>
                                                            <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                                                <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
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
                                            <div class="cExercise" >

                                                <!-- LEFT AREA WITH THE EXERCISE DESCRIPTION -->
                                                <div class="cExercise_header">
                                                    <div class="cExercise_header_top">
                                                        <div class="cExercise_header_icon">
                                                            <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
                                                                <title>
                                                                    Play Icon
                                                                </title>
                                                                <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                                                    <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
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
                                                                    <img class="traineeNote" src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                                    <span>1</span>
                                                                </div>
                                                                <div class="note">
                                                                    <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
                                                                    <!-- Diego, here is the notes from trainer -->
                                                                    <p name="noteToExercise" class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                                    <div class="traineePicContainer">
                                                                        <!-- Diego!! Need to link the trainer's profile image here -->
                                                                        <img class="trainerProfilePic" src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
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
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'images')" class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                                    <?php $activeButton = ""; $active = "images"; ?>
                                                                @endif
                                                                @if($exercise->exercises->video != "")
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')" class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                                    <?php $activeButton = ""; $active = "video"; ?>
                                                                @endif
                                                                @if($exercise->exercises->youtube != "")
                                                                    <a href="javascript:void(0)" onclick="tabSwitcher(this,'youtube')" class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
                                                                    <?php $activeButton = ""; $active = "youtube"; ?>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="exercise_image_container">
                                                            <div class="tabs exerciseImageTab  {{ ($active == "images" ? "showTab" : "") }} imagesTab">
                                                                <a href="/{{ Helper::image($exercise->exercises->image) }}" data-lightbox="ex_{{ $exercise->id }}"><img src="/{{ Helper::image($exercise->exercises->image) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                                @if($exercise->exercises->image2 != "")
                                                                    <a href="/{{ Helper::image($exercise->exercises->image2) }}" data-lightbox="ex_{{ $exercise->id }}"><img src="/{{ Helper::image($exercise->exercises->image2) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                                @endif
                                                            </div>
                                                            @if($exercise->exercises->video != "")
                                                                <div
                                                                    class="tabs exerciseVideoTab videoTab {{ ($active == "video" ? "showTab" : "") }}">
                                                                    <div class="exercise_video_container"
                                                                         style="background-color: #000; width: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_w") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_w") :  Config::get("constants.constantsMobileSizeVideo_w")))  }}; height: {{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}; color: #FFF;">
                                                                        <video id="my-video" class="video-js" controls preload="auto" style="width:100%; max-height:{{ ($agent::isDesktop()? Config::get("constants.constantsDesktopSizeVideo_h") : ($agent::isTablet() ?  Config::get("constants.constantsTabletSizeVideo_h") :  Config::get("constants.constantsMobileSizeVideo_h")))  }}" poster="/{{ Helper::image(null,"video") }}" data-setup="{}">
                                                                            <source src="/{{ $exercise->exercises->video}}" type='video/mp4'>
                                                                            <source src="MY_VIDEO.webm" type='video/webm'>
                                                                            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                                                <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
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
                                                             xmlns="https://www.w3.org/2000/svg">
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
                                             xmlns="https://www.w3.org/2000/svg">
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
                                                            <p name="noteToExercise" class="viewExerciseNotes">{{{ ($exercise->notes != "") ? $exercise->notes : "" }}}</p>
                                                            <div class="traineePicContainer">
                                                                <!-- Diego!! Need to link the trainer's profile image here -->
                                                                <img class="trainerProfilePic" src="/{{ ($workout->author) ? Helper::image($workout->author->thumb) : Helper::image("") }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- ///////////////// -->


                                                <div class="unitSwitcherContainer">
                                                    <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                                                    <p>{{ Lang::get("content.Lbs") }}</p>
                                                    <label class="unitToggleLabel">
                                                        <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
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
                                                                <a href="javascript:void(0)" onclick="tabSwitcher(this,'images')" class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                                    <?php $activeButton = ""; $active = "images"; ?>
                                                            @endif
                                                            @if($exercise->exercises->video != "")
                                                                <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')" class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                                    <?php $activeButton = ""; $active = "video"; ?>
                                                            @endif
                                                            @if($exercise->exercises->youtube != "")
                                                                <a href="javascript:void(0)" onclick="tabSwitcher(this,'youtube')" class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
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

                                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                                    @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                                        <div class="exeTempo">
                                                            <p>{{ Lang::get("content.Tempo") }}</p>
                                                            <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                                            <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                                        </div>
                                                    @endif
                                                    @if(Auth::user()->userType == "Trainee")
                                                        <div>
                                                            <a href="javascript:void(0);" onclick="viewSetHistory(this, {{ $exercise->id }});" class="bluebtn" style="padding: 2px 4px; font-size: .7em">View History</a>
                                                        </div>
                                                    @endif
                                                    </div>

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
                                                                <td style="display: flex; padding: 0">
                                                                    <input class="form-input exercise_units_weight_{{ $exercise->id }}" onchange="updateWorkoutWeight(this, {{$exercise->id}}, {{$set->id}});" style="border: none; margin: 0px; padding: 10px" type="number"  value="{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}" width="50%" @if($workout->isOwner() && Auth::user()->userType != "Trainee" ) readonly @endif>
                                                                    <span style="margin: 10px;" class="exercise_units_weight_unit_{{ $exercise->id }}">{{$exercise->units == "Metric"?Lang::get("content.Kg"):Lang::get("content.Lbs") }}</span>
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
                                                        <img class="exitNote" src="{{asset('assets/img/exitPopup.svg')}}" onclick="exerciseNote(this);">
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
                                                            <a href="javascript:void(0)" onclick="tabSwitcher(this,'images')" class="tabBtn tabSelected tab_button_images {{ $activeButton }}">{{ Lang::get("content.Images") }}</a>
                                                            <?php $activeButton = ""; $active = "images"; ?>
                                                        @endif
                                                        @if($exercise->exercises->video != "")
                                                            <a href="javascript:void(0)" onclick="tabSwitcher(this,'video')" class="tabBtn tab_button_video {{ $activeButton }}">{{ Lang::get("content.Video") }}</a>
                                                            <?php $activeButton = ""; $active = "video"; ?>
                                                        @endif
                                                        @if($exercise->exercises->youtube != "")
                                                            <a href="javascript:void(0)" onclick="tabSwitcher(this,'youtube')" class="tabBtn tab_button_youtube {{ $activeButton }}">Youtube</a>
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
                    @if($group->restAfter != 0 and $group->restAfter != "")
                        <div class="circuitRestBtwExe">
                            <svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg">
                                <title>
                                    Pause Icon
                                </title>
                                <g transform="translate(1 1)" fill="none" fill-rule="evenodd">
                                    <circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/>
                                    </circle>
                                    <path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/>
                                    </path>
                                </g>
                            </svg>
                            <p>{{ $group->restAfter }} {{ Lang::get("content.secrest") }}</p>
                        </div>
                    @endif
                    @endforeach

                </div> <!--End Wrapper -->

        </div> <!--End Trainee / Trainer -->

    </section>

    @include('popups.shareWorkout')
    <!-- ////////////////////////////////////////////////////// -->

    @if($workout->isOwner() && Auth::user()->userType == "Trainee" )
        <div id="performanceHeader">
            <div id="step1">
                <div id="timer">
                    <div class="timerButton">
                        <label id="label" style="margin: 0; display: flex; align-items: center; justify-content: center; text-align: center" for=btn>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 96 96" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="09-timer"><path d="m77.08 28.577 5.748-5.748-5.656-5.658-6.149 6.149A39.747 39.747 0 0 0 52 16.2V8h8V0H36v8h8v8.2a39.747 39.747 0 0 0-19.023 7.12l-6.149-6.149-5.656 5.658 5.748 5.748a40 40 0 1 0 58.16 0zM48 88a32 32 0 1 1 32-32 32.036 32.036 0 0 1-32 32z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path><path d="M48 32v24H24a24 24 0 1 0 24-24z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path></g></g></svg>
                            {{ Lang::get("content.Start timer") }}</label>
                        <input type="button" name="btn" value="Start" onclick="toggleTimer(this.value)" id="btn"/>
                    </div>
                    <div id=n1></div>
                    <a href="javascript:void(0)"
                       onclick="discartOld()">{{ Lang::get("content.Discard old and start new") }}</a>
                </div>

                <button onclick="showPerformancePopUp()">{{ Lang::get("content.Complete Workout") }}</button>
            </div>


            <div class="overlayPerform showPerformancePopUp">
                <form action="{{ Lang::get("routes./Workout/Performance") }}" method="post"/>
                <input type="hidden" name="totalSeconds" value="0" id="totalSeconds"/>
                <input type="hidden" name="workoutId" value="{{ $workout->id }}" id="workoutId"/>
                <input type="hidden" name="workoutPerformanceId" value="0" id="workoutPerformanceId"/>
                <div class="performOptions">
                    <svg width="15" height="15" viewBox="0 0 15 15" xmlns="https://www.w3.org/2000/svg"
                         onclick="closePerformance();">
                        <title>
                            Close Icon
                        </title>
                        <path class="closeIcon"
                              d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z"
                              fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/>
                        </path>
                    </svg>
                    <ul id="step2">
                        <h3>{{ Lang::get("content.How are you feeling") }}</h3>
                        @foreach($ratings as $rating)
                            <li onclick="nextStep(3)"><label for="rating{{ $rating->id }}"><p>{{ $rating->name }}</p>
                                </label><input id="rating{{ $rating->id }}" type="radio" name="rating"
                                               value="{{ $rating->id }}"/></li>
                        @endforeach
                    </ul>
                    <div id="step3" class="">
                        <h3>{{ Lang::get("content.Want to add some information") }}</h3>
                        <label for="totaltime">{{ Lang::get("content.Workout time") }}</label>
                        <input type="text" name="totalTime" value="0" id="totaltime"/><span class="minUnit">min</span>
                        <textarea name="performanceComments"></textarea>
                        <div class="submit">
                            <button onClick="lightBoxLoadingTwSpinner();"
                                    type="submit">{{ Lang::get("content.Submit") }}</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="overlayKillChild"></div> -->

                </form>
            </div>

        </div>
    @endif


@endsection



@section('scripts')
    {{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')) }}
    <script type="text/javascript" src="{{asset('assets/fw/flowplayer/flowplayer-3.2.2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/stopwatch.js')}}"></script>
    <script>
        var updateWorkoutWeightTimeout;
        function updateWorkoutWeight(element,exercise_id,set_id){
            if($(element).val().trim() != ""){
                clearTimeout(updateWorkoutWeightTimeout);
                updateWorkoutWeightTimeout = setTimeout(function (){
                    $.ajax({
                        url: "{{route('workout.weight-update')}}",
                        type: "POST",
                        data: {
                            set_id: set_id,
                            exercise_id: exercise_id,
                            workout_id: {{ $workout->id }},
                            weight: $(element).val(),
                        },
                        success: function (data, textStatus, jqXHR) {

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            errorMessage(jqXHR.responseText);
                        }
                    });
                },500);
            }
        }

        function viewSetHistory(element,exercise_id){
            $.ajax({
                url: "{{route('workout.weight-history')}}",
                type: "POST",
                data: {
                    exercise_id: exercise_id,
                    workout_id: {{ $workout->id }},
                },
                success: function (data, textStatus, jqXHR) {

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                }
            });
        }

        function removeSetHistory(element,set_id){
            $.ajax({
                url: "{{route('workout.remove-weight-history')}}",
                type: "POST",
                data: {
                    weight_history_id: set_id,
                },
                success: function (data, textStatus, jqXHR) {
                    successMessage(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    errorMessage(jqXHR.responseText);
                }
            });
        }

        function toggleTimer(action) {
            console.log("click");
            switch (action) {
                case  'Stop':
                    window.clearInterval(tm); // stop the timer
                    window.clearInterval(sm); // stop the timer
                    document.getElementById('btn').value = "Start";
                    document.getElementById('label').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 96 96" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="09-timer"><path d="m77.08 28.577 5.748-5.748-5.656-5.658-6.149 6.149A39.747 39.747 0 0 0 52 16.2V8h8V0H36v8h8v8.2a39.747 39.747 0 0 0-19.023 7.12l-6.149-6.149-5.656 5.658 5.748 5.748a40 40 0 1 0 58.16 0zM48 88a32 32 0 1 1 32-32 32.036 32.036 0 0 1-32 32z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path><path d="M48 32v24H24a24 24 0 1 0 24-24z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path></g></g></svg> Start timer`;
                    break;
                case  'Start':
                    startWorkoutPerformance();
                    tm = window.setInterval('disp()', 1000);
                    sm = window.setInterval('savePerformance()', 1000);
                    document.getElementById('btn').value = "Stop";
                    document.getElementById('label').innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" x="0" y="0" viewBox="0 0 96 96" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><g data-name="09-timer"><path d="m77.08 28.577 5.748-5.748-5.656-5.658-6.149 6.149A39.747 39.747 0 0 0 52 16.2V8h8V0H36v8h8v8.2a39.747 39.747 0 0 0-19.023 7.12l-6.149-6.149-5.656 5.658 5.748 5.748a40 40 0 1 0 58.16 0zM48 88a32 32 0 1 1 32-32 32.036 32.036 0 0 1-32 32z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path><path d="M48 32v24H24a24 24 0 1 0 24-24z" fill="#FFFFFF" opacity="1" data-original="#000000" class=""></path></g></g></svg>Stop timer`;
                    break;
            }
        }


        function discartOld() {
            toggleTimer("Stop");
            $.ajax(
                {
                    url: "/Workout/Performance/discartOldPerformance",
                    type: "POST",
                    data: {
                        workoutId: '{{ $workout->id }}',
                        performanceId: '{{ ($workout->lastPerformance != "" ) ? $workout->lastPerformance : "''" }}'
                    },
                    success: function (data, textStatus, jqXHR) {
                        h = 0;
                        m = 0;
                        s = 0;
                        totalSeconds = 0;
                        ts = totalSeconds;
                        toggleTimer("Start");

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
            document.getElementById('n1').innerHTML = "0:00:00";
        }

        function savePerformance() {
            $.ajax(
                {
                    url: "/Workout/Performance/saveProgressPerformance",
                    type: "POST",
                    data: {
                        totalSeconds: totalSeconds,
                        performanceId: $("#workoutPerformanceId").val()
                    },
                    success: function (data, textStatus, jqXHR) {

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
        }


        function startWorkoutPerformance() {
            $.ajax(
                {
                    url: "/Workout/Performance/Start",
                    type: "POST",
                    data: {
                        workoutId: '{{ $workout->id }}',
                        performanceId: '{{ ($workout->lastPerformance != "" ) ? $workout->lastPerformance : "''" }}'
                    },
                    success: function (data, textStatus, jqXHR) {
                        console.log(data.id);
                        $("#workoutPerformanceId").val(data.id);
                        if (data.timeInSeconds > 0) {
                            totalSeconds = parseInt(data.timeInSeconds);
                            ts = totalSeconds;
                            h = Math.floor(totalSeconds / 3600);
                            m = Math.floor(totalSeconds / 60 - h * 3600);
                            s = Math.floor(totalSeconds - h * 3600 - m * 60);
                        }


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
        }


        function showPerformancePopUp() {
            $(".overlayPerform").addClass("overlayPerform-active");
            $(".performOptions").addClass("performOptions-active");
            $("#performanceHeader").addClass("performanceHeader-Active");
            $("body").addClass("no_scroll_overlay");
            document.getElementById('step1').style.display = 'none';
            toggleTimer("Stop");
            $("#totalSeconds").val(totalSeconds);
            $("#totaltime").val((totalSeconds / 60).toFixed(1));
        }

        function nextStep(id) {
            var nextStep = "step" + id;
            var thisStep = "step" + (id - 1);
            document.getElementById(thisStep).style.display = 'none';
            document.getElementById(nextStep).style.display = 'block';
        }

        function closePerformance() {
            $("body").removeClass("no_scroll_overlay");
            $(".overlayPerform").removeClass("overlayPerform-active");
            $(".performOptions").removeClass("performOptions-active");
            $("#performanceHeader").removeClass("performanceHeader-Active");
            document.getElementById("step1").style.display = 'block';
            document.getElementById("step2").style.display = 'block';
            document.getElementById("step3").style.display = 'none';
        }

        // add trainer notes on trainee workout view
        function exerciseNote(object) {
            var EaddingNote = $(object).closest(".exerciseNote");
            var EaddNote = $(object).closest(".exerciseNote").find(".noteContainer");
            var Enote = $(object).closest(".exerciseNote").find(".note");

            EaddNote.toggle();
            EaddingNote.toggleClass("ptAddingNote");
            Enote.toggle();
        }


        $(".exeDescription").click(function () {
            if ($(this).find(".exeDescription_full").is(":visible")) {
                $(this).find(".exeDescription_full").slideUp(300);
                $(this).find(".exeDescription_Exp path:nth-child(2)").css("opacity", "1");
            } else {
                $(this).find(".exeDescription_full").slideDown(300);
                $(this).find(".exeDescription_Exp path:nth-child(2)").css("opacity", "0");
            }
        });


        var selectedItems = [{{ $workout->id }}];

        function removeTag(id, obj, e) {
            $.ajax(
                {
                    url: "/widgets/tags/removeTag",
                    type: "POST",
                    data: {tag: id, workoutId: {{ $workout->id }}},
                    success: function (data, textStatus, jqXHR) {
                        successMessage(data);
                        callWidget("w_tagsWorkout", null, null, null, {workoutId: {{ $workout->id }}});

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
        }

        function downloadJPEG(obj) {

            $(obj).closest(".loadingParent").find(".loading").show();
            //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG");
            var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/{{ $workout->id }}/JPEG";
            triggerAjaxFileDownload(url);
            widgetsToReload.push("w_workouts");
            refreshWidgets();
            showLess();

        }


        function downloadPDF(obj) {
            $(obj).closest(".loadingParent").find(".loading").show();
            //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/PDF");
            var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/{{ $workout->id }}/PDF";
            triggerAjaxFileDownload(url);
            widgetsToReload.push("w_workouts");
            refreshWidgets();
            showLess();
        }

        function downloadBoth(obj) {

            $(obj).closest(".loadingParent").find(".loading").show();
            //window.location.assign("{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG/PDF");
            //document.getElementById('downloader').src = "{{ Lang::get("routes./Workouts/createUserDownload") }}/"+selectedItems.join(",")+"/JPEG/PDF";

            var url = "{{ Lang::get("routes./Workouts/createUserDownload") }}/{{ $workout->id }}/JPEG/PDF";
            triggerAjaxFileDownload(url);

            widgetsToReload.push("w_workouts");
            refreshWidgets();
            showLess();
        }

        function changeUnits(exerciseWorkout, units, element) {
            //ASSUME CHECKED TOBE POUNDS
            // alert(units);
            var units = $("#exercise_units_" + exerciseWorkout).val();

            $(".exercise_units_weight_" + exerciseWorkout).each(function (index) {
                if ($("#exercise_units_" + exerciseWorkout).val() == "Imperial" || units == "") {
                    //alert("Changing to KG");
                    $(this).val(convertTo($(this).val(), "Metric"));
                } else {
                    $(this).val(convertTo($(this).val(), "Imperial"));
                }
            });

            $(".exercise_units_distance_" + exerciseWorkout).each(function (index) {
                if ($("#exercise_units_" + exerciseWorkout).val() == "Imperial" || units == "") {
                    //alert("Changing to KG");
                    $(this).text(convertToDistance($(this).text(), "Metric"));

                } else {
                    $(this).text(convertToDistance($(this).text(), "Imperial"));
                }
            });

            $(".exercise_units_speed_" + exerciseWorkout).each(function (index) {
                if ($("#exercise_units_" + exerciseWorkout).val() == "Imperial" || units == "") {
                    //alert("Changing to KG");
                    $(this).text(convertToSpeed($(this).text(), "Metric"));

                } else {
                    $(this).text(convertToSpeed($(this).text(), "Imperial"));
                }
            });

            if ($("#exercise_units_" + exerciseWorkout).val() == "Imperial" || units == "") {
                $(".exercise_units_weight_unit_" + exerciseWorkout).text(convertToUnit($(".exercise_units_weight_unit_" + exerciseWorkout).val(), "Metric"));
                $(".exercise_units_speed_unit_" + exerciseWorkout).text(convertToUnitSpeed($(".exercise_units_speed_unit_" + exerciseWorkout).val(), "Metric"));
                $(".exercise_units_distance_unit_" + exerciseWorkout).text(convertToUnitDistance($(".exercise_units_distance_unit_" + exerciseWorkout).val(), "Metric"));

                $("#exercise_units_" + exerciseWorkout).val("Metric");
                units = "Metric";
            } else {
                $(".exercise_units_weight_unit_" + exerciseWorkout).text(convertToUnit($(".exercise_units_weight_unit_" + exerciseWorkout).val(), "Imperial"));
                $(".exercise_units_speed_unit_" + exerciseWorkout).text(convertToUnitSpeed($(".exercise_units_speed_unit_" + exerciseWorkout).val(), "Imperial"));
                $(".exercise_units_distance_unit_" + exerciseWorkout).text(convertToUnitDistance($(".exercise_units_distance_unit_" + exerciseWorkout).val(), "Imperial"));

                $("#exercise_units_" + exerciseWorkout).val("Imperial");
                units = "Imperial";
            }
            saveUnitForExercise(exerciseWorkout, units);

        }

        function setUnits(exerciseWorkout, units, element) {
            //ASSUME CHECKED TOBE POUNDS
            // alert(units);

            if ($("#exercise_units_" + exerciseWorkout).val() == "") {
                saveUnitForExercise(exerciseWorkout, "Imperial");
            } else if ($("#exercise_units_" + exerciseWorkout).val() == "Imperial") {
                $(".exercise_units_weight_unit_" + exerciseWorkout).text(convertToUnit($(".exercise_units_weight_unit_" + exerciseWorkout).val(), "Imperial"));
                $(".exercise_units_speed_unit_" + exerciseWorkout).text(convertToUnitSpeed($(".exercise_units_speed_unit_" + exerciseWorkout).val(), "Imperial"));
                $(".exercise_units_distance_unit_" + exerciseWorkout).text(convertToUnitDistance($(".exercise_units_distance_unit_" + exerciseWorkout).val(), "Imperial"));
            } else {
                $(".exercise_units_weight_unit_" + exerciseWorkout).text(convertToUnit($(".exercise_units_weight_unit_" + exerciseWorkout).val(), "Metric"));
                $(".exercise_units_speed_unit_" + exerciseWorkout).text(convertToUnitSpeed($(".exercise_units_speed_unit_" + exerciseWorkout).val(), "Metric"));
                $(".exercise_units_distance_unit_" + exerciseWorkout).text(convertToUnitDistance($(".exercise_units_distance_unit_" + exerciseWorkout).val(), "Metric"));
            }

        }

        function saveUnitForExercise(exerciseWorkout, units) {
            $.ajax(
                {
                    url: "{{{ Lang::get("routes./Workout/unit/update") }}}",
                    type: "POST",
                    data: {id: exerciseWorkout, units: units, workoutId: {{ $workout->id }}},
                    success: function (data, textStatus, jqXHR) {

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        errorMessage(jqXHR.responseText);
                    }
                });
        }

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


        @foreach($groups as $group)
            <?php
            $exercises = $group->getExercises()->get();
            $restTimeBetweenExercises = unserialize($group->restBetweenCircuitExercises);
            $circuitExercisesCounter = 0;
            ?>
        @foreach($exercises as $exercise)
        //setUnits({{ $exercise->id}},$("#exercise_units_{{ $exercise->id }}").val());
        @endforeach


            @endforeach


        @foreach($exercises as $exercise)
        @if($exercise->exercises->video != "")
        flowplayer('player{{ $exercise->id }}', '/fw/flowplayer/flowplayer-3.2.2.swf', {
            wmode: "transparent", clip: {
                autoPlay: false,
                autoBuffering: true
            }
        });
        @endif
        @endforeach


    </script>
    <script>callWidget("w_tagsWorkout", null, null, null, {workoutId: {{ $workout->id }}});</script>

@endsection
<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
<script src="https://vjs.zencdn.net/5.17.0/video.js"></script>
