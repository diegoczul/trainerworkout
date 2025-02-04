<?php 
    $layoutToLoad = null;
    if(Auth::check()){
        $layoutToLoad = strtolower(Auth::user()->userType); 
    } else{
        $layoutToLoad = "visitor"; 
    }
?>
@extends('layouts.'.$layoutToLoad)

@section('content')
<section id="content" class="clearfix">

		<div class="wrapper">

        	

            <div class="widgets threefourthwidget shadow marginleftnone">

            	

            	<h1>Your Membership Manager</h1>

     
          
            @if(count($cart["items"]) > 0)
          
            @foreach($cart["items"] as $cartObject)

              
            @if($cartObject["type"] == "Workout")
            <div class="latestworkouts clearfix">
            <?php $workout = Workouts::find($cartObject["id"]); ?>
            <?php $images = $workout->getExercisesImagesWidget(); ?>
            <div class="latestworkoutswrap clearfix">
							
                    	<h3>{{ $workout->name }} <span class="price">
                      @if($workout->price != "")
                        ${{ $workout->price }}
                      @else
                        Free
                      @endif
                        </span></h3>

                        <div class="ltworkouts">

                    	<div class="imagethumb132">

                        	<a href="#">

                   	    	<img src="/{{ Helper::image($images[0]) }}" alt="latest workouts"></a>

                        </div>

                        

                  </div>

                        <div class="ltworkouts">

                            <div class="imagethumb132">

                                <a href="#">

                                <img src="/{{ Helper::image($images[1]) }}" alt="latest workouts"></a>

                            </div>

                            

                      </div>

                        <div class="ltworkouts">

                            <div class="imagethumb132">

                                <a href="#">

                                <img src="/{{ Helper::image($images[2]) }}" alt="latest workouts"></a>

                            </div>

                            

                      </div>

                        <div class="ltworkouts">

                            <div class="imagethumb132">

                                <a href="#">

                                <img src="/{{ Helper::image($images[3]) }}" alt="latest workouts"></a>

                            </div>

                            

                      </div>

                        <div class="ltworkouts">

                            <div class="imagethumb132">

                                <a href="#">

                                

                                <img src="/{{ Helper::image($images[4]) }}" alt="latest workouts"> </a>

                            </div>

                            

                      </div>

                      <div class="clearfix"></div>

                      <div class="cartitemdetails clearfix">

                      	<div class="authordetails">

                        	<table width="100%" class="tabulardata" border="0" cellspacing="0" cellpadding="0">

                                  <tr>

                                    <th width="40%">Author</th>

                                    <td><a href="" class="blue">{{ $workout->authors()->firstName." ".$workout->authors()->lastName }}</a></td>

                                  </tr>

                               <!--   <tr>

                                    <th>Focus</th>

                                    <td>Lower legs</td>

                                  </tr>-->

                                  <tr>

                                    <th>Category</th>

                                    <td>{{ $workout->category }}</td>

                                  </tr>

                                  

                                </table>

                        </div>
						
                        <div class="itemdetails">
                        	<table width="100%" class="tabulardata" border="0" cellspacing="0" cellpadding="0">
                          <?php
                          $sets = 0;
                          $reps = 0;
                          $time = 0;
                          $exercises = 0;
                          $workoutExercises = $workout->getExercises()->get();
                          $exercises = count($workoutExercises);

                          foreach($workoutExercises as $workoutExercise){
                              $templates = $workout->getTemplateSets($workoutExercise->id);
                              $sets += count($templates);
                              foreach($templates as $template){
                                
                                $reps += $template->reps;
                                $time += $template->reps;
                                $time += $template->rest;
                              }
                          }
                          $time = $time / 60;
                          ?>
                              <tr>
                                <td style="background-color:#FFF; border:0px;"></td>
                                <th >Workout</th>
                               
                              </tr>
                              <tr>
                                <th># of exercises</th>
                                <td id="sum_ex">{{ $exercises }}</td>
                                 
                              </tr>
                              <tr>
                                <th># of sets</th>
                                <td id="sum_sets">{{ $sets }}</td>
                  
                              </tr>
                              <tr>
                                <th># of reps</th>
                                <td id="sum_reps">{{ $reps }}</td>
                        
                              </tr>
                              <tr>
                                <th>Estimated Time</th>
                                <td id="sum_time"> {{ $time }}min</td>
                          
                              </tr>
                            </table>
                        </div>
                </div>

                      </div>

                      <!--<a class="bluebtn fltright" href="#">Preview Workout</a>-->

                      <div class="clear"></div>

                    </div>

              @elseif ($cartObject["type"] == "Session")
              
              <?php $session = TrainerSessions::find($cartObject["id"]); ?>
              <div class="latestworkoutswrap clearfix">
              
                      <h3>{{ $session->name }} <span class="price">${{ $session->price }}</span></h3>

                        <div class="ltworkouts">

                      <div class="imagethumb132">

                          <a href="#">

                          <img src="/{{ Helper::image("") }}" alt="latest workouts"></a>

                        </div>

                        

                  </div>
                  </div>
              
              @else
              <?php $membership = Memberships::find($cartObject["id"]); ?>
              <div class="latestworkoutswrap clearfix">
                  
                     <h3>{{ $membership->name }} <span class="price">
                      @if($membership->price != "")
                        ${{ $membership->price }}
                      @else
                        Free
                      @endif
                        </span></h3>

                        <div class="ltworkouts">

                      <div class="imagethumb132 showDelete">
                          <a href="/Store/removeItem/{{ $cartObject["identifier"] }}" class="deleteicon2"></a>
                          <a href="#">

                          <img src="/{{ Helper::image("") }}" alt="latest workouts"></a>

                        </div>

                        

                  </div>
                  </div>
              @endif
       @endforeach     
       
		  @else

      <p>{{ Lang::get("messagesEmtpyCart") }}</p>
		 
     @endif
			 

                </div>


                

          

            <div class="onethirdwidthwidget">

            <div class="widgets onethirdwidthwidget shadow">

                <h1>Order Summary</h1>

                <table width="100%" class="tabulardata ordertable" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td>Subtotal</td>

                    <td><strong>${{ $cart["subtotal"] }}</strong></td>

                  </tr>

                 <!--  <tr>

                    <td>Taxes</td>

                    <td><strong>$</strong></td>

                  </tr> -->

                  <tr>

                    <td>Total</td>

                    <td><strong>${{ $cart["total"]  }}</strong></td>

                  </tr>

                </table>

                <div class="workoutreviews clearfix">

                	<a class="bluebtn blocklement" href="/Store/Checkout/">Checkout</a>

                </div>

            </div>

            <div class="widgets onethirdwidthwidget shadow" style="display:none">

            	<h1>Trending Workouts</h1>

               <div id="w_trendingWorkouts">
                
                </div>
            </div>

            <div class="clearfix"></div>

        </div>
        </div>

    </section>

@endsection
@section("scripts")
<script>//callWidget("w_trendingWorkouts");</script>
@endsection
