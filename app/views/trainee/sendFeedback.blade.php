@extends('layouts.Trainee')

 <!--------------------------    Page for consumer to send feedback to TrainerWorkout     ---------------------------->



@section('content')

<div class="content">
	<div class="contentContainer">





	</div>
</div>







<div id="overlayBase"></div>
<div class="fb_overlay">
    <svg class="c-menu__close" width="15" height="15" viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" onclick="">
        <title>
            Close Icon
        </title>
        <path class="closeIcon" d="M7.5 4.865L3.536.9a1.874 1.874 0 0 0-2.65 2.65L4.85 7.516.916 11.45a1.874 1.874 0 1 0 2.65 2.65L7.5 10.166l3.934 3.934a1.874 1.874 0 1 0 2.65-2.65L10.15 7.514l3.965-3.964A1.874 1.874 0 0 0 11.465.9L7.5 4.865z" fill-opacity=".52" fill="#FFF" fill-rule="evenodd"/></path>
    </svg>
    <div class="feedbackContainer">
        <h1>Thank you!</h1>
        <h2>Your feedback help us make Trainer Workout Better!</h2>
        <form>
            <label for="feedback">feedback</label>
            <input type="text" placholder="the message that will help us make Trainer Workout better for you!" name="feedback" id="feedback">
        </form>
        <button>Send</button>
    </div>
</div>


























@endsection