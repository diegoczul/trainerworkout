 @if ($trendingWorkouts->count() > 0)
  <?php $flag = true; ?>
  @foreach ($trendingWorkouts as $trendingWorkout)

  <div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}">
    <a href="/{{ $trendingWorkout->getURL() }}">
      <div class="overlay_title trending_overlay">
        <span>{{ $trendingWorkout->name }}</span>
      </div>
      <div class="overlay_more trending_overlay">
        <div class="wo_price">{{{$trendingWorkout->price == 0 ? 'Free' : Helper::formatPrice($trendingWorkout->price)}}}</div>
        <span>Preview Workout</span>
      </div>
      <ul class="trendingwrkimgs clearfix">
         <?php $images = $trendingWorkout->getExercisesImagesWidget(); ?>
         <li><a href="/{{ $trendingWorkout->getURL() }}"><img src="/{{ Helper::image($images[0]) }}"/></a></li>
         <li><a href="/{{ $trendingWorkout->getURL() }}"><img src="/{{ Helper::image($images[1]) }}"/></a></li>
         <li><a href="/{{ $trendingWorkout->getURL() }}"><img src="/{{ Helper::image($images[2]) }}"/></a></li>
         <li><a href="/{{ $trendingWorkout->getURL() }}"><img src="/{{ Helper::image($images[3]) }}"/></a></li>
      </ul>
    </a>
  </div>

@endforeach 



 @else
    {{ Messages::showEmptyMessage("TrendingWorkoutsEmpty") }}
@endif

@if($total > $trendingWorkouts->count())
<div class="clearfix"></div>
    <div class="btmbuttonholder">
                <div class="clearfix"></div>
                    <span class="hrborder"></span>
                    <a href="javascript:void(0)" onclick="callWidget('w_trendingWorkouts',{{ $trendingWorkouts->count() }},null,$(this))" class="greybtn">More Workouts</a>
                </div>
@endif

           