<div class="workoutsblock marginleftnone">
<h3>Free Workouts</h3>
<?php $flag = true; ?>
@if ($freeWorkouts->count() > 0)
  
@foreach ($freeWorkouts as $workout)  
<?php $images = $workout->getExercisesImagesWidget(); ?>
  <div class="trendingworkout notopborder">
    <?php $flag = false; ?>
    <a href="/{{ $workout->getURL() }}">
      <div class="overlay_title trending_overlay">
        <span>{{ $workout->name }}</span>
      </div>
      <div class="overlay_more trending_overlay">
        <div class="wo_price">{{{$workout->price == 0 ? 'Free' : Helper::formatPrice($workout->price)}}}</div>
        <span>Preview Workout</span>
      </div>
      <ul class="trendingwrkimgs clearfix">
         <?php $images = $workout->getExercisesImagesWidget(); ?>
         <li><a href="/{{ $workout->getURL() }}"><img src="/{{ Helper::image($images[0]) }}"/></a></li>
         <li><a href="/{{ $workout->getURL() }}"><img src="/{{ Helper::image($images[1]) }}"/></a></li>
         <li><a href="/{{ $workout->getURL() }}"><img src="/{{ Helper::image($images[2]) }}"/></a></li>
         <li><a href="/{{ $workout->getURL() }}"><img src="/{{ Helper::image($images[3]) }}"/></a></li>
      </ul>
    </a>
  </div>

@endforeach 
 @else
    <div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}" style="position:relative">
    {{ Messages::showEmptyMessage("TrendingWorkoutsEmptyTrainer") }}
    </div>
@endif
               </div>
                <!-- bottom button holder -->
             
                
			

				

<div class="workoutsblock ">
<h3>Paid Workouts</h3>
  <?php $flag = true; ?>
@if ($paidWorkouts->count() > 0)

@foreach ($paidWorkouts as $workout)	
<?php $images = $workout->getExercisesImagesWidget(); ?>
<div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}">
                <div class="trendingworkouts">
               <?php $flag = false; ?>
                <h6><span>
                  <?php 
                      $output = "<a href='' class='bluebtn'>View</a>";
                      if($workout->sale == 1){
                          $link = $workout->getURL();
                          if($workout->price == 0){
                             $output = "<a href='/".$link."' class='bluebtn'>Free</a>";
                          } else {
                             $output = "<a href='/".$link."' class='bluebtn'>$ ".$workout->price."</a>";
                          }
                         
                      }
                  ?>
                  {{ $output }}
                </span>{{ $workout->name }}</h6>
                </div>
                <ul class="trendingwrkimgs clearfix">
                
                  <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[0]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[1]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[2]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[3]) }}" alt=""/></a></li>
                </ul>
                <div class="workoutreviews clearfix">
                  <a href="/{{ $link  }}" class="bluebtn fltright">Preview Workout</a>
                    <span style='font-size:10px'>
                    {{ $workout->shares }} People Shared
                   
                  </span>
                </div>
               </div>
@endforeach 
 @else
    <div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}" style="position:relative">
    {{ Messages::showEmptyMessage("TrendingWorkoutsEmptyTrainer") }}
    </div>
@endif


               </div>
                

<div class="workoutsblock ">
<h3>New Workouts</h3>
  <?php $flag = true; ?>
@if ($paidWorkouts->count() > 0)

@foreach ($paidWorkouts as $workout)  
<?php $images = $workout->getExercisesImagesWidget(); ?>
<div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}">
                <div class="trendingworkouts">
               <?php $flag = false; ?>
                <h6><span>
                  <?php 
                      $output = "<a href='' class='bluebtn'>View</a>";
                      if($workout->sale == 1){
                          $link = $workout->getURL();
                          if($workout->price == 0){
                             $output = "<a href='/".$link."' class='bluebtn'>Free</a>";
                          } else {
                             $output = "<a href='/".$link."' class='bluebtn'>$ ".$workout->price."</a>";
                          }
                         
                      }
                  ?>
                  {{ $output }}
                </span>{{ $workout->name }}</h6>
                </div>
                <ul class="trendingwrkimgs clearfix">
                
                  <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[0]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[1]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[2]) }}" alt=""/></a></li>
                    <li><a href="/{{ $link  }}"><img src="/{{ Helper::image($images[3]) }}" alt=""/></a></li>
                </ul>
                <div class="workoutreviews clearfix">
                  <a href="/{{ $link  }}" class="bluebtn fltright">Preview Workout</a>
                    <span style='font-size:10px'>
                    {{ $workout->shares }} People Shared
                   
                  </span>
                </div>
               </div>

@endforeach 
 @else
    <div class="trendingworkout {{ (($flag)? "notopborder" : "" )  }}" style="position:relative">
    {{ Messages::showEmptyMessage("TrendingWorkoutsEmptyTrainer") }}
    </div>
@endif
               </div>
               
    
                </div>
            
                </div>

@if($paidTotal > $paidWorkouts->count() || $freeTotal > $freeWorkouts->count() || $newTotal > $newWorkouts->count())
                <div class="clearfix"></div>
                <div class="btmbuttonholder">
                	<span class="hrborder"></span>
                	<a href="javascript:void(0)" onclick="callWidget('w_workoutMarket',{{ $paidWorkouts->count() }},null,$(this))" class="greybtn">More Workouts</a>
                </div>
                </div>
@endif

