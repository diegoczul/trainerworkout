<table width="100%" border="0" class="tabulardata" cellspacing="0" cellpadding="0">
     <tr>
            <th width="25%">Date</th>
            <th width="20%">Set</th>
            <th width="20%">Repetition</th>
            <th width="20%">Weight</th>
            <th width="15%"><strong>Done</strong></th>
     </tr>
     <?php $sets = $workout->getSets($exercise->id); ?>
     @if(count($sets) > $exercise->sets)
      <tr>
            <td colspan="5" style="background-color:white">
            <!--<span class="hrborder"></span>-->
            <a class='bluebutton' href='javascript: void(0)' onclick='showMore("set_1411")'>More</a>
            </td>
       </tr>
     @endif
      <?php $counter = 0; ?>
     @foreach($workout->getSets($exercise->id) as $set)                                 
     <tr class="">

       @if($counter%$exercise->sets == 0)
         <td rowspan='{{ $exercise->sets }}'>{{ Helper::date($set->updated_at) }}</td>    
       @endif                            
       <td>{{ $set->number }}</td>
       <td>{{ $set->reps }}
           <input name="weight_reps"  style="display:none" type="text"  class="inputbox-small center input" value="{{ $set->reps }}"  />
       </td>
       <td>{{ ($set->weight == "" ? 0 : $set->weight) }}
           <input name="weight" style="display:none" type="text"  class="inputbox-small center input" value="{{ $set->weight }}"  />
       </td>
       
       <td><input {{ ($set->completed == 1 ? "checked='checked'" : "") }} {{ ($set->completed == 1 ? "disabled='disabled'" : "") }} type="checkbox" value="" /></td>
     </tr>
     <?php $counter++; ?>
     @endforeach
</table>