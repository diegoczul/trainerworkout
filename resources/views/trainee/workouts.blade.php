@php
    use App\Http\Libraries\Helper;
@endphp
@extends('layouts.trainee')

@section("header")
    {!! Helper::seo("traineeWorkouts") !!}
@endsection

@section('content')

<div class="content client">
    <div class="traineeBackgroundFilter"></div>
	<div class="contentContainer">
        <div class="workouts_options workouts_options_down">
            <a title="Archive" href="javascript:void(0)" class="moreOptionsButton" id="archiveWorkouts" onclick="archiveWorkouts()">
                <svg width="20" height="16" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <title>
                        archiveMore
                    </title>
                    <defs>
                        <rect id="a" x="1" y="4" width="18" height="12" rx="1.231"/>
                        <mask id="c" x="0" y="0" width="18" height="12" fill="#fff">
                            <use xlink:href="#a"/>
                        </mask>
                        <rect id="b" y=".8" width="20" height="4" rx="1.231"/>
                        <mask id="d" x="0" y="0" width="20" height="4" fill="#fff">
                            <use xlink:href="#b"/>
                        </mask>
                    </defs>
                    <g stroke="#369AD8" fill="none" fill-rule="evenodd">
                        <use mask="url(#c)" stroke-width="1.6" xlink:href="#a"/>
                        <use mask="url(#d)" stroke-width="1.6" xlink:href="#b"/>
                        <path d="M6.4 7.4h7.2" stroke-width=".8" stroke-linecap="round"/>
                    </g>
                </svg>
            </a>

            <a title="Delete" href="javascript:void(0)" class="moreOptionsButton" id="deleteWorkouts" onclick="deleteWorkouts()">
                <svg width="13" height="18" viewBox="0 0 13 18" xmlns="https://www.w3.org/2000/svg">
                    <title>
                        Delete Icon
                    </title>
                    <g stroke-width=".5" stroke="#369AD8" fill="none" fill-rule="evenodd">
                        <g>
                            <rect y="1.702" width="13" height="1.702" rx=".413"/>
                            <rect x="4.875" width="3.25" height="1.276" rx=".413"/>
                            <path d="M1.22 3.523c0-.23.182-.414.413-.414h9.734c.23 0 .414.187.414.413V16.35c0 .91-.74 1.65-1.65 1.65H2.87a1.65 1.65 0 0 1-1.65-1.65V3.523z"/></path>
                        </g>
                        <g stroke-linecap="square">
                            <path d="M9.14 6.3v8.51M6.5 6.3v8.51M3.86 6.3v8.51"/>
                        </g>
                    </g>
                </svg>
            </a>

        </div>
		<div class="traineeWorkoutContainer" id="w_workoutsTrainee">
			
		</div>
	</div>
</div>

@endsection
@section('scripts')
<script>callWidget("w_workoutsTrainee");</script>


<script type="text/javascript">

</script>
@endsection