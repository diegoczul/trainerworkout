@php
    use App\Http\Libraries\Helper;
@endphp
<!doctype html>
<!--[if lt IE 7 ]>
<html lang="en" class="no-js ie6">
<![endif]-->
<!--[if IE 7 ]>
<html lang="en" class="no-js ie7">
<![endif]-->
<!--[if IE 8 ]>
<html lang="en" class="no-js ie8">
<![endif]-->
<!--[if IE 9 ]>
<html lang="en" class="no-js ie9">
<![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
    <!-- meta tags and title goes here
       ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="UTF-8">
    <title>Training workout Print</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Mobile Specific Metas here
       ================================================== -->
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->

    <meta name="title" content="">
    <meta name="description" content="">

    <style>
        /* http://meyerweb.com/eric/tools/css/reset/
       v2.0 | 20110126
       License: none (public domain)
    */
        html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            vertical-align: baseline;
        }

        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol, ul {
            list-style: none;
        }

        blockquote, q {
            quotes: none;
        }

        blockquote:before, blockquote:after {
            content: '';
            content: none;
        }

        q:before, q:after {
            content: '';
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        *, p, span, div, td, tr {
            font-family: "Avenir", "Open Sans", "Helvetica Neue", "Helvetica", "Roboto", "Arial", sans-serif;
            color: #4A4A4A;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            padding: 5px 5px 5px 5px !important;
            background: white;
        }

        .restBtwExe,
        .exercise {
            page-break-inside: avoid !important;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo {
            display: inline-block;
            margin-right: 20px;
            height: 95px;
        }

        .logo .user-logo {
            height: 100%;
        }

        .logo .tw {
            height: 100%;
        }

        .info {
            margin-top: auto;
            display: inline-block;
        }

        .w_name {
            color: #2C3E50;
            margin-bottom: 8px;
            font-style: oblique;
            max-width: 700px;
            white-space: nowrap;
        }

        .w_name span {
            font-weight: 500;
            font-size: 1.8em;
            letter-spacing: 1.28px;
            margin-right: 10px;
            color: #2C3E50;
            font-style: normal;
        }

        .note {
            display: -webkit-box;
            display: -moz-box;
            display: box;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flexbox;
            display: flex;
        }

        .note img {
            height: 50px;
            width: 50px;
            border-radius: 100%;
        }

        .note p {
            margin-left: 15px;
            max-width: 650px;
        }

        .poweredby {
            float: right;
            padding-top: 10px;
        }

        .poweredby p {
            color: #2C3E50;
            font-size: 1em;
            font-style: oblique;
            margin-bottom: 6px;
            text-align: right;
        }

        .poweredby img {
            margin-left: 64px;
        }

        .group {
            border: solid 1px #979797;
            padding: 10px;
            margin-bottom: 10px;
        }

        .circuit {
            background: #E5E5E5;
        }

        .circuit h1 {
            font-size: 1.2em;
        }

        .workout_header {
            background: #D8D8D8;
            margin-bottom: 15px;
            width: 100%;
        }

        .workout_header h4 {
            text-align: center;
            width: 20%;
        }

        .workout_header h4, .workout_header p {
            padding: 16px 0;
            display: inline-block;
        }

        .workout_header .exTable {
            padding-left: 30px;
            display: inline-block;
            width: 75%;
        }

        .workout_header .first {
            text-align: center;
        }

        .workout_header .day {
            text-align: left;
            padding-left: 5px;
        }

        .ccHead {
            margin-bottom: 25px;
        }

        .exercise_in_circuit {
            margin-bottom: 10px;
        }

        .exercise_line {
            display: -webkit-box;
            display: -moz-box;
            display: box;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flexbox;
            display: flex;
            width: 100%;
        }

        .exInfo {
            display: inline-block;
        }

        .ccHead,
        .titleText {
            font-weight: 500;
        }

        .titleInfo {
            max-width: 180px;
            margin-bottom: 10px;
        }

        .imgContainer {
            margin: auto auto;
            border: solid 1px white;
            display: -webkit-inline-box;
            display: -moz-inline-box;
            display: inline-box;
            display: -webkit-inline-flex;
            display: -moz-inline-flex;
            display: -ms-inline-flexbox;
            display: inline-flex;
        }

        .imgContainer img {
            height: 80px;
            width: 80px;
        }

        /* TEMPO */
        .exeTempo {
            display: inline-block;
        }

        .exeTempo p {
            color: #2C3E50;
            line-height: 16px;
            padding: 2px 4px;
            background-color: #DADADA;
            display: inline-block;
            min-width: 25px;
            text-align: center;
            font-size: 0.6em;
            margin: 1em 0;
            margin-right: -3px;
        }

        .exTable {
            display: -webkit-inline-box;
            display: -moz-inline-box;
            display: inline-box;
            display: -webkit-inline-flex;
            display: -moz-inline-flex;
            display: -ms-inline-flexbox;
            display: inline-flex;
        }

        .exTable table {
            background: white;
            margin-top: auto;
        }

        .setsTable {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #979797;
        }

        .cardio th,
        .cardio td {
            width: 58px;
        }

        .cardio th p {
            display: block;
            max-width: 55px;
            text-overflow: clip;
            white-space: normal;
            word-wrap: break-word;
            text-align: center;
        }

        .muscle th, .muscle td {
            width: 117.33333px;
        }

        .muscle th p {
            display: block;
            max-width: 114.33333px;
            text-overflow: clip;
            white-space: normal;
            word-wrap: break-word;
            text-align: center;
        }

        td, th {
            font-size: 12px;
            font-weight: 300;
            border: 1px solid #979797;
            padding: 4px 0;
            text-align: center;
            vertical-align: middle;
        }

        .muscle .set,
        .cardio .set {
            background: #D8D8D8;
            width: 40px;
        }

        .firstColDay {
            border-left: 2px solid #979797;
        }

        .day0 {
            background-color: #D8D8D8;
        }

        .restBtwRounds,
        .restBtwExercise {
            background: white;
            width: 90%;
            text-align: center;
            padding: 6px;
            margin: 10px auto;
            border-radius: 2px;
        }

        .restBtwExe {
            padding: 5px 10px;
            background-color: #D8D8D8;
            display: -webkit-box;
            display: -moz-box;
            display: box;
            display: -webkit-flex;
            display: -moz-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-align-content: center;
            -moz-align-content: center;
            align-content: center;
            -ms-flex-line-pack: center;
        }

        .restBtwExe p {
            color: #4A4A4A;
            text-align: center;
            margin: auto;
        }

        @page {
            size: landscape;
            margin-top: 0.2cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }

        * {
            font-size: 12pt;
            -webkit-print-color-adjust: exact;
        }

        .exTable {
            width: 79%;
        }

        .exInfo {
            width: 20%;
        }

        .cardio p {
            display: block;
            width: 98%;
            text-overflow: clip;
            white-space: normal;
            word-wrap: break-word;
            text-align: center;
        }

        .exTable .set {
            width: 30pt;
        }

        /*# sourceMappingURL=printWorkout.css.map */

    </style>
    @if(Config::get("app.whitelabel") != "default")
            <?php $whitelabel = "ymca"; ?>
    @endif

</head>


<!-- Set the number of days that will be displayed in teh print workout -->
<?php
$daysToFill = 3;
$daysWorkout = $daysToFill + 1;
?>

<body>
<div class="wrapper">
    <!-- Headers start -->
    <div class="header">
        <div class="logo">
            @if(Config::get("app.whitelabel") != "default")
                <img src="{{ Helper::imageToBase64(asset("assets",Config::get("app.logo"))) }}">
            @else
                @if($workout->author and $workout->author->activeLogo != "")
                    <img class="user-logo" src="{{ Helper::imageToBase64(public_path(($workout->author and $workout->author->activeLogo) ? Helper::image($workout->author->activeLogo->image??null) : Helper::image(null))) }}"/>
                @else
                    <svg class="tw" viewBox="0 0 92 53" xmlns="https://www.w3.org/2000/svg"><path d="M9.68 23.845c2.38 0 3.998-.802 5.604-1.646C8.108 23.56 1.024 18.547 0 10.184l11.89-.178.497-3.146 8-6.86h6.865l-1.618 9.7s5.44-.17 9.413 0c2.438.307 5.294 1.203 6.48 2.615 1.487 1.417 3.098 4.405 3.875 9.238.305 1.485.805 4.29 1 6.25l7.993-17.46c2.583-1.24 8.407-1.883 13.604-.158L70.522 30.5 84.976 6.86l5.114-1.74 1.746 5.065L73.24 51.247c-4.56 1.455-9.303 1.04-12.034 0l-3.42-20.47s-8.91 18.477-10.13 20.47c-4.132 1.455-10.075.882-11.957 0 0-1.712 0-20.19-.934-23.093 0 0-.5-5.426-3.69-5.955h-7.453s-2.99 16.24-2.638 16.76c.238.735.67.83 1.098 1.19 1.73.347 2.115.655 8.562-.943V49.2c-3.703 2.395-8.456 4.62-16.282 3.502-4.353-.792-7.64-3.088-7.828-8.9.34-4.368 1.61-9.957 3.145-19.957z" fill="#2C3E50" fill-rule="evenodd"/></svg>
{{--                    {!! Helper::svgToBase64Img('<svg class="tw" viewBox="0 0 92 53" xmlns="https://www.w3.org/2000/svg"><path d="M9.68 23.845c2.38 0 3.998-.802 5.604-1.646C8.108 23.56 1.024 18.547 0 10.184l11.89-.178.497-3.146 8-6.86h6.865l-1.618 9.7s5.44-.17 9.413 0c2.438.307 5.294 1.203 6.48 2.615 1.487 1.417 3.098 4.405 3.875 9.238.305 1.485.805 4.29 1 6.25l7.993-17.46c2.583-1.24 8.407-1.883 13.604-.158L70.522 30.5 84.976 6.86l5.114-1.74 1.746 5.065L73.24 51.247c-4.56 1.455-9.303 1.04-12.034 0l-3.42-20.47s-8.91 18.477-10.13 20.47c-4.132 1.455-10.075.882-11.957 0 0-1.712 0-20.19-.934-23.093 0 0-.5-5.426-3.69-5.955h-7.453s-2.99 16.24-2.638 16.76c.238.735.67.83 1.098 1.19 1.73.347 2.115.655 8.562-.943V49.2c-3.703 2.395-8.456 4.62-16.282 3.502-4.353-.792-7.64-3.088-7.828-8.9.34-4.368 1.61-9.957 3.145-19.957z" fill="#2C3E50" fill-rule="evenodd"/></svg>') !!}--}}
                @endif
            @endif
        </div>

        <div class="info">
            <div class="w_name"><span>{{ $workout->name }}</span>{{ Lang::get("content.Created by") }} : {{ $workout->author->getCompleteName() }}</div>
            <div class="note">
                @php
                    $wkimg = Helper::image(($workout->author and $workout->author->image != "") ? $workout->author->image : Helper::image(null));
                @endphp
                <img src="{{ Helper::imageToBase64(public_path($wkimg)) }}" alt="profile image"/>
                @if( $workout->notes )
                    <p class="txtNote">{{ $workout->notes }}</p>
                @endif
            </div>
        </div>

        <div class="poweredby">
            @if(Config::get("app.whitelabel") != "default")
                <p>{{ Lang::get("content.Created by") }}</p>
            @else
                <p>{{ Lang::get("content.Powered by") }}</p>
            @endif
            <img src="{{ Helper::imageToBase64(public_path('assets/'.Config::get("app.logo_on_print_grid"))) }}">
        </div>
    </div>

    <!-- Header end -->

    <?php $dayProportion = (1 / $daysWorkout * 100) - 1; ?>

    <div class="workout_header">
        <h4>{{ Lang::get("content.Exercise") }}</h4>
        <div class="exTable">
            <p class="first" style="width: <?php echo "$dayProportion"; ?>%;">{{ Lang::get("content.Base Prescription") }}</p>
            @for($x = 0; $x < $daysToFill; $x++)
                <?php $day = $x + 1; ?>
                <p class="day" style="width: <?php echo "$dayProportion"; ?>%;">{{ Lang::get("content.Day") }} <?php echo "$day" ?> : </p>
            @endfor
        </div>
    </div>


    <div class="holder-wrap">
        @foreach($groups as $group)
            <?php
                $exercises = $group->getExercises()->get();
                $restTimeBetweenExercises = unserialize($group->restBetweenCircuitExercises);
                if (!is_array($restTimeBetweenExercises)) $restTimeBetweenExercises = array();
                $circuitExercisesCounter = 0;
            ?>

            @if(count($exercises) > 1)
                <!-- IF this is a Circuit -->
                <div class="group circuit"> <!-- Circuit Start HERE -->
                    @if($group->circuitType == "emom")
                        <!-- If this is a emom -->
                        <h1 class="ccHead">{{ Lang::get("content.Circuit") }} Emom, {{{ $group->emom }}} {{ Lang::get("content.Minutes") }}</h1>
                    @elseif($group->circuitType == "amrap")
                        <!-- If this is AMRAP type circuit -->
                        <h1 class="ccHead">{{ Lang::get("content.Circuit") }} Amrap, Maximum {{{ $group->maxTime }}} {{ Lang::get("content.Minutes") }}</h1>
                    @else
                        <!-- If this is typical type circuit -->
                        <h1 class="ccHead">{{ Lang::get("content.Circuit") }} {{ $group->intervals }} {{ Lang::get("content.Rounds") }}</h1>
                    @endif


                    @foreach($exercises as $exercise)
                        <div class="exercise exercise_in_circuit">
                            <div class="exercise_line">
                                <div class="exInfo">
                                    <div class="titleInfo">
                                        @if($exercise->equipmentId != "")
                                            <span class="titleText">{{ $exercise->exercises->name }} with {{{ $exercise->equipment->name  }}}</span>
                                        @else
                                            <span class="titleText">{{ $exercise->exercises->name }} </span>
                                        @endif
                                    </div>

                                    <div class="imgContainer">
                                        <img class=exImage src="{{ Helper::imageToBase64(public_path(Helper::image($exercise->exercises->image))) }}">
                                        @if($exercise->exercises->image2 != "")
                                            <img class=exImage src="{{ Helper::imageToBase64(public_path(Helper::image($exercise->exercises->image2))) }}">
                                        @endif
                                    </div>
                                    @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                        <div class="exeTempo">
                                            <p>{{ Lang::get("content.Tempo") }}</p>
                                            <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                            <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                            <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                            <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                        </div>
                                    @endif
                                    @if($exercise->notes != "")
                                        <p class="printNote">{{{ $exercise->notes }}}</p>
                                    @endif
                                </div>
                                <div class="exTable">
                                    <table class="setsTable">
                                        <!---------------------------------------- CIRCUIT ---------------------------------------------->
                                        @if($exercise->exercises->bodygroupId != 18)
                                            <!-- if muscle exercise in circuit -->
                                            <thead class="muscle"> <!-- Header of Muscle Exercise -->
                                            <tr>
                                                <th scope="row" class="set">{{ Lang::get("content.Set") }}</th>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                    <th scope="col" class="wgt firstColDay <?php echo"day$x"; ?> ">
                                                        <span>{{ Lang::get("content.Wgt") }}</span> @if($exercise->units == "Metric") <span>(kg)</span> @else <span>(lbs)</span> @endif
                                                    </th>
                                                    <th scope="col" class="<?php echo"day$x"; ?>">
                                                        @if($exercise->metricVisual == "rep")
                                                            {{ Lang::get("content.Repetitions")  }}
                                                        @elseif($exercise->metricVisual == "time")
                                                            {{ Lang::get("content.Time")  }} (sec)
                                                        @elseif($exercise->metricVisual == "maxRep")
                                                            {{ Lang::get("content.maxRep")  }}
                                                        @elseif($exercise->metricVisual == "range")
                                                            {{ Lang::get("content.range")  }}
                                                        @else
                                                            {{ Lang::get("content.exerciseMode")  }}
                                                        @endif
                                                    </th>
                                                @endfor

                                            </tr>
                                            </thead>

                                                <?php
                                                $sets = $workout->getSets($exercise->id);
                                                $setsTemp = array();
                                                $counter = 0;
                                                $allDone = 1;
                                                //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                                                foreach ($sets as $set) {
                                                    $setsTemp[$counter] = $set;
                                                    $counter++;
                                                }
                                                ?>

                                            <tbody class="muscle">  <!-- Data of Muscle Exercise -->
                                            @for($y = 0; $y < $exercise->sets; $y++)
                                                <tr>
                                                    <td scope="row" class="set">{{ $y+1 }}</td>
                                                    @for($x = 0; $x < $daysWorkout; $x++)
                                                            <?php $pointer = $exercise->sets * $x + $y; ?>
                                                        @if(array_key_exists($pointer, $setsTemp))
                                                            <td class="wgt firstColDay <?php echo"day$x"; ?>">{{ ($setsTemp[$pointer]->weight == "" ? 0 : Helper::formatWeight($setsTemp[$pointer]->weight)) }}</td>
                                                            @if(($exercise->metric == "time" || $setsTemp[$pointer]->metric == "time" || $setsTemp[$pointer]->metric == "temps") and ($setsTemp[$pointer]->metric != "maxRep" and $setsTemp[$pointer]->metric != "range"))
                                                                <td class="rep lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->time }}
                                                                    <span>sec</span>
                                                                </td>
                                                            @else
                                                                <td class="time lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->reps }}</td>
                                                            @endif
                                                        @else
                                                            <td class="wgt firstColDay <?php echo"day$x"; ?>"></td>
                                                            <td class="rep_or_time lastColDay <?php echo"day$x"; ?>"></td>
                                                        @endif
                                                    @endfor
                                                </tr>

                                                    <?php $restThere = false; ?>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                    @if(array_key_exists($pointer, $setsTemp) and $setsTemp[$pointer]->rest != 0)
                                                            <?php $restThere = true; ?>
                                                    @endif
                                                @endfor

                                                @if($restThere)
                                                    <tr>  <!-- Rest of Muscle Exercise -->
                                                        <td class="rest">Rest</td>
                                                        @for($x = 0; $x < $daysWorkout; $x++)
                                                                <?php $pointer = $exercise->sets * $x + $y; ?>
                                                            @if(array_key_exists($pointer, $setsTemp))
                                                                <td colspan="2" class="varRest  <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->rest }}</td>
                                                            @else
                                                                <td colspan="2" class="varRest  <?php echo"day$x"; ?>"></td>
                                                            @endif
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endfor
                                            </tbody>
                                        @else
                                            <!-- Not Muscle start Cardio Exercise in circuit -->
                                                <?php
                                                $sets = $workout->getSets($exercise->id);
                                                $setsTemp = array();
                                                $counter = 0;
                                                $allDone = 1;
                                                foreach ($sets as $set) {
                                                    $setsTemp[$counter] = $set;
                                                    $counter++;
                                                }
                                                ?>
                                                    <!-- cardio exercise on circuit -->
                                            <thead class="cardio"> <!-- header of cardio exercise -->
                                            <tr>
                                                <th scope="row" class="set">Int</th>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                    <th scope="col" class="time firstColDay <?php echo"day$x"; ?>">
                                                        <p>{{ Lang::get("content.Time") }}</p>
                                                        <p>({{ Lang::get("content.min") }})</p>
                                                    </th>
                                                    <th scope="col" class="dist <?php echo"day$x"; ?>">
                                                        <p>{{ Lang::get("content.Distance") }}</p>
                                                        <p>(<span class="exercise_units_speed_unit_{{ $exercise->id }}">{{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span>)</p>
                                                    </th>
                                                    <th scope="col" class="hr <?php echo"day$x"; ?>">
                                                        <p>
                                                            @if($exercise->metricVisual == "hr" || $exercise->metricVisual =='rep')
                                                                {{ Lang::get("content.hr") }}
                                                            @elseif($exercise->metricVisual == "effort")
                                                                {{ Lang::get("content.effort") }}
                                                            @elseif($exercise->metricVisual == "Vo2Max")
                                                                {{ Lang::get("content.Vo2Max") }}
                                                            @elseif($exercise->metricVisual == "reserve")
                                                                {{ Lang::get("content.reserve") }}
                                                            @elseif($exercise->metricVisual == "range")
                                                                {{ Lang::get("content.range") }}
                                                            @elseif($exercise->metricVisual == "max")
                                                                {{ Lang::get("content.max") }}
                                                            @else
                                                                {{ Lang::get("content.exerciseMode")  }}
                                                            @endif
                                                        </p>
                                                    </th>
                                                    <th scope="col" class="speed lastColDay <?php echo"day$x"; ?>">
                                                        <p>{{ Lang::get("content.Spd") }}</p>
                                                        <p>
                                                            (<span class="exercise_units_speed_unit_{{ $exercise->id }}">{{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span>)
                                                        </p>
                                                    </th>
                                                @endfor
                                            </tr>
                                            </thead>
                                            <tbody class="cardio"> <!-- data of cardio exercise -->
                                            @for($y = 0; $y < $exercise->sets; $y++)
                                                <tr>
                                                    <td scope="row" class="set">{{ $y+1 }}</td>
                                                    @for($x = 0; $x < $daysWorkout; $x++)
                                                            <?php $pointer = $exercise->sets * $x + $y; ?>
                                                        @if(array_key_exists($pointer, $setsTemp))
                                                            <td class="time firstColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->time }}</td>
                                                            <td class="dist <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->distance }}</td>
                                                            <td class="hr <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->bpm }}</td>
                                                            <td class="speed lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->speed }}</td>

                                                        @else
                                                            <td class="time firstColDay <?php echo"day$x"; ?>"></td>
                                                            <td class="dist <?php echo"day$x"; ?>"></td>
                                                            <td class="hr <?php echo"day$x"; ?>"></td>
                                                            <td class="speed lastColDay <?php echo"day$x"; ?>"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                                    <?php $restThere = false; ?>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                    @if(array_key_exists($pointer, $setsTemp) and $setsTemp[$pointer]->rest != 0)
                                                            <?php $restThere = true; ?>
                                                    @endif
                                                @endfor

                                                @if($restThere)
                                                    <tr>
                                                        <td class="rest">Rest</td> <!-- Rest of Cardio Exercise -->
                                                        @for($x = 0; $x < $daysWorkout; $x++)
                                                            <?php $pointer = $exercise->sets * $x + $y; ?>
                                                            @if(array_key_exists($pointer, $setsTemp))
                                                                <td colspan="4" class="varRest <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->rest }}</td>
                                                            @else
                                                                <td colspan="4" class="varRest <?php echo"day$x"; ?>"></td>
                                                            @endif
                                                        @endfor
                                                    </tr>
                                                @endif

                                            @endfor
                                            </tbody>
                                        @endif <!-- end cardio exercise in circuit -->

                                    </table>
                                </div>
                            </div> <!-- End of exercise line -->
                            @if(is_array($restTimeBetweenExercises) and  array_key_exists($circuitExercisesCounter, $restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                <div class="restBtwExercise"> <!-- rest between exercises -->
                                    <p>Rest: {{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                </div>
                            @endif

                                <?php $circuitExercisesCounter++; ?>

                        </div>  <!-- End of exercise in circuit-->

                    @endforeach  <!-- end of $exercises as $exercise -->

                    @if($group->rest != 0)
                        <div class="restBtwRounds">
                            Rest between rounds: {{ $group->rest }} sec
                        </div>
                    @endif

                </div> <!-- End of circuit -->

            @else

                @foreach($exercises as $exercise)
                    @if($exercise->exercises->bodygroupId != 18)
                        <!----------------------------------------------------- MUSCLE -------------------------------------------------->
                        <div class="exercise group">
                            <div class="exercise_line">
                                <div class="exInfo">
                                    <div class="titleInfo">
                                        @if($exercise->equipmentId != "")
                                            <span class="titleText">{{ $exercise->exercises->name }} with {{{ $exercise->equipment->name  }}}</span>
                                        @else
                                            <span class="titleText">{{ $exercise->exercises->name }} </span>
                                        @endif
                                    </div>

                                    <div class="imgContainer">
                                        <img class=exImage src="{{ Helper::imageToBase64(public_path(Helper::image($exercise->exercises->image))) }}">
                                        @if($exercise->exercises->image2 != "")
                                            <img class=exImage src="{{ Helper::imageToBase64(public_path(Helper::image($exercise->exercises->image2))) }}">
                                        @endif
                                    </div>
                                    @if($exercise->tempo1 != "" or $exercise->tempo2 != "" or $exercise->tempo3 != "" or $exercise->tempo4 != "")
                                        <div class="exeTempo">
                                            <p>{{ Lang::get("content.Tempo") }}</p>
                                            <p>{{ ($exercise->tempo1 != "" ? $exercise->tempo1 : "-") }}</p>
                                            <p>{{ ($exercise->tempo2 != "" ? $exercise->tempo2 : "-") }}</p>
                                            <p>{{ ($exercise->tempo3 != "" ? $exercise->tempo3 : "-") }}</p>
                                            <p>{{ ($exercise->tempo4 != "" ? $exercise->tempo4 : "-") }}</p>
                                        </div>
                                    @endif
                                    @if($exercise->notes != "")
                                        <p class="printNote">{{{ $exercise->notes }}}</p>
                                    @endif
                                </div>
                                <div class="exTable">
                                    <table class="setsTable">
                                        <thead class="muscle">
                                        <tr>
                                            <th scope="row" class="set">{{ Lang::get("content.Set") }}</th>
                                            @for($x = 0; $x < $daysWorkout; $x++)
                                                <th scope="col"
                                                    class="wgt firstColDay <?php echo"day$x"; ?>">{{ Lang::get("content.Wgt") }}
                                                    @if($exercise->units == "Metric")
                                                        <span>(kg)</span>
                                                    @else
                                                        <span>(lbs)</span>
                                                    @endif
                                                </th>
                                                <th scope="col" class="<?php echo"day$x"; ?>">
                                                    @if($exercise->metricVisual == "rep")
                                                        {{ Lang::get("content.Repetitions")  }}
                                                    @elseif($exercise->metricVisual == "time")
                                                        {{ Lang::get("content.Time")  }} (sec)
                                                    @elseif($exercise->metricVisual == "maxRep")
                                                        {{ Lang::get("content.maxRep")  }}
                                                    @elseif($exercise->metricVisual == "range")
                                                        {{ Lang::get("content.range")  }}
                                                    @else
                                                        {{ Lang::get("content.exerciseMode")  }}
                                                    @endif
                                                </th>
                                            @endfor
                                        </tr>
                                        </thead>
                                            <?php
                                            $sets = $workout->getSets($exercise->id);
                                            $setsTemp = array();
                                            $counter = 0;
                                            $allDone = 1;
                                            //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                                            foreach ($sets as $set) {
                                                $setsTemp[$counter] = $set;
                                                $counter++;
                                            }
                                            ?>
                                        <tbody class="muscle">
                                        @for($y = 0; $y < $exercise->sets; $y++)
                                            <tr>
                                                <td scope="row" class="set">{{ $y+1 }}</td>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                        <?php $pointer = $exercise->sets * $x + $y; ?>
                                                    @if(array_key_exists($pointer, $setsTemp))
                                                        <td class="wgt firstColDay <?php echo"day$x"; ?>">{{ ($setsTemp[$pointer]->weight == "" ? 0 : Helper::formatWeight($setsTemp[$pointer]->weight)) }}</td>
                                                        @if(($exercise->metric == "time" || $setsTemp[$pointer]->metric == "time" || $setsTemp[$pointer]->metric == "temps") and ($setsTemp[$pointer]->metric != "maxRep" and $setsTemp[$pointer]->metric != "range"))
                                                            <td class="rep lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->time }}
                                                                <span>sec</span>
                                                            </td>
                                                        @else
                                                            <td class="time lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->reps }}</td>
                                                        @endif
                                                    @else
                                                        <td class="wgt firstColDay <?php echo"day$x"; ?>"></td>
                                                        <td class="rep_or_time lastColDay <?php echo"day$x"; ?>"></td>
                                                    @endif
                                                @endfor
                                            </tr>


                                            @for($x = 0; $x < $daysWorkout; $x++)
                                                <?php $pointer = $exercise->sets * $x + $y; ?>
                                                <?php $restThere = false; ?>
                                                @if(array_key_exists($pointer, $setsTemp) and $setsTemp[$pointer]->rest != 0)
                                                    <?php $restThere = true; ?>
                                                @endif
                                                @if($restThere)
                                                    <tr>
                                                        <td class="rest">Rest</td>
                                                        @for($x = 0; $x < $daysWorkout; $x++)
                                                                <?php $pointer = $exercise->sets * $x + $y; ?>
                                                            @if(array_key_exists($pointer, $setsTemp))
                                                                <td colspan="2" class="varRest <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->rest }}</td>
                                                            @else
                                                                <td colspan="2" class="varRest <?php echo"day$x"; ?>"></td>
                                                            @endif
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endfor
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if(is_array($restTimeBetweenExercises) and array_key_exists($circuitExercisesCounter,$restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "" and $restTimeBetweenExercises[$circuitExercisesCounter] != 0)
                                <!-- Rest Between Exercise in Circuit -->
                                <div class="circuitRestBtwExe">
                                    {!! Helper::svgToBase64Img('<svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><circle stroke="#2C3E50" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/><path fill="#2C3E50" d="M7 6h4v13H7zM14 6h4v13h-4z"/></g></svg>') !!}
                                    <p>{{ $restTimeBetweenExercises[$circuitExercisesCounter] }} {{ Lang::get("content.secrest") }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                            <?php
                            $sets = $workout->getSets($exercise->id);
                            $setsTemp = array();
                            $counter = 0;
                            $allDone = 1;
                            //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                            foreach ($sets as $set) {
                                $setsTemp[$counter] = $set;
                                $counter++;
                            }

                            ?>
                                <!------------------------------------------------------Cardio------------------------------------------------------------------>
                        <div class="group exercise">
                            <div class="exercise_line">
                                <div class="exInfo">
                                    <div class="titleInfo">
                                        @if($exercise->equipmentId != "")
                                            <span class="titleText">{{ $exercise->exercises->name }} with {{{ $exercise->equipment->name  }}}</span>
                                        @else
                                            <span class="titleText">{{ $exercise->exercises->name }} </span>
                                        @endif
                                    </div>

                                    <div class="imgContainer">
                                        <img class=exImage src="/{{ Helper::image($exercise->exercises->image) }}">
                                        @if($exercise->exercises->image2 != "")
                                            <img class=exImage src="/{{ Helper::image($exercise->exercises->image2) }}">
                                        @endif
                                    </div>
                                    @if($exercise->notes != "")
                                        <p class="printNote">{{{ $exercise->notes }}}</p>
                                    @endif
                                </div>
                                <div class="exTable">
                                    <table class="setsTable">
                                        <thead class="cardio"> <!-- header of cardio exercise -->
                                        <tr>
                                            <th scope="row" class="set">Int</th>
                                            @for($x = 0; $x < $daysWorkout; $x++)
                                                <th scope="col" class="time firstColDay <?php echo"day$x"; ?>">
                                                    <p>{{ Lang::get("content.Time") }}</p>
                                                    <p>({{ Lang::get("content.min") }})</p></th>
                                                <th scope="col" class="dist <?php echo"day$x"; ?>">
                                                    <p>{{ Lang::get("content.Distance") }}</p>
                                                    <p>
                                                        (<span class="exercise_units_speed_unit_{{ $exercise->id }}">{{ ($exercise->units == "Metric") ? Lang::get("content.km") : Lang::get("content.mi") }}</span>)
                                                    </p></th>
                                                <th scope="col" class="hr <?php echo"day$x"; ?>"><p>
                                                        @if($exercise->metricVisual == "hr" || $exercise->metricVisual =='rep')
                                                            {{ Lang::get("content.hr") }}
                                                        @elseif($exercise->metricVisual == "effort")
                                                            {{ Lang::get("content.effort") }}
                                                        @elseif($exercise->metricVisual == "Vo2Max")
                                                            {{ Lang::get("content.Vo2Max") }}
                                                        @elseif($exercise->metricVisual == "reserve")
                                                            {{ Lang::get("content.reserve") }}
                                                        @elseif($exercise->metricVisual == "range")
                                                            {{ Lang::get("content.range") }}
                                                        @elseif($exercise->metricVisual == "max")
                                                            {{ Lang::get("content.max") }}
                                                        @else
                                                            {{ Lang::get("content.exerciseMode")  }}
                                                        @endif
                                                    </p></th>
                                                <th scope="col" class="speed lastColDay <?php echo"day$x"; ?>">
                                                    <p>{{ Lang::get("content.Spd") }}</p>
                                                    <p>
                                                        (<span class="exercise_units_speed_unit_{{ $exercise->id }}">{{ ($exercise->units == "Metric") ? Lang::get("content.km/h") : Lang::get("content.mi/h") }}</span>)
                                                    </p></th>
                                            @endfor
                                        </tr>
                                        </thead>

                                        <tbody class="cardio"> <!-- data of cardio exercise -->
                                        @for($y = 0; $y < $exercise->sets; $y++)
                                            <tr>
                                                <td scope="row" class="set">{{ $y+1 }}</td>
                                                @for($x = 0; $x < $daysWorkout; $x++)
                                                        <?php $pointer = $exercise->sets * $x + $y; ?>
                                                    @if(array_key_exists($pointer, $setsTemp))
                                                        <td class="time firstColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->time }}</td>
                                                        <td class="dist <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->distance }}</td>
                                                        <td class="hr <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->bpm }}</td>
                                                        <td class="speed lastColDay <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->speed }}</td>

                                                    @else
                                                        <td class="time firstColDay <?php echo"day$x"; ?>"></td>
                                                        <td class="dist <?php echo"day$x"; ?>"></td>
                                                        <td class="hr <?php echo"day$x"; ?>"></td>
                                                        <td class="speed lastColDay <?php echo"day$x"; ?>"></td>
                                                    @endif
                                                @endfor
                                            </tr>


                                                <?php $restThere = false; ?>
                                            @for($x = 0; $x < $daysWorkout; $x++)
                                                @if(array_key_exists($pointer, $setsTemp) and $setsTemp[$pointer]->rest != 0)
                                                        <?php $restThere = true; ?>
                                                @endif
                                            @endfor

                                            @if($restThere)
                                                <tr>
                                                    <td class="rest">Rest</td> <!-- Rest of Cardio Exercise -->
                                                    @for($x = 0; $x < $daysWorkout; $x++)
                                                            <?php $pointer = $exercise->sets * $x + $y; ?>
                                                        @if(array_key_exists($pointer, $setsTemp))
                                                            <td colspan="4"
                                                                class="varRest <?php echo"day$x"; ?>">{{ $setsTemp[$pointer]->rest }}</td>
                                                        @else
                                                            <td colspan="4" class="varRest <?php echo"day$x"; ?>"></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            @endif

                                        @endfor
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            @if($group->restAfter != 0 and $group->restAfter != "")
                <div class="restBtwExe group">
                    {!! Helper::svgToBase64Img('<svg width="27" height="27" viewBox="0 0 27 27" xmlns="https://www.w3.org/2000/svg"><g transform="translate(1 1)" fill="none" fill-rule="evenodd"><circle stroke="#4A4A4A" fill="#FFF" cx="12.5" cy="12.5" r="12.5"/><path fill="#4A4A4A" d="M7 6h4v13H7zM14 6h4v13h-4z"/></g></svg>') !!}
                    <p>{{ $group->restAfter }} {{ Lang::get("content.secrest") }}</p>
                </div>
            @endif
        @endforeach
        <!------------------------------------------------------THE END-------------------------------------------------------------------------------- -->
    </div> <!-- holder-wrap -->
</div> <!-- wrapper -->

</body>
</html>
