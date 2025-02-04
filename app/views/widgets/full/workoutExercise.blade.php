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
@if($exercise->exercises->bodygroupId == 18)
<table class="tabulardata setsInformation exerciseTable_{{ $exercise->id }}" cellspacing="0" cellpadding="0">
  <thead>
     <tr>
        <th class="date">Date</th>
        <th>Time</th>
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
     @if($counter%$exercise->sets == 0)
      <td rowspan='{{ $exercise->sets }}'>{{ ($counter >= count($sets) - $exercise->sets ? "Today" : Helper::date($set->updated_at) ) }}</td>
      @endif
     <!-- Speed -->
     <td>
        <span class="view time {{ ($set->completed == 1 ? "" : "hide") }}">{{ ($set->time == "" ? 0 : $set->time)}} min</span>
        <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}"  >
           <input name="set_time[{{ $set->id }}]"  type="text" class="inputbox-small center input edit time lbs_{{$sum_ex}}" value="{{ ($set->time == "" ? 0 : $set->time) }}" style="margin-right:-35px !important;padding-right: 35px !important;" />
           <span class="edit time unselectable">min</span>
        </div>
     </td>
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
        <div class="weight_input edit {{ ($set->completed == 1 ? "hide" : "") }}" >
           <input name="set_distance[{{ $set->id }}]"  type="text"  class="inputbox-small center input edit distance {{ ($set->completed == 1 ? "hide" : "") }}" value="{{ $set->distance }}"  />
           <span class="edit distance unselectable">km</span>
        </div>
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
     <td colspan="2">
        <a href='javascript:void(0)' status="hide" onclick='showMore("{{ $exercise->id }}",$(this))' class="workout_table_btn"><i class="fa fa-clock-o"></i> history</a>
     </td>
  </tr>
</table>
@else

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
 
@endif