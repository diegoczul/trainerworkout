
<?php $flag = true; ?>
@if ($newWorkouts->count() > 0)
  
@foreach ($newWorkouts as $workout)  
<?php $images = $workout->getExercisesImagesWidget(); ?>

<div class="workout_main_container">

    <a href="/{{$workout->getURL()}}">
      <div class="upper_container">
        <div class="workout_overlay"><span>{{ $workout->name }}</span></div>
        <ul>
            <li><img src="/{{ Helper::image($images[0]) }}"></li>
            <li><img src="/{{ Helper::image($images[1]) }}"></li>
            <li><img src="/{{ Helper::image($images[2]) }}"></li>
            <li><img src="/{{ Helper::image($images[3]) }}"></li>
            <li><img src="/{{ Helper::image($images[4]) }}"></li>
        </ul>
      </div>
    </a>

    <div class="lower_container">
        <div class="workout_info">
            <span>{{ $workout->shares }} People Shared</span>
            <span>Created by {{ (isset($workout->author) ? $workout->author->firstName : 'TrainerWorkout' ) }}</span>
        </div>
        <div class="workout_buttons">
          <a href="/{{$workout->getURL()}}" class="workout_button greybtn">
              <div>{{{  $workout->price == 0 ? 'Free' : Helper::formatPrice($workout->price) }}}</div>
          </a>
        </div>
    </div>

</div>

@endforeach 

@if($newTotal > $newWorkouts->count())
<div class="clearfix"></div>
  <div class="btmbuttonholder">
                <div class="clearfix"></div>
                  <span class="hrborder"></span>
                  <a href="javascript:void(0)" onclick="callWidget{{ Helper::getTypeOfCall($user->id) }}('w_workoutMarket',{{ $newWorkouts->count() }},{{ $user->id }},$(this))" class="greybtn">More Workouts</a>
                </div>
@endif

 @else
    <div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}" style="position:relative">
    {{ Messages::showEmptyMessage("TrendingWorkoutsEmpty") }}
    </div>
@endif
           



                