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
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta charset="UTF-8">
      <title>Training workout Print</title>
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- Mobile Specific Metas here
         ================================================== -->
      <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> -->

      <meta name="title" content="">
      <meta name="description" content="">

      {{ HTML::style('css/printTable.css') }}

      <style>
      body{
        /* background-color: #FFF!important;*/
      }
          @page { margin: 0px; }
          body { margin: 0px; }
      </style>

   </head>
   <body>

      @foreach($workouts as $workout)
      @if($workout = Workouts::find($workout))
      <?php
         
        $groups = $workout->getGroups()->get();
        $exercises = $workout->getExercises()->get();
      ?>
      <div class="wrapper" style="margin-bottom:20px;">

         <div class="widgets fullwidthwidget shadow" style="background-color:#FFF !important">
            <div class="border print clearfix">
               <!-- Headers start -->
               <div class="header clearfix">
               
                  <table class="header">
                        <tr>
                           <td class="logo">
                              @if($user->activeLogo)
                              <img class="tw" src="{{ URL::secure("/".$user->activeLogo->image) }}" />
                              @else
                              <img class="tw" src="{{ URL::secure("/img/logo_signature.jpg") }}" />
                              @endif
                           </td>

                           <td class="header">
                              <!-- <span class="aurthor"><strong>Client:</strong> {{ $workout->user->getCompleteName() }}</span> -->
                              <span class="aurthor"><strong>{{ Lang::get("content.Workout") }}:</strong> {{ $workout->name }}</span>
                           </td>
                           <td class="header">
                              <span class="aurthor"><strong>{{ Lang::get("content.Author") }}:</strong> {{ $workout->author->getCompleteName() }}</span>
                           </td>
                           <td class="header">
                              <span class="aurthor"><strong>{{ Lang::get("content.Email") }}:</strong> {{ $workout->author->email }}</span>
                           </td>
                           <td class="header">
                              
                             
                           </td>
                        </tr>
                     </table>

               </div>
                
               <!-- Header end adsfadsf-->
           
               <section class="section clearfix">
                  <div class="holder-wrap">
            @foreach($groups as $group)
            <?php
               
               $exercises = $group->getExercises()->get();
               $restTimeBetweenExercises = unserialize($group->restBetweenCircuitExercises);
               if(!is_array($restTimeBetweenExercises)) $restTimeBetweenExercises= array();
               $circuitExercisesCounter = 0;

              
            ?>
             @if(count($exercises) > 1)
             <div class="circuit width">

               <table class="mainTable">
                  <tr> <!-- header of table -->
                        <td class="ccHead">{{ Lang::get("content.repeatCircuit") }} {{ $group->intervals }} {{ Lang::choice("content.workout/times",$group->intervals ) }} @if($group->rest != 0) | {{ Lang::choice("content.restCircuit",$group->intervals) }}: {{ $group->rest }} sec @endif</td>
                  </tr>

