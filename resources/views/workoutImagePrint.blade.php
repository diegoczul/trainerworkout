@php
    use App\Http\Libraries\Helper;
@endphp
<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
    <meta charset="utf-8">

    {{ HTML::style(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.css')) }}
    {{ HTML::style(asset('assets/css/lang/styles_'.Config::get('app.locale').'.css')) }}
    {{ HTML::script(asset('assets/js/jquery-1.11.0.js')) }}
    {{ HTML::script(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.js')) }}
    {{ HTML::script(asset('assets/js/modernizr_touch.js')) }}

    @if(Config::get("app.whitelabel") != "default")
        <?php $whitelabel = "ymca"; ?>
    @endif

    @yield("headerScripts")

    <!-- Open Sans Font -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,700,800' rel='stylesheet' type='text/css'>

</head>
<body class="trainer">
<div id="o-wrapper" class="o-wrapper">
    <div class="systemMessages"></div>
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
    <section id="workoutView" class="clearfix internal">
        <div class="Trainee">
            <div class="workoutHeaderContainer">
                <div class="workoutHeaderWrapper">
                    <div class="workoutHedaer">
                        <div class="workoutHeaderImage">
                            <div class="one_sixth">
                                @if($workout->author and $workout->author->activeLogo)
                                    <img width="85" src="{{ Helper::imageToBase64(Helper::image($workout->author->activeLogo->image)) }}">
                                @endif
                            </div>
                            <div class="four_sixth"><h1>{{ $workout->name }}</h1></div>
                            <div class="one_sixth">
                                <div class="viewWorkoutHeader_powered">
                                    @if(Config::get("app.whitelabel") != "default")
                                        <span>{{ Lang::get("content.Created by") }}</span>
                                    @else
                                        <span>{{ Lang::get("content.Powered by") }}</span>
                                    @endif
                                </div>
                                <img src="{{ Helper::imageToBase64(public_path('assets/'.Config::get("app.logo_on_image"))) }}">
                            </div>
                        </div>
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

                                <img src="{{ Helper::imageToBase64(public_path(($workout->author) ? Helper::image($workout->author->image) : Helper::image("")))  }}">
                                <div class="workoutPTname">
                                    <p>{{{ ($workout->author) ? $workout->author->firstName : "" }}}</p>
                                    <p>{{{ ($workout->author) ? $workout->author->lastName : "" }}}</p>
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
                                                        <img src="{{ Helper::imageToBase64(Helper::image($image)) }}">
                                                    @endforeach
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>

                                <?php $circuitCount = 1; ?>
                            @foreach($exercises as $exercise)

                                @if($exercise->exercises->bodygroupId != 18)
                                    <!-- MUSCLE -->
                                    <div class="cExercise">

                                        <!-- LEFT AREA WITH THE EXERCISE DESCRIPTION -->
                                        <div class="cExercise_header">
                                            <div class="cExercise_header_top">
                                                <div class="cExercise_header_icon">
                                                    <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/play.svg'))}}">
                                                </div>
                                                <div class="cExercise_header_info">
                                                    <p>{{ $circuitCount }}<span>/{{ count($exercises) }}</span></p>
                                                    @if($exercise->equipmentId != "")
                                                        <img class="equip_img" src="{{ Helper::imageToBase64(public_path($exercise->equipment->thumb)) }}">
                                                    @endif
                                                    <h5>{{ $exercise->exercises->name }}
                                                        @if($exercise->equipmentId != "")
                                                            {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                                        @endif
                                                    </h5>
                                                </div>

                                            </div>
                                            <div class="cExercise_header_bottom">
                                                @if($exercise->notes != "")

                                                    <div class="exeDescription">


                                                        <p style="display:block">{{{ ($exercise->notes != "") ? $exercise->notes : "N/A" }}}</p>

                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="respExeContainer">
                                            <!-- EXERCISE IMAGE -->
                                            <div class="exercise_image_container">
                                                <a href="{{ asset(Helper::image($exercise->exercises->image)) }}" data-lightbox="ex_{{ $exercise->id }}" >
                                                    <img src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image))}}" alt="{{ $exercise->exercises->name }}">
                                                </a>
                                                @if($exercise->exercises->image2 != "")
                                                    <a href="{{ asset(Helper::image($exercise->exercises->image2)) }}" data-lightbox="ex_{{ $exercise->id }}" >
                                                        <img src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image2))}}" alt="{{ $exercise->exercises->name }}">
                                                    </a>
                                                @endif
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

                                                    <!-- <div class="unitSwitcherContainer">
                                    <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                                    <p>{{ Lang::get("content.Lbs") }}</p>
                                    <label class="unitToggleLabel">
                                        <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                        <div class="unitToggleControl"></div>
                                    </label>
                                    <p>{{ Lang::get("content.Kg") }}</p>
                                </div> -->
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
                                                            <td><span  class="exercise_units_weight_{{ $exercise->id }}">{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}</span>&nbsp;<span class="exercise_units_weight_unit_{{ $exercise->id }}">Lbs</span></td>
                                                            @if(($exercise->metric == "time" || $set->metric == "time" || $set->metric == "temps") and ($set->metric != "maxRep" and $set->metric != "range"))
                                                                <td>{{ $set->time }}<span>(sec)</span></td>
                                                            @else
                                                                <td>{{ $set->reps }}<span></span></td>
                                                            @endif
                                                            <td>{{ $set->metric }}</td>
                                                        </tr>
                                                        @if($set->rest != "")
                                                            <tr><td class="restBtwSet" colspan="4">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td></tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>



                                        @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                            <!-- Rest Between Exercise in Circuit -->
                                            <div class="circuitRestBtwExe">
                                                <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/pauseBlue.svg'))}}">
                                                <p>{{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                            </div>
                                        @endif
                                    </div>


                                        <?php $circuitExercisesCounter++; ?>
                                        <?php $circuitCount++; ?>
                                @else

                                    <!-- CARDIO -->
                                    <div class="cExercise" exercise="">

                                        <!-- LEFT AREA WITH THE EXERCISE DESCRIPTION -->
                                        <div class="cExercise_header">
                                            <div class="cExercise_header_top">
                                                <div class="cExercise_header_icon">
                                                    <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/play.svg'))}}">
                                                </div>
                                                <div class="cExercise_header_info">
                                                    <p>{{ $circuitCount }}<span>/{{ count($exercises) }}</span></p>
                                                    @if($exercise->equipmentId != "")
                                                        <img class="equip_img" src="{{Helper::imageToBase64(public_path('assets/'.$exercise->equipment->thumb))}}}">
                                                    @endif
                                                    <h5>{{ $exercise->exercises->name }}
                                                        @if($exercise->equipmentId != "")
                                                            {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}
                                                        @endif
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="cExercise_header_bottom">

                                                @if($exercise->notes != "")
                                                    <div class="exeDescription">


                                                        <p style="display:block">{{{ ($exercise->notes != "") ? $exercise->notes : "N/A" }}}</p>

                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        <!-- EXERCISE IMAGE -->
                                        <div class="respExeContainer">
                                            <div class="exercise_image_container">
                                                <a href="{{ asset(Helper::image($exercise->exercises->image)) }}" data-lightbox="ex_{{ $exercise->id }}" >
                                                    <img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                @if($exercise->exercises->image2 != "")
                                                    <a href="{{ asset(Helper::image($exercise->exercises->image2)) }}" data-lightbox="ex_{{ $exercise->id }}" >
                                                        <img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image2)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                                @endif
                                            </div>

                                            <div class="exeData">
                                                <div class="exeData_top">
                                                    <!-- <div class="unitSwitcherContainer">
                            <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                                <p>mi</p>
                                <label class="unitToggleLabel">
                                    <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                                    <div class="unitToggleControl"></div>
                                </label>
                                <p>km</p>
                            </div> -->
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
                                                        <th class="tbSpeed" scope="col">{{ Lang::get("content.Speed") }} <span class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span></th>
                                                        <th class="tbDist" scope="col">{{ Lang::get("content.Distance") }} <span class="exercise_units_distance_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span></th>
                                                        <th class="tbTime" scope="col">{{ Lang::get("content.Time") }}</th>
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
                                                            <td><span class="exercise_units_speed_{{ $exercise->id }}">{{ ($set->speed == "" || $set->speed == "0" ? "-" : $set->speed) }}</span><span class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span></td>
                                                            <td><span class="exercise_units_distance_{{ $exercise->id }}">{{ $set->distance == "" || $set->distance == "0" ? "-" : $set->distance }}</span><span class="exercise_units_distance_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span></td>
                                                            <td>{{ ($set->time == "" || $set->time == "0" ? "-" : "$set->time")}}<span> min</span></td>
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
                                                            <tr><td class="restBtwSet" colspan="6">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td></tr>
                                                        @endif
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                                @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                                    <!-- Rest Between Exercise in Circuit -->
                                                    <div class="circuitRestBtwExe">
                                                        <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/pauseBlue.svg'))}}">
                                                        <p>{{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                        <?php $circuitCount++; ?>
                                @endif
                            @endforeach


                            <!-- Rewind Icons end of Circuit -->
                            <div class="endCircuit">
                                <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/rewind.svg'))}}">
                                @if($group->circuitType == "emom")
                                    <p>Rest for the remainder of the minute</p>
                                @elseif($group->rest == "")
                                    <p>Go back and do the next round</p>
                                @else
                                    <p> {{ $group->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</p>
                                @endif
                            </div>

                        </div>

                    @else
                        @foreach($exercises as $exercise)
                            @if($exercise->exercises->bodygroupId != 18)




                                <!------------------------ MUSCLE EXERCISE ------------------------>
                                <div class="exercise muscle" exercise="">
                                    <div class="exercise_Header">
                                        <div class="exercise_Header_imgContainer">
                                            @if($exercise->equipmentId != "")
                                                <img class="equip_img" src="{{ Helper::imageToBase64(public_path('/'.$exercise->equipment->thumb)) }}">
                                            @endif
                                        </div>
                                        <h5>{{ $exercise->exercises->name }} @if($exercise->equipmentId != "") {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}@endif</h5>
                                    </div>

                                    <!-- EXERCISE DESCRIPTION -->
                                    <div class="exeInfo">

                                        @if($exercise->notes != "")
                                            <div class="exeDescription">


                                                <p style="display:block">{{{ ($exercise->notes != "") ? $exercise->notes : "N/A" }}}</p>

                                            </div>
                                        @endif

                                        <!-- Trainee view notes -->

                                        <!-- ///////////////// -->


                                        <!--                     <div class="unitSwitcherContainer">
                    <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                        <p>{{ Lang::get("content.Lbs") }}</p>
                        <label class="unitToggleLabel">
                            <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                            <div class="unitToggleControl"></div>
                        </label>
                        <p>{{ Lang::get("content.Kg") }}</p>
                    </div> -->

                                    </div>

                                    <div class="respExeContainer">
                                        <!-- EXERCISE IMAGE -->
                                        <div class="exercise_image_container">
                                            <a href="/{{ Helper::image($exercise->exercises->image) }}" data-lightbox="ex_{{ $exercise->id }}" ><img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                            @if($exercise->exercises->image2 != "")
                                                <a href="/{{ Helper::image($exercise->exercises->image2) }}" data-lightbox="ex_{{ $exercise->id }}" ><img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image2)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                            @endif
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
                                                    <th class="tbSet" scope="col">Set</th>
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

                                                    <?php  $sum_sets += $exercise->sets;?>
                                                    <?php  $counter = 0; ?>
                                                    <?php  $sets = $workout->getSets($exercise->id); ?>
                                                    <?php  $allDone = 1; ?>

                                                <tbody>
                                                @foreach($sets as $set)
                                                    <tr>
                                                        <th scope="row">{{ Helper::setNumber($set->number,$set->workoutsExercises->sets) }}</th>
                                                        <td><span   class="exercise_units_weight_{{ $exercise->id }}">{{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)) }}</span>&nbsp;<span class="exercise_units_weight_unit_{{ $exercise->id }}"> Lbs</span></td>

                                                        @if(($exercise->metric == "time" || $set->metric == "time" || $set->metric == "temps") and ($set->metric != "maxRep" and $set->metric != "range"))
                                                            <td>{{ $set->time }}<span>sec</span></td>
                                                        @else
                                                            <td>{{ $set->reps }}<span></span></td>
                                                        @endif
                                                        <td>{{ $set->metric }}</td>
                                                    </tr>
                                                    @if($set->rest != "")
                                                        <tr><td class="restBtwSet" colspan="4">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td></tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            @else


                                <!------------------------ CARDIO EXERCISE ------------------------>

                                <div class="exercise cardio" exercise="">
                                    <div class="exercise_Header">
                                        <div class="exercise_Header_imgContainer">
                                            @if($exercise->equipmentId != "")
                                                <img class="equip_img" src="{{ Helper::imageToBase64(public_path('/'.$exercise->equipment->thumb)) }}">
                                            @endif
                                        </div>
                                        <h5>{{ $exercise->exercises->name }} @if($exercise->equipmentId != "") {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}@endif</h5>
                                    </div>
                                    <div class="exeInfo">
                                        @if($exercise->notes != "")
                                            <div class="exeDescription">


                                                <p style="display:block">{{{ ($exercise->notes != "") ? $exercise->notes : "N/A" }}}</p>

                                            </div>
                                        @endif

                                        <!--                     <div class="unitSwitcherContainer">
                    <input type="hidden" id="exercise_units_{{ $exercise->id }}" value="{{ $exercise->units }}"/>
                        <p>mi</p>
                        <label class="unitToggleLabel">
                            <input type="checkbox" class="unitToggleInput" onChange="changeUnits({{ $exercise->id }},this.value,this)" value="{{ $exercise->units }}" {{ $exercise->units == "Metric" ? "checked='checked'" : ""  }}>
                            <div class="unitToggleControl"></div>
                        </label>
                        <p>km</p>
                    </div> -->
                                    </div>

                                    <div class="respExeContainer">
                                        <div class="exercise_image_container">
                                            <a href="/{{ Helper::image($exercise->exercises->image) }}" data-lightbox="ex_{{ $exercise->id }}" ><img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                            @if($exercise->exercises->image2 != "")
                                                <a href="/{{ Helper::image($exercise->exercises->image2) }}" data-lightbox="ex_{{ $exercise->id }}" ><img  src="{{ Helper::imageToBase64(Helper::image($exercise->exercises->image2)) }}" alt="{{ $exercise->exercises->name }}"></a>
                                            @endif
                                        </div>

                                        <!-- ---- EXERCISE DATA ---- -->

                                        <div class="exeData">
                                            <table class="">
                                                <caption>cardio exercise</caption>
                                                <thead>
                                                <tr>
                                                    <th class="tbInt" scope="col"><p>{{ Lang::get("content.Interval") }}</p></th>
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
                                                    <th class="tbSpeed" scope="col">{{ Lang::get("content.Speed") }} <span class="exercise_units_speed_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span></th>
                                                    <th class="tbDist" scope="col">{{ Lang::get("content.Distance") }} <span class="exercise_units_distance_unit_{{ $exercise->id }}"> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span></th>
                                                    <th class="tbTime" scope="col">{{ Lang::get("content.Time") }}</th>
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
                                                        <td>{{ ($set->bpm == "" || $set->bpm == "0" ? "-" : "$set->bpm")}}<span> </span></td>
                                                        <td>{{ ($set->speed == "" || $set->speed == "0" ? "-" : "$set->speed") }}<span> {{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span></td>
                                                        <td>{{ $set->distance == "" || $set->distance == "0" ? "-" : "$set->distance" }}<span> {{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span></td>
                                                        <td>{{ ($set->time == "" || $set->time == "0" ? "-" : "$set->time")}}<span> min</span></td>
                                                        <td>{{ $set->metric }}</td>
                                                    </tr>
                                                    @if($set->rest != "")
                                                        <tr><td class="restBtwSet" colspan="6">{{ $set->rest }} {{ Lang::get("content.secrestbeforenextrounds") }}</td></tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>

                                            @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                                <tr>
                                                    <td class="td_lightBlue setsRest">{{ Lang::get("content.Rest") }}</td>
                                                    <td class="td_lightBlue setsRestValue" colspan="4"> {{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.sec") }}</td>
                                                </tr>
                                            @endif

                                        </div>
                                    </div>
                                </div>


                            @endif
                        @endforeach
                    @endif
                    @if($group->restAfter != 0 and $group->restAfter != "")
                        <div class="circuitRestBtwExe">
                            <img class="svgImg" src="{{Helper::imageToBase64(public_path('assets/img/svg/pauseBlue.svg'))}}">
                            <p>{{ $group->restAfter }} {{ Lang::get("content.secrest") }}</p>
                        </div>
                    @endif
                @endforeach

            </div>
        </div>
    </section>
    <!-- SIDE MENU  Needs to stay below FOOTER and outsode of O-wrapper  -->
</div> <!-- End of O-wrapper -->
<!-- /c-menu push-left -->
<div id="c-mask" class="c-mask"></div><!-- /c-mask -->
<!-- End of Side Mneu -->
<div class='loader-bg'>
    <img src='{{asset('assets/img/tw-gif.gif')}}'>
    <button onclick="hideTopLoader()" class="btn" style="color: #ffffff;position: absolute;right: 0;top: 0;padding: 10px;font-weight: bold;background: transparent;border: none;">X</button>
</div>
</body>
</html>