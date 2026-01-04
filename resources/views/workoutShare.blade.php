@use(App\Http\Libraries\Helper)
@use(App\Http\Libraries\Messages)
@use(App\Models\ExercisesImages)
@extends('layouts.'.strtolower($user->userType))
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

   ?>
<section id="content" class="clearfix">
   <div class="wrapper">
      <div class="widgets fullwidthwidget shadow">
         @if($sale)
         @if($workout->price == 0)
         <div class="fltright"><a href="/Workout/AddToMyWorkouts/{{ $workout->id }}" class="bluebtn">Add to my workouts</a></div>
         @else
         <div class="fltright"><a href="/Store/addToCart/{{ $workout->id }}/Workout" class="bluebtn"> Buy workout for ${{ $workout->price }}</a></div>
         @endif
         @else
         <div class="fltright"><a href="/Workout/AddToMyWorkouts/{{ $workout->id }}" class="bluebtn">Add to my workouts</a></div>
         @endif
         <h1>{{ $workout->name }}</h1>
         <div class="workoutdays clearfix">
            @if ($sale and $workout->price > 0)
            <div class="workoutsummary clearfix">
               <h1>You are missing <?php  if((count($exercises)-3) > 0){ echo count($exercises)-3; } else { echo 0; } ?> more exercises. To see more of this workout please buy it.</h1>
               <a href="/Store/addToCart/<?php echo $workout->id; ?>/" class="bluebtn fltright">Buy workout for ${{ $workout->price }}</a>
            </div>
            @endif
            @foreach($exercises as $exercise)
            <?php $sum_ex++; ?>
            @if($sum_ex > 3 and $sale and $workout->price > 0)
            <?php continue; ?>
            @endif
            @if($exercise->exercises->bodygroupId != 18)
            <div class="dayitemsdetails clearfix" exercise="" data-exercise-id="{{ $exercise->exercises->id }}">
               <div class="dayworkouts clearfix">
                  <!--  <div class="wo_notification">1</div> -->
                  <div class="workout_image_container">
                     <ul>
                        <li>
                           <img  src="/{{ Helper::image($exercise->exercises->image) }}" alt="{{ $exercise->exercises->name }}">
                        </li>
                        <li>
                           <?php
                              $customImages = ExercisesImages::where("userId",$workout->userId)->where("exerciseId",$exercise->exercises->id)->orderBy("id","Desc")->get();
                              ?>
                           @if(count($customImages) > 0)
                           <?php $lastImageAdded = $customImages[0]; ?>
                           <img id="customImage{{ $exercise->id }}"  src="/{{ Helper::image($lastImageAdded->image) }}" alt="Trainer Workout">
                           @else
                           @if($exercise->exercises->image2 != "")
                           <img  id="customImage{{ $exercise->id }}"  src="/{{ Helper::image($exercise->exercises->image2) }}" alt="Trainer Workout - ">
                           @else
                           <img  src="/{{ Helper::image("") }}" alt="{{ $exercise->exercises->name }}">
                           @endif
                           @endif
                        </li>
                     </ul>
                  </div>
                  <div class="workout_name_container">
                     <h5>{{ $exercise->exercises->name }}</h5>
                  </div>
                  <div class="exerciseAITrainer" style="display: inline-block; margin-left: 10px;">
                     <div class="spanContainer" onclick="openAITrainerChat(this);">
                        <span>AI<br>Trainer</span>
                     </div>
                  </div>
               </div>
               <div class="dayworkoutstable">
                  <table class="tabulardata setsInformation exerciseTable_{{ $exercise->id }}" cellspacing="0" cellpadding="0">
                     <thead>
                        <tr>
                           <th class="date">Date</th>
                           <th>Set</th>
                           <th>Weight</th>
                           <th>Repetition</th>
                           <?php
                              $sets = $workout->getSets($exercise->id);
                              $allDone = 1;
                              ?>
                           @foreach($sets as $set)
                           <?php
                              if($set->completed != 1){
                                $allDone = 0;
                              }

                              ?>
                           @endforeach
                           <th class="filters">
                              <label>
                              <input type="checkbox" onclick='exerciseCompleted({{ $exercise->id }},$(this)); return false;' value="Yes" />
                              <span class="icon"><i class="fa fa-check"></i></span>
                              </label>
                           </th>
                        </tr>
                     </thead>
                     <?php
                        $sum_sets += $exercise->sets;

                        ?>
                     <!-- History -->
                     <!--                   <tr>
                        <td colspan="5" style="background-color:white">
                           <a class='bluebutton moreButton' href='javascript: void(0)' onclick='showMore("{{ $exercise->id }}",$(this))' status='hide'>History</a>
                        </td>
                        </tr> -->
                     <?php  $counter = 0;
                        ?>
                     @foreach($sets as $set)
                     <?php
                        if($set->completed == 1){
                          $sum_sets_2++;
                          $sum_time_2 += $set->rest;
                          $sum_reps_2 += $set->reps;
                        } else {
                          $sum_time += $set->rest;
                          $sum_reps += $set->reps;
                        }
                        ?>
                     <tr class="set_{{ $exercise->id }} {{ ($counter >= count($sets) - $exercise->sets ? "last" : "hide" ) }}">
                     <!-- Date -->
                     @if($counter%$exercise->sets == 0)
                     <td rowspan='{{ $exercise->sets }}'>{{ ($counter >= count($sets) - $exercise->sets ? "Today" : Helper::date($set->updated_at) ) }}</td>
                     @endif
                     <!-- Set Number -->
                     <td>{{ Helper::setNumber($set->number,$set->workoutsExercises->sets)  }}</td>
                     <!-- Weight Input -->
                     <td>
                        <span class="view weight  {{ ($set->completed == 1 ? "" : "hide") }}">
                        {{ ($set->weight == "" ? 0 : Helper::formatWeight($set->weight)." Lbs") }}
                        </span>
                        <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}" >
                        <input name="set_weight[{{ $set->id }}]"  type="text" class="inputbox-small center input edit weight lbs_{{$sum_ex}}" value="{{ ($set->weight == "" ? 0.0 : Helper::formatWeight($set->weight)) }}"  />
                        <!--                         <input name="set_weight[{{ $set->id }}]"  type="text"  class="inputbox-small center input edit weight hide kg_{{$sum_ex}}" value="{{ Helper::formatWeight($set->weightKG) }}"  />-->
                        <span class="lbs_{{$sum_ex}} edit weight unselectable">Lbs</span>
                        <!--                         <span class="kg_{{$sum_ex}}  {{ ($set->completed == 1 ? "" : "hide") }} hide">Kg&nbsp;&nbsp;</span> -->
               </div>
               </td>
               <!-- Repetitions -->
               <td><span class="view reps {{ ($set->completed == 1 ? "" : "hide") }}">{{ $set->reps }}</span>
               <input name=""  type="text"  class="inputbox-small center input edit reps  reps_input {{ ($set->completed == 1 ? "hide" : "") }}" value="{{ $set->reps }}"  />
               <input name=""  type="text"  class="inputbox-small center input edit rest  reps_input hide" value="{{ $set->rest }}"  />
               <input name=""  type="text"  class="inputbox-small center input edit idSet reps_input hide" value="{{ $set->id }}"  />
               <input name=""  type="text"  class="inputbox-small center input edit idExercise reps_input hide" value="{{ $exercise->id }}"  />
               </td>
               <td class="filters">
               <label>
               <input name="set_completed[{{ $set->id }}]" {{ ($set->completed == 1 ? "checked='checked'" : "") }} {{ ($set->completed == 1 ? "disabled='disabled'" : "") }} onClick="completeSingleSet({{ $set->id }},$(this))" type="checkbox" value="Yes" />
               <span class="icon"><i class="fa fa-check"></i></span>
               </label>
               </td>
               </tr>
               <?php $counter++; ?>
               @endforeach
               <tr>
               <td></td>
               <td>
               <a href="javascript:void(0)" class="workout_table_btn" onClick='saveAllAddNewSets($(this),{{ $exercise->id }})'>+ Add Set</a>
               </td>
               <td>
               <!-- <div class="measure_kg_lbs">Lbs</div>
                  <div class="toggle_holder">
                    <input type="checkbox" id="switch_{{$sum_ex}}" name="switch_{{$sum_ex}}" class="switch" onclick="toggleWeight({{$sum_ex}},{{ $exercise->id }})"/>
                    <label for="switch_{{$sum_ex}}"></label>
                  </div>
                  <div class="measure_kg_lbs">Kg</div> -->
               </td>
               <td colspan="2">
               <a href='javascript:void(0)' status="hide" onclick='showMore("{{ $exercise->id }}",$(this))' class="workout_table_btn"><i class="fa fa-clock-o"></i> <span>show</span> history</a>
               </td>
               </tr>
               </table>
            </div>
         </div>
         @else
         <div class="dayitemsdetails clearfix" exercise="">
            <div class="dayworkouts clearfix">
               <h5>{{ $exercise->exercises->name }}</h5>
               <ul>
                  <li>
                     <a href="" class="fancy"><img  src="/{{ Helper::image($exercise->exercises->image) }}"alt="{{ $exercise->exercises->name }}"></a>
                  </li>
                  <li>
                     @if($exercise->exercises->image2 != "")
                     <a href="//fb/ViewExercise//" class="fancy"><img   src="/{{ $exercise->exercises->image2 }}" alt="Trainer Workout - "></a>
                     @endif
                  </li>
               </ul>
            </div>
            <div class="dayworkoutstable">
               <table class="tabulardata setsInformation exerciseTable_{{ $exercise->id }}" cellspacing="0" cellpadding="0">
                  <thead>
                     <tr>
                        <th class="date">Date</th>
                        <th>Heart Rate</th>
                        <th>Speed</th>
                        <th>Distance</th>
                        <?php
                           $sets = $workout->getSets($exercise->id);
                           $allDone = 1;
                           ?>
                        @foreach($sets as $set)
                        <?php
                           if($set->completed != 1){
                             $allDone = 0;
                           }

                           ?>
                        @endforeach
                        <th class="filters">
                           <label>
                           <input type="checkbox" onclick='exerciseCompleted({{ $exercise->id }},$(this)); return false;' value="Yes" />
                           <span class="icon"><i class="fa fa-check"></i></span>
                           </label>
                        </th>
                     </tr>
                  </thead>
                  <?php
                     $sets = $workout->getSets($exercise->id);
                     $sum_sets += $exercise->sets;
                     $counter = 0;
                     ?>
                  @foreach($sets as $set)
                  <?php
                     if($set->completed == 1){
                       $sum_sets_2++;
                       $sum_time_2 += $set->time;
                       $sum_reps_2 += $set->reps;
                     } else {
                       $sum_time += $set->time;
                       $sum_reps += $set->reps;
                     }
                     ?>
                  <tr class="set_{{ $exercise->id }} {{ ($counter >= count($sets) - $exercise->sets ? "last" : "hide" ) }}">
                  <td rowspan='{{ $exercise->sets }}'>{{ ($counter >= count($sets) - $exercise->sets ? "Today" : Helper::date($set->updated_at) ) }}</td>
                  <!-- Heart Rate -->
                  <td>
                     <span class="view heart_beat {{ ($set->completed == 1 ? "" : "hide") }}">{{ ($set->bpm == "" ? 0 : $set->bpm)}} bpm</span>
                     <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}"  >
                     <input name="set_heart_beat[{{ $set->id }}]"  type="text" class="inputbox-small center input edit heart_beat lbs_{{$sum_ex}}" value="{{ ($set->bpm == "" ? 0 : $set->bpm) }}" style="margin-right:-35px !important;padding-right: 35px !important;" />
                     <span class="edit heart_beat unselectable">bpm</span>
            </div>
            </td>
            <!-- Speed -->
            <td>
            <span class="view speed {{ ($set->completed == 1 ? "" : "hide") }}">{{ ($set->speed == "" ? 0 : $set->speed) }} km/h</span>
            <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}">
            <input name="set_speed[{{ $set->id }}]"  type="text"  class=" center input edit speed {{ ($set->completed == 1 ? "hide" : "") }}" value="{{ $set->speed }}"  style="margin-right:-40px !important;padding-right: 40px !important;" />
            <span class="edit speed unselectable">km/h</span>
         </div>
         </td>
         <!-- Distance -->
         <td>
         <span class="view distance {{ ($set->completed == 1 ? "" : "hide") }}">{{ $set->distance }} km</span>
      <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}"  style="display: inherit;" >
         <input name="set_distance[{{ $set->id }}]"  type="text"  class="inputbox-small center input edit distance {{ ($set->completed == 1 ? "hide" : "") }}" value="{{ $set->distance }}"  />
         <span class="edit distance unselectable">km</span>
      </div>
      <input name=""  type="text"  class="inputbox-small center input reps_input edit rest hide" value="{{ $set->rest }}"  />
      <input name=""  type="text"  class="inputbox-small center input reps_input edit idSet hide" value="{{ $set->id }}"  />
      <input name=""  type="text"  class="inputbox-small center input reps_input edit idExercise hide" value="{{ $exercise->id }}"  />
      </td>
      <td class="filters">
      <label>
      <input name="set_completed[{{ $set->id }}]" {{ ($set->completed == 1 ? "checked='checked'" : "") }} onClick="completeSingleSet({{ $set->id }},$(this))" {{ ($set->completed == 1 ? "disabled='disabled'" : "") }}  type="checkbox" value="Yes" />
      <span class="icon"><i class="fa fa-check"></i></span>
      </label>
      </td>
      </tr>
      <?php $counter++; ?>
      @endforeach
      <tr>
      <td>
      <a href="javascript:void(0)" class="workout_table_btn" onClick='saveAllAddNewSets($(this),{{ $exercise->id }})'>+ Add Set</a>
      </td>
      <td></td>
      <td></td>
      <td></td>
      <td>
      <a href='javascript:void(0)' status="hide" onclick='showMore("{{ $exercise->id }}",$(this))' class="workout_table_btn"><i class="fa fa-clock-o"></i> history</a>
      </td>
      </tr>
      </table>
   </div>
   </div>
   @endif
   @endforeach
   @if ($sale and $workout->price > 0)
   <div class="workoutsummary clearfix">
      <h1>You are missing <?php  if((count($exercises)-3) > 0){ echo count($exercises)-3; } else { echo 0; } ?> more exercises. To see more of this workout please buy it.</h1>
      <a href="/Store/addToCart/<?php echo $workout->id; ?>/" class="bluebtn fltright">Buy workout for ${{ $workout->price }}</a>
   </div>
   @endif
   </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript">
   <?php
      if ($sum_sets > 0)
      $sum_ex_2 = number_format($sum_reps_2/$sum_reps,0);

      $sum_time = $sum_time / 60;
      if ($sum_time > 0)
      $sum_time = number_format($sum_time);

      $sum_time_2 = $sum_time_2 / 60;
      if ($sum_time_2 > 0)
      $sum_time_2 = number_format($sum_time_2);
      ?>

   $("#sum_ex").html(<?php echo $sum_ex; ?>);
   $("#sum_sets").html(<?php echo $sum_sets; ?>);
   $("#sum_reps").html(<?php echo $sum_reps; ?>);
   $("#sum_time").html('<?php echo $sum_time; ?> min' );

   $("#sum_ex_2").html(<?php echo $sum_ex_2; ?>);
   $("#sum_sets_2").html(<?php echo $sum_sets_2; ?>);
   $("#sum_reps_2").html(<?php echo $sum_reps_2; ?>);
   $("#sum_time_2").html('<?php echo $sum_time_2; ?> min' );


   function showMore(exerciseId,obj){
     if($(obj).attr("status") == "hide"){
       $(obj).attr("status","visible");
       $(obj).text("Hide");
       $(".set_"+exerciseId).show();
     } else {
       $(".set_"+exerciseId).hide();
       $(".set_"+exerciseId+".last").show();
       $(obj).attr("status","hide");
       $(obj).text("More");

     }

   }

   function viewModeRow(obj){
       var tr = $(obj).closest("tr");
       $(tr).find(".view.reps").text($(tr).find(".edit.reps").val());
       $(tr).find(".view.weight").text($(tr).find(".edit.weight").val());
       $(tr).find(".view.reps").show();
       $(tr).find(".edit.reps").hide();
       $(tr).find(".view.weight").show();
       $(tr).find(".edit.weight").hide();

       $(tr).find(".view.speed").text($(tr).find(".edit.speed").val());
       $(tr).find(".view.distance").text($(tr).find(".edit.distance").val());
       $(tr).find(".view.time").text($(tr).find(".edit.time").val());
       $(tr).find(".view.speed").show();
       $(tr).find(".view.time").show();
       $(tr).find(".view.distance").show();
       $(tr).find(".edit.time").hide();
       $(tr).find(".edit.distance").hide();
       $(tr).find(".edit.speed").hide();
   }

   function editModeRow(obj){
       var tr = $(obj).closest("tr");
       //$(tr).find(".view.reps").text($(tr).find(".edit.reps").val());
       //$(tr).find(".view.weight").text($(tr).find(".edit.weight").val());
       $(tr).find(".view.reps").hide();
       $(tr).find(".edit.reps").show();
       $(tr).find(".view.weight").hide();
       $(tr).find(".edit.weight").show();

       $(tr).find(".view.speed").hide();
       $(tr).find(".edit.speed").show();
       $(tr).find(".view.distance").hide();
       $(tr).find(".edit.distance").show();
       $(tr).find(".view.time").hide();
       $(tr).find(".edit.time").show();

   }

   function editModeAll(obj){
       $(obj).find(".view.reps").hide();
       $(obj).find(".edit.reps").show();
       $(obj).find(".view.weight").hide();
       $(obj).find(".edit.weight").show();

       $(obj).find(".view.speed").hide();
       $(obj).find(".edit.speed").show();
       $(obj).find(".view.distance").hide();
       $(obj).find(".edit.distance").show();
       $(obj).find(".view.time").hide();
       $(obj).find(".edit.time").show();

       //$(obj).find(".view.hr").hide();
       //$(obj).find(".edit.hr").show();
   }

   function viewModeAll(exerciseId){
       $(".exerciseTable_"+exerciseId+" tr").each(function(i,obj){
         $(this).find(".view.reps").text($(this).find(".edit.reps").val());
         $(this).find(".view.weight").text($(this).find(".edit.weight").val());
         $(this).find(".view.reps").show();
         $(this).find(".edit.reps").hide();
         $(this).find(".view.weight").show();
         $(this).find(".edit.weight").hide();

         $(this).find(".view.distance").text($(this).find(".edit.distance").val());
         $(this).find(".view.speed").text($(this).find(".edit.speed").val());
         $(this).find(".view.time").text($(this).find(".edit.time").val());

         $(obj).find(".view.speed").show();
         $(obj).find(".edit.speed").hide();
         $(obj).find(".view.time").show();
         $(obj).find(".edit.time").hide();
         $(obj).find(".view.distance").show();
         $(obj).find(".edit.distance").hide();
         //$(obj).find(".view.hr").show();
         //$(obj).find(".edit.hr").hide();

       });
   }

   function completeSingleSet(setId,obj){
     weight = $(obj).closest("tr").find(".edit.weight").val();
     reps = $(obj).closest("tr").find(".edit.reps").val();
     rest = $(obj).closest("tr").find(".edit.rest").val();
     $.ajax(
           {
               url : "/Workout/saveSingleSet",
               type: "POST",
               data: {
                   set:setId,
                   weight:weight,
                   rest:rest,
                   reps:reps,
                   workoutId: {{ $workout->id }}
               },
               success:function(data, textStatus, jqXHR)
               {
                   successMessage(data);
                   viewModeRow(obj);
               },
               error: function(jqXHR, textStatus, errorThrown)
               {
                   errorMessage(jqXHR.responseText);
               },
           });
   }

   function exerciseCompleted(exerciseWorkout,obj){
     var table = $(obj).closest(".dayitemsdetails").find(".setsInformation");
     $.ajax(
           {
               url : "/Workout/exerciseCompleted",
               type: "POST",
               data: {
                   workoutsExercisesId: exerciseWorkout,
                   workoutId: {{ $workout->id }}
               },
               success:function(data, textStatus, jqXHR)
               {
                   successMessage(data);
                   viewModeRow(obj);
                   table.find("input[type=checkbox]").prop("checked",true);
                   viewModeAll(exerciseWorkout);
               },
               error: function(jqXHR, textStatus, errorThrown)
               {
                   errorMessage(jqXHR.responseText);
               },
           });
   }

   function editModeAllSets(exerciseId,obj){
     var table = $(obj).closest(".dayitemsdetails").find(".setsInformation");
     var moreButton = table.find(".moreButton");
     showMore(exerciseId,moreButton);
     editModeAll(table);

     if($(obj).attr("status") == "edit"){
         $(obj).attr("status","save");
         $(obj).attr("onClick","saveAll("+exerciseId+",$(this))");
         $(obj).text("Click to save all");
     } else {
         $(obj).attr("onCLick","editModeAllSets("+exerciseId+",$(this))");
         $(obj).attr("status","edit");
         $(obj).text("Click to edit all");
     }
   }

   function viewModeAllWorkout(){
         $(".workoutdays").find(".view.reps").text($(this).find(".edit.reps").val());
         $(".workoutdays").find(".view.weight").text($(this).find(".edit.weight").val());

         $(".workoutdays").find(".view.distance").text($(this).find(".edit.distance").val());
         $(".workoutdays").find(".view.speed").text($(this).find(".edit.speed").val());
         $(".workoutdays").find(".view.time").text($(this).find(".edit.time").val());

         $(".workoutdays").find(".view.reps").show();
         $(".workoutdays").find(".edit.reps").hide();
         $(".workoutdays").find(".view.weight").show();
         $(".workoutdays").find(".edit.weight").hide();

         $(".workoutdays").find(".view.distance").show();
         $(".workoutdays").find(".edit.distance").hide();
         $(".workoutdays").find(".view.speed").show();
         $(".workoutdays").find(".edit.speed").hide();
         $(".workoutdays").find(".view.time").show();
         $(".workoutdays").find(".edit.time").hide();

   }


   function saveAll(exerciseId,element){
     var data = [];
     var counter = 0;

     $(".exerciseTable_"+exerciseId+" tr").each(function(i,obj){
       if(counter > 1){
         reps = $(this).find(".edit.reps").val();
         rest = $(this).find(".edit.rest").val();
         weight = $(this).find(".edit.weight").val();
         idSet = $(this).find(".edit.idSet").val();
         idExercise = $(this).find(".edit.idExercise").val();
         completed = 0;
         if($(this).find(".edit.idSet").prop("checked")){
           completed = 1;
         }
         var entry = {"reps":reps,"weight":weight,"rest":rest,"idSet":idSet};
         data.push(entry);

       }
       counter++;
     });
     $.ajax(
           {
               url : "/Workout/saveAllSets",
               type: "POST",
               data: {
                 sets:data,
                 exerciseId:exerciseId,
                 workoutId: {{ $workout->id }}
               },
               success:function(data, textStatus, jqXHR)
               {
                   successMessage(data);
                   viewModeAll(exerciseId);
                   $(element).attr("onCLick","editModeAllSets("+exerciseId+",$(this))");
                   $(element).attr("status","edit");
                   $(element).text("Click to edit all");
               },
               error: function(jqXHR, textStatus, errorThrown)
               {
                   errorMessage(jqXHR.responseText);
               },
           });

   }

   function addWorkoutToMe(el){
     var preLoad = showLoadWithElement(el, 0, 'center');
     $.ajax(
           {
               url : "/Workout/AddToMyWorkouts",
               type: "POST",
               data: {
                 workoutId: {{ $workout->id }}
               },
               success:function(data, textStatus, jqXHR)
               {
                   successMessage(data);
                   hideLoadWithElement(preLoad);
               },
               error: function(jqXHR, textStatus, errorThrown)
               {
                   errorMessage(jqXHR.responseText);
                   hideLoadWithElement(preLoad);
               },
           });
   }