@foreach($exercises as $exercise)
            <tr><td>
             <table class="mainTable circuitTable">
                

               <tr>
                  <td class="exInfo">
                   @if($exercise->equipmentId != "")
                                       <div class="titleInfo">
                                          <img class="equip" src="{{ URL::secure(Helper::image($exercise->equipment->thumb )) }}">
                                          <span class="titleText">{{ $exercise->exercises->name }} with {{{ $exercise->equipment->name  }}}</span>
                                       </div>
                                    @else
                                       {{ $exercise->exercises->name }}
                                    @endif
                    <table class="description">
                                    <tr> <!-- exercise description -->
                                          <td class = "exInfo"> 
                                             <div class="container">
                                             
                                             <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image)) }}">
                                             @if($exercise->exercises->image2 != "")
                                                <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image2)) }}">
                                             @endif
                                             
                                              @if($exercise->notes != "")
                                              <div class="imgLine"></div>
                                                <p class="exNotes"><strong>Notes: </strong>{{{ $exercise->notes }}}</p>
                                                @else
                                                
                                              @endif
                                             
                                          </div>
                                       </td>
                                    </tr>

                     </table>
                  </td>
                  <td>
                  <table class="setsTable">    
                  <!---------------------------------------- CIRCUIT 1 ---------------------------------------------->
               
                  
                     @if($exercise->exercises->bodygroupId != 18)
                     <!-- muscle exercise on circuit -->
                     <tr> 
                           <td class="ccSet">{{ Lang::get("content.Set") }}</td>
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                           <td class="ccWgt">{{ Lang::get("content.Wgt") }}</td>
                           @if($exercise->metric == "reps")
                              <td class="ccReps">{{ Lang::get("content.Reps") }}</td>
                           @else
                              <td class="ccReps">{{ Lang::get("content.Time") }}</td>
                           @endif
                         </tr>
                         
                        <?php 
                           $sets = $workout->getSets($exercise->id); 
                           $setsTemp = array();
                           $counter = 0;
                           $allDone = 1;
                           //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                           foreach($sets as $set){
                              $setsTemp[$counter] = $set;
                              $counter++;
                           }

                        ?>
                        @for($y = 0; $y < $exercise->sets; $y++)
                         <tr> 
                              <td class="varSets">{{ $y+1 }}</td>
                              @for($x = 0; $x < 7; $x++)
                                 <?php $pointer = $exercise->sets*$x+$y; ?>
                                 @if(array_key_exists($pointer, $setsTemp))
                                    <td class="varWgt">{{ ($setsTemp[$pointer]->weight == "" ? 0 : Helper::formatWeight($setsTemp[$pointer]->weight)) }}</td>
                                    @if($exercise->metric == "reps")
                                       <td class="varReps">{{  $setsTemp[$pointer]->reps }}</td>
                                    @else
                                       <td class="varReps">{{ $setsTemp[$pointer]->time }}</td>
                                    @endif
                                 @else
                                    <td class="varWgt"></td>
                                    <td class="varReps"></td>
                                 @endif
                              @endfor
                           </tr>
                          
                           <tr>
                              <td class="rest">Rest</td>
                              @for($x = 0; $x < 7; $x++)
                                 <?php $pointer = $exercise->sets*$x+$y; ?>
                                 @if(array_key_exists($pointer, $setsTemp))
                                     <td colspan="2" class="varRest">{{ $setsTemp[$pointer]->rest }}</td>
                                 @else
                                     <td colspan="2" class="varRest"></td>
                                 @endif
                              @endfor
                           </tr>
                       
                        @endfor
                        
                        <?php $circuitExercisesCounter++; ?>
                          @else
                          <?php 
                           $sets = $workout->getSets($exercise->id); 
                           $setsTemp = array();
                           $counter = 0;
                           $allDone = 1;
                           //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                           foreach($sets as $set){
                              $setsTemp[$counter] = $set;
                              $counter++;
                           }

                        ?>
                          <!-- cardio exercise on circuit -->
                        <tr> <!-- header of table -->
                              <!-- <td class="ccType">Cardio</td> -->
                              <td class="ccSet">Int</td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }}{{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>{{ Lang::get("content.bpm") }}
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }}{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }}{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }}({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                              <td class="ccTimeDist"><span class="tag1">{{ Lang::get("content.Time") }} ({{ Lang::get("content.min") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Dist") }} (km)</span></td>
                              <td class="ccHrSpd"><span class="tag1">{{ Lang::get("content.HR") }} ({{ Lang::get("content.bpm") }})</span><div class="line"></div><span class="tag2">{{ Lang::get("content.Spd") }} (km/h)</span></td>
                           </tr> 
                           <!-- body -->
                           @for($y = 0; $y < $exercise->sets; $y++)
                         <tr> 
                              <td class="varSets">{{ $y+1 }}</td>
                              @for($x = 0; $x < 7; $x++)
                                 <?php $pointer = $exercise->sets*$x+$y; ?>
                                 @if(array_key_exists($pointer, $setsTemp))
                                    <td class="varTimeDist"><span class="tag1">{{ $setsTemp[$pointer]->time  }}</span><div class="line"></div><span class="tag2">{{ $setsTemp[$pointer]->distance  }}</span></td>
                                       <td class="varHrSpd"><span class="tag1">{{ $setsTemp[$pointer]->bpm  }}</span><div class="line"></div><span class="tag2">{{ $setsTemp[$pointer]->speed  }}</span></td>

       
                                 @else
                                    <td class="varTimeDist"><div class="line"></div></td>
                                    <td class="varHrSpd"><div class="line"></div></td>
                                 @endif
                              @endfor
                           </tr>
                          

                           <tr>
                              <td class="rest">{{ Lang::get("content.Rest") }}</td>
                              @for($x = 0; $x < 7; $x++)
                                 <?php $pointer = $exercise->sets*$x+$y; ?>
                                 @if(array_key_exists($pointer, $setsTemp))
                                     <td colspan="2" class="varRest">{{ $setsTemp[$pointer]->rest }}</td>
                                 @else
                                     <td colspan="2" class="varRest"></td>
                                 @endif
                              @endfor
                           </tr>
                       
                        @endfor
                           
                       @endif
                     <!-- muscle exercise on circuit -->

                
                
                
                   </table>
                     </td>
                     </tr>
                     @if(array_key_exists($circuitExercisesCounter, $restTimeBetweenExercises) and $restTimeBetweenExercises[$circuitExercisesCounter] != "")
                        <tr><td colspan="2" class="placeholder"></td></tr>
                        <tr> <!-- rest between exercises -->
                           <td class="ccRest" colspan=16>{{ Lang::get("content.Rest") }}: {{ $restTimeBetweenExercises[$circuitExercisesCounter] }} secs</td>
                        </tr>
                        @endif
                  </table></td></tr>

                  
                  @endforeach
                  </table>
                  </div>
                  <div class="marginTable"></div>
             @else

@foreach($exercises as $exercise)
               @if($exercise->exercises->bodygroupId != 18)
                  <!----------------------------------------------------- MUSCLE -------------------------------------------------->
                  <div class="width">
                     <table class="mainTable marginTable">
                        
                        <tr>
                           <td class="imageTable">
                           @if($exercise->equipmentId != "")
                                                <div class="titleInfo">
                                                   <img class="equip" src="{{ URL::secure(Helper::image($exercise->equipment->thumb )) }}">
                                                   <span class="titleText">{{ $exercise->exercises->name }} {{ Lang::get("content.with") }} {{{ $exercise->equipment->name  }}}</span>
                                                </div>
                                             @else
                                                {{ $exercise->exercises->name }}
                                             @endif
                              <table class="description">
                                    <tr> <!-- exercise description -->
                                          <td class = "exInfo"> 
                                             <div class="container">
                                             
                                             <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image)) }}">
                                             @if($exercise->exercises->image2 != "")
                                                <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image2)) }}">
                                             @endif
                                             
                                              @if($exercise->notes != "")
                                              <div class="imgLine"></div>
                                                <p class="exNotes"><strong>Notes: </strong>{{{ $exercise->notes }}}</p>
                                                @else
                                               
                                              @endif
                                             
                                          </div>
                                       </td>
                                    </tr>
                              </table>
                           </td>
                           <td>  
                              <table class="setsTable">
                                       <tr> <!-- header of table -->
                                          
                                          <td class="hSet">Set</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                          <td class="hWgt">Wgt</td>
                                          <td class="hReps">Reps</td>
                                       </tr> 
                                       
                                       <?php 
                                    $sets = $workout->getSets($exercise->id); 
                                    $setsTemp = array();
                                    $counter = 0;
                                    $allDone = 1;
                                    //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                                    foreach($sets as $set){
                                       $setsTemp[$counter] = $set;
                                       $counter++;
                                    }


                                 ?>
                                 @for($y = 0; $y < $exercise->sets; $y++)
                                  <tr> 
                                       <td class="varSets">{{ $y+1 }}</td>
                                       @for($x = 0; $x < 7; $x++)
                                          <?php $pointer = $exercise->sets*$x+$y; ?>
                                          @if(array_key_exists($pointer, $setsTemp))
                                             <td class="varWgt">{{ ($setsTemp[$pointer]->weight == "" ? 0 : Helper::formatWeight($setsTemp[$pointer]->weight)." Lbs") }}</td>
                                             @if($exercise->metric == "reps")
                                                <td class="varReps">{{  $setsTemp[$pointer]->reps }}</td>
                                             @else
                                                <td class="varReps">{{ $setsTemp[$pointer]->time }}</td>
                                             @endif
                                          @else
                                             <td class="varWgt"></td>
                                             <td class="varReps"></td>
                                          @endif
                                       @endfor
                                    </tr>
                                   
                                    <tr>
                                       <td class="rest">Rest</td>
                                       @for($x = 0; $x < 7; $x++)
                                          <?php $pointer = $exercise->sets*$x+$y; ?>
                                          @if(array_key_exists($pointer, $setsTemp))
                                              <td colspan="2" class="varRest">{{ $setsTemp[$pointer]->rest }}</td>
                                          @else
                                              <td colspan="2" class="varRest"></td>
                                          @endif
                                       @endfor
                                    </tr>
                                
                                 @endfor

                                    
                                </table>
                           </td>
                  </tr>
                  </table>
                     
                    </div>
               @else
                <?php 
                           $sets = $workout->getSets($exercise->id); 
                           $setsTemp = array();
                           $counter = 0;
                           $allDone = 1;
                           //REGULAR FOREACH WONT WORK BECAUSE ROWS ARE NOW HORIZONTAL :( SO WE NEED TO GET THEM INTO AN ARRAY :P
                           foreach($sets as $set){
                              $setsTemp[$counter] = $set;
                              $counter++;
                           }

                        ?>
                  <!------------------------------------------------------Cardio------------------------------------------------------------------>  
                  <div class="width">
                     <table class="mainTable marginTable">
                        
                     <tr>

                       <td class="imageTable">
                        @if($exercise->equipmentId != "")
                                                <div class="titleInfo">
                                                   <img class="equip" src="{{ URL::secure(Helper::image($exercise->equipment->thumb )) }}">
                                                   <span class="titleText">{{ $exercise->exercises->name }} with {{{ $exercise->equipment->name  }}}</span>
                                                </div>
                                             @else
                                                {{ $exercise->exercises->name }}
                                             @endif
                              <table class="description">
                                    <tr> <!-- exercise description -->
                                          <td class = "exInfo"> 
                                             <div class="container">
                                             
                                             <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image)) }}">
                                             @if($exercise->exercises->image2 != "")
                                                <img class=exImage src="{{ URL::secure(Helper::image($exercise->exercises->image2)) }}">
                                             @endif
                                             
                                              @if($exercise->notes != "")
                                              <div class="imgLine"></div>
                                                <p class="exNotes"><strong>Notes: </strong>{{{ $exercise->notes }}}</p>
                                                @else
                                               
                                              @endif
                                             
                                          </div>
                                       </td>
                                    </tr>
                              </table>
                           </td>

                        <td>
                           <table class="setsTable">
                                 
                              <tr> <!-- header of table -->
                                 <td class="hSet">Int</td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                                 <td class="hTimeDist"><span class="tag1">Time</span><div class="line"></div><span class="tag2">Dist</span></td>
                                 <td class="hHrSpd"><span class="tag1">HR</span><div class="line"></div><span class="tag2">Spd</span></td>
                              </tr> 
                              <!-- body -->
                              @for($y = 0; $y < $exercise->sets; $y++)
                              <tr> 
                                 <td class="varSets">{{ $y+1 }}</td>
                                    @for($x = 0; $x < 7; $x++)
                                       <?php $pointer = $exercise->sets*$x+$y; ?>
                                       @if(array_key_exists($pointer, $setsTemp))
                                          <td class="varTimeDist"><span class="tag1">{{ $setsTemp[$pointer]->time  }}</span><div class="line"></div><span class="tag2">{{ $setsTemp[$pointer]->distance  }}</span></td>
                                          <td class="varHrSpd"><span class="tag1">{{ $setsTemp[$pointer]->bpm  }}</span><div class="line"></div><span class="tag2">{{ $setsTemp[$pointer]->speed  }}</span></td>  
                                       @else
                                          <td class="varTimeDist"><span class="tag1"></span><div class="line"></div><span class="tag2"></span></td>
                                          <td class="varHrSpd"><span class="tag1"></span><div class="line"></div><span class="tag2"></span></td>
                                       @endif
                              @endfor
                              </tr>
                              
                          
                           <tr>
                              <td class="rest">Rest</td>
                              @for($x = 0; $x < 7; $x++)
                                 <?php $pointer = $exercise->sets*$x+$y; ?>
                                 @if(array_key_exists($pointer, $setsTemp))
                                     <td colspan="2" class="varRest">{{ $setsTemp[$pointer]->rest }}</td>
                                 @else
                                     <td colspan="2" class="varRest"></td>
                                 @endif
                              @endfor
                           </tr>
                           

                        @endfor
                       </table>
                        </td>
                     </tr>
                     </table>
                    </div> 
               @endif
               @endforeach

             @endif
              @endforeach

            

      


 
      




<!------------------------------------------------------------------------THE END------------------------------------------------------------------------------------------------------------ -->

                  </div> <!-- holder-wrap -->
               </section> <!-- section clearfix -->
            </div> <!-- border print clearfix -->
         </div> <!-- widgets -->
      </div> <!-- wrapper -->
      @endif
@endforeach
   </body>
</html>
