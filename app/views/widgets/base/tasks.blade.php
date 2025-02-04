<div class="itemlist row1">
        <h3>Past Due</h3>

        <ul>
@if ($tasksOld->count() > 0)
<?php $count = 0; ?>
@foreach ($tasksOld as $task)
          <li class="clearfix rowDelete row{{ $count%2 }}">
          <a href="javascript:void(0)" onClick="deleteTask('{{ $task->id }}', $(this)); return false;" class="deleteicon2"></a>
            <div class="fltleft client"> <a href="#" class="image fltleft">  <img src="/{{ ($task->targetId != "") ? Helper::image($task->user->image) : Helper::image("") }}" alt="Client Image" style="width: auto; min-height: 80px;" /></a>
              <div class="clienttitle fltright"> <span>{{ ($task->targetId != "") ? $task->user->getCompleteName() : $task->name }}<br>
                </span> </div>
            </div>
            <div class="detail fltright">
              <form>
                <fieldset>
                  
                  <label for="">Reminder: {{ $task->value }}<br></label>
                  <span class='mediumdate'> <strong>When: </strong>{{ $task->dateStart }} {{ ($task->dateEnd != "" ? "<br/><strong>To: </strong>".$task->dateEnd : "") }}</span> <input type="checkbox"  onClick="completeTask({{ $task->id }},$(this));" id="" {{ ($task->completed != "" ? "checked='checked'" : "") }} name="cc" class="fltright" />
                    <!--<a href="#" class="editicon"></a>-->
                </fieldset>
                <fieldset>
                  <p><strong>Type: </strong>{{ $task->type }}</p>
                </fieldset>
              </form>
            </div>
          </li>
          <?php $count++; ?>
@endforeach

@else

<li><p>{{ Lang::get("messages.NoTodayAppointments") }}</p></li>

@endif
        </ul>
</div>

<div class="itemlist row1">
        <h3>Today</h3>
        <ul>
@if ($tasksToday->count() > 0)
<?php $count = 0; ?>
@foreach ($tasksToday as $task)
          <li class="clearfix rowDelete row{{ $count%2 }}">
          <a href="javascript:void(0)" onClick="deleteTask('{{ $task->id }}', $(this)); return false;" class="deleteicon2"></a>
            <div class="fltleft client"> <a href="#" class="image fltleft">  <img src="/{{ ($task->targetId != "") ? Helper::image($task->user->image) : Helper::image("") }}" alt="Client Image" style="width: auto; min-height: 80px;" /></a>
              <div class="clienttitle fltright"> <span>{{ ($task->targetId != "") ? $task->user->getCompleteName() : $task->name }}<br>
                </span> </div>
            </div>
            <div class="detail fltright">
              <form>
                <fieldset>
                  
                  <label for="">Reminder: {{ $task->value }}<br></label>
                  <span class='mediumdate'> <strong>When: </strong>{{ $task->dateStart }} {{ ($task->dateEnd != "" ? "<br/><strong>To: </strong>".$task->dateEnd : "") }}</span> <input type="checkbox"  onClick="completeTask({{ $task->id }},$(this));" id="" {{ ($task->completed != "" ? "checked='checked'" : "") }} name="cc" class="fltright" />
                    <!--<a href="#" class="editicon"></a>-->
                </fieldset>
                <fieldset>
                  <p><strong>Type: </strong>{{ $task->type }}</p>
                </fieldset>
              </form>
            </div>
          </li>
          <?php $count++; ?>
@endforeach

@else

<li><p>{{ Lang::get("messages.NoTodayAppointments") }}</p></li>

@endif
        </ul>
</div>

<div class="itemlist row1">
        <h3>Upcoming</h3>


        <ul>
@if ($tasksNew->count() > 0)
<?php $count = 0; ?>
@foreach ($tasksNew as $task)
          <li class="clearfix rowDelete row{{ $count%2 }}">
          <a href="javascript:void(0)" onClick="deleteTask('{{ $task->id }}', $(this)); return false;" class="deleteicon2"></a>
            <div class="fltleft client"> <a href="#" class="image fltleft">  <img src="/{{ ($task->targetId != "") ? Helper::image($task->user->image) : Helper::image("") }}" alt="Client Image" style="width: auto; min-height: 80px;" /></a>
              <div class="clienttitle fltright"> <span>{{ ($task->targetId != "") ? $task->user->getCompleteName() : $task->name }}<br>
                </span> </div>
            </div>
            <div class="detail fltright">
              <form>
                <fieldset>
                  
                  <label for="">Reminder: {{ $task->value }}<br></label>
                  <span class='mediumdate'> <strong>When: </strong>{{ $task->dateStart }} {{ ($task->dateEnd != "" ? "<br/><strong>To: </strong>".$task->dateEnd : "") }}</span> <input type="checkbox" onClick="completeTask({{ $task->id }},$(this));" {{ ($task->completed != "" ? "checked='checked'" : "") }} id="" name="cc" class="fltright" />
                    <!--<a href="#" class="editicon"></a>-->
                </fieldset>
                <fieldset>
                  <p><strong>Type: </strong>{{ $task->type }}</p>
                </fieldset>
              </form>
            </div>
          </li>
          <?php $count++; ?>
@endforeach

@else

<li><p>{{ Lang::get("messages.NoTodayAppointments") }}</p></li>

@endif
         
        </ul>
</div>
  <script>

    function deleteTask(id,obj){
         $.ajax(
            {
                url : "/widgets/tasks/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_tasks");
                    refreshWidgets();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
            });
    }

    function completeTask(id,obj){
        var handler = $(obj);
         $.ajax(
            {
                url : "/widgets/tasks/completeTask",
                type: "post",
                data: {task: id},
                beforeSend:function() 
                {
                    preLoad = showLoadWithElement(handler,20,"right");
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

    </script>


@if($tasksOldTotal > $tasksOld->count() || $tasksTodayTotal > $tasksToday->count() || $tasksNewTotal > $tasksNew->count())
    
  <div class="btmbuttonholder">
    <span class="hrborder"></span>
 <a href="javascript:void(0)" onclick="callWidget('w_tasks',{{ $tasksOld->count()+$tasksToday->count()+$tasksNew->count() }},null,$(this))" class="greybtn">More Appointments</a>
                </div>
@endif