<!-- AI Trainer Chat Modal -->
<div id="aiTrainerModal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div style="position: relative; background-color: #fefefe; margin: 2% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 600px; height: 85vh; display: flex; flex-direction: column; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
            <div>
                <h3 id="aiTrainerExerciseName" style="margin: 0; font-size: 20px; font-weight: 600; color:#FFFFFF">AI Trainer</h3>
                <p style="margin: 5px 0 0 0; font-size: 12px; opacity: 0.9;; color:#ffffff">Ask me anything about this exercise</p>
            </div>
            <button onclick="closeAITrainerChat()" style="background: transparent; border: none; color: white; font-size: 28px; font-weight: 300; cursor: pointer; line-height: 1; padding: 0; width: 30px; height: 30px;">&times;</button>
        </div>
        
        <!-- Chat Messages Container -->
        <div id="aiChatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background-color: #f8f9fa;">
            <!-- Messages will be inserted here -->
        </div>
        
        <!-- Input Area -->
        <div style="padding: 15px; border-top: 1px solid #e0e0e0; background-color: white; border-radius: 0 0 12px 12px;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <input type="text" id="aiChatInput" placeholder="Ask about form, technique, variations..." style="flex: 1; padding: 10px 15px; border: 1px solid #ddd; border-radius: 25px; font-size: 14px; outline: none; height: 38px; box-sizing: border-box;" onkeypress="if(event.key==='Enter') sendAITrainerMessage();">
                <button onclick="sendAITrainerMessage()" id="aiSendBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 24px; border-radius: 25px; cursor: pointer; font-weight: 500; font-size: 14px; transition: opacity 0.3s; height: 38px; box-sizing: border-box;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Send
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .exerciseAITrainer {
        position: absolute;
        width: 50px;
        height: 50px;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .exerciseAITrainer .spanContainer {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }
    
    .exerciseAITrainer .spanContainer:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);
    }
    
    .exerciseAITrainer span {
        color: white;
        font-size: 10px;
        font-weight: 600;
        text-align: center;
        line-height: 1.2;
        text-transform: uppercase;
    }
    
    #aiChatMessages::-webkit-scrollbar {
        width: 6px;
    }
    
    #aiChatMessages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #aiChatMessages::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    #aiChatMessages::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    let currentExerciseId = '';
    let currentExerciseName = '';

    function openAITrainerChat(element) {
        // Find the exercise container
        const exerciseContainer = element.closest('.dayitemsdetails');
        
        // Get exercise ID from data attribute
        currentExerciseId = exerciseContainer.getAttribute('data-exercise-id');
        
        if (!currentExerciseId) {
            alert('Exercise ID not found');
            return;
        }
        
        // Get exercise name from header
        const exerciseNameElement = exerciseContainer.querySelector('.workout_name_container h5');
        currentExerciseName = exerciseNameElement ? exerciseNameElement.textContent.trim() : 'Exercise';
        
        // Update modal title
        document.getElementById('aiTrainerExerciseName').textContent = currentExerciseName;
        
        // Load chat history
        loadChatHistory();
        
        // Show modal
        document.getElementById('aiTrainerModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Focus input
        setTimeout(() => {
            document.getElementById('aiChatInput').focus();
        }, 100);
    }

    function closeAITrainerChat() {
        document.getElementById('aiTrainerModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function loadChatHistory() {
        const messagesContainer = document.getElementById('aiChatMessages');
        messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #999;"><i class="fas fa-spinner fa-spin"></i> Loading chat...</div>';
        
        fetch(`/trainer/exercise-chat/${currentExerciseId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.messages.length === 0) {
                    messagesContainer.innerHTML = `
                        <div style="text-align: center; padding: 40px 20px; color: #999;">
                            <div style="font-size: 48px; margin-bottom: 15px;">💪</div>
                            <p style="font-size: 16px; margin: 0;">Ask me anything about <strong>${currentExerciseName}</strong></p>
                            <p style="font-size: 13px; margin-top: 8px;">Form tips, variations, common mistakes, and more!</p>
                        </div>
                    `;
                } else {
                    messagesContainer.innerHTML = '';
                    data.messages.forEach(msg => {
                        appendMessage(msg.sender, msg.message, false);
                    });
                    scrollToBottom();
                }
            } else {
                messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #e74c3c;">Failed to load chat history</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messagesContainer.innerHTML = '<div style="text-align: center; padding: 20px; color: #e74c3c;">Error loading chat</div>';
        });
    }

    function sendAITrainerMessage() {
        const input = document.getElementById('aiChatInput');
        const message = input.value.trim();
        
        if (!message) return;
        
        const sendBtn = document.getElementById('aiSendBtn');
        const originalBtnText = sendBtn.innerHTML;
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<svg style="width: 16px; height: 16px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        input.disabled = true;
        
        // Show user message immediately
        appendMessage('user', message, true);
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        fetch(`/trainer/exercise-chat/${currentExerciseId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            hideTypingIndicator();
            
            if (data.success) {
                appendMessage('ai', data.aiMessage.message, true);
            } else {
                appendMessage('ai', 'Sorry, I encountered an error. Please try again.', true);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideTypingIndicator();
            appendMessage('ai', 'Sorry, I encountered an error. Please try again.', true);
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalBtnText;
            input.disabled = false;
            input.focus();
        });
    }

    function appendMessage(sender, text, shouldScroll) {
        const messagesContainer = document.getElementById('aiChatMessages');
        const messageDiv = document.createElement('div');
        
        if (sender === 'user') {
            messageDiv.style.cssText = 'display: flex; justify-content: flex-end; margin-bottom: 15px;';
            messageDiv.innerHTML = `
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 16px; border-radius: 18px 18px 4px 18px; max-width: 70%; word-wrap: break-word; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    ${text}
                </div>
            `;
        } else {
            messageDiv.style.cssText = 'display: flex; justify-content: flex-start; margin-bottom: 15px;';
            messageDiv.innerHTML = `
                <div style="background: white; color: #333; padding: 12px 16px; border-radius: 18px 18px 18px 4px; max-width: 70%; word-wrap: break-word; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;">
                    ${text.replace(/\n/g, '<br>')}
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
        
        if (shouldScroll) {
            scrollToBottom();
        }
    }

    function showTypingIndicator() {
        const messagesContainer = document.getElementById('aiChatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typingIndicator';
        typingDiv.style.cssText = 'display: flex; justify-content: flex-start; margin-bottom: 15px;';
        typingDiv.innerHTML = `
            <div style="background: white; padding: 12px 16px; border-radius: 18px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border: 1px solid #e0e0e0;">
                <div style="display: flex; gap: 4px;">
                    <div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.32s;"></div>
                    <div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; animation-delay: -0.16s;"></div>
                    <div style="width: 8px; height: 8px; background: #999; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both;"></div>
                </div>
            </div>
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 80%, 100% { transform: scale(0); }
                40% { transform: scale(1); }
            }
        `;
        if (!document.querySelector('style[data-typing-animation]')) {
            style.setAttribute('data-typing-animation', 'true');
            document.head.appendChild(style);
        }
        
        messagesContainer.appendChild(typingDiv);
        scrollToBottom();
    }

    function hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function scrollToBottom() {
        const messagesContainer = document.getElementById('aiChatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
</script>
