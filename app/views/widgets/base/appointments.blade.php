<div class="itemlist row0">
<h3>Old Appointments</h3>
<ul>
@if ($appointmentsOld->count() > 0)

@foreach ($appointmentsOld as $appointment)


        
          <li class="clearfix rowDelete"> <a href="#" class="image fltleft"> <img src='/{{ ($appointment->targetId != "") ? Helper::image($appointment->user->image) : Helper::image("") }}';  alt="Appointment Image"> </a>
           <a href="javascript:void(0)" onClick="deleteAppointments('{{ $appointment->id }}', $(this)); return false;" class="deleteicon2">TEST</a>
            <div class="detail fltright">
         
              <p> <a href="/{{ ($appointment->targetId != "") ? $appointment->user->getURL() : "#" }}"> {{ ($appointment->targetId != "") ? $appointment->user->getCompleteName() : $appointment->name }} </a><br>
              {{ $appointment->appointment }}
              <br>
                <span class='mediumdate'> <strong>From: </strong>{{ $appointment->dateStart }} <br/><strong>To: </strong>{{ $appointment->dateEnd }}</span> </p>
            </div>
          </li>
          
@endforeach

@else

<li><p>{{ Lang::get("messagesNoOldAppointments") }}</p></li>


@endif
</ul>
</div>
<div class="itemlist row1">
<h3>Today's Appointments</h3>
<ul>
@if ($appointmentsToday->count() > 0)

@foreach ($appointmentsToday as $appointment)



       
          <li class="clearfix rowDelete"> <a href="#" class="image fltleft"> <img src='/{{ ($appointment->targetId != "") ? Helper::image($appointment->user->image) : Helper::image("") }}';  alt="Appointment Image"> </a>
           <a href="javascript:void(0)" onClick="deleteAppointments('{{ $appointment->id }}', $(this)); return false;" class="deleteicon2">TEST</a>
            <div class="detail fltright">
         
              <p> <a href="/{{ ($appointment->targetId != "") ? $appointment->user->getURL() : "#" }}"> {{ ($appointment->targetId != "") ? $appointment->user->getCompleteName() : $appointment->name }} </a><br>
              {{ $appointment->appointment }}
              <br>
                <span class='mediumdate'> <strong>From: </strong>{{ $appointment->dateStart }} <br/><strong>To: </strong>{{ $appointment->dateEnd }}</span> </p>
            </div>
          </li>
          
@endforeach


@else

<li><p>{{ Lang::get("messagesNoTodayAppointments") }}</p></li>

@endif
</ul>
</div>
<div class="itemlist row0">
<h3>New Appointments</h3>
<ul>
@if ($appointmentsNew->count() > 0)

@foreach ($appointmentsNew as $appointment)

        

        
          <li class="clearfix rowDelete"> <a href="#" class="image fltleft"> <img src='/{{ ($appointment->targetId != "") ? Helper::image($appointment->user->image) : Helper::image("") }}';  alt="Appointment Image"> </a>
           <a href="javascript:void(0)" onClick="deleteAppointments('{{ $appointment->id }}', $(this)); return false;" class="deleteicon2">TEST</a>
            <div class="detail fltright">
         
              <p> <a href="/{{ ($appointment->targetId != "") ? $appointment->user->getURL() : "#" }}"> {{ ($appointment->targetId != "") ? $appointment->user->getCompleteName() : $appointment->name }} </a><br>
              {{ $appointment->appointment }}
              <br>
                <span class='mediumdate'> <strong>From: </strong>{{ $appointment->dateStart }} <br/><strong>To: </strong>{{ $appointment->dateEnd }}</span> </p>
            </div>
          </li>
          
@endforeach

@else

<li><p>{{ Lang::get("messagesNoNewAppointments") }}</p></li>

@endif
</ul>
</div>

    <script>

    function deleteAppointments(id,obj){
         $.ajax(
            {
                url : "/widgets/appointments/"+id,
                type: "DELETE",

                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    widgetsToReload.push("w_appointments");
                    refreshWidgets();
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
            });
    }

    </script>


@if($appointmentsOldTotal > $appointmentsOld->count() || $appointmentsTodayTotal > $appointmentsToday->count() || $appointmentsNewTotal > $appointmentsNew->count())
		
  <div class="btmbuttonholder">
  	<span class="hrborder"></span>
 <a href="javascript:void(0)" onclick="callWidget('w_appointments',{{ $appointmentsOld->count()+$appointmentsToday->count()+$appointmentsNew->count() }},null,$(this))" class="greybtn">More Appointments</a>
                </div>
@endif



            