{{ HTML::style('css/innerStyles.css') }}
{{ HTML::style('fw/jquery-ui-1.11.1.custom/jquery-ui.min.css'); }}
{{ HTML::style('fw/chosen_v1/chosen.css'); }}
{{ HTML::style('fw/autocomplete/foxycomplete.css'); }}
    
@extends('layouts.popup')

@section('content')     
     <!--Begin dropdownmenu--> 
        {{ Form::open(array('url' => 'widgets/availabilities/addEdit')); }}
       <div class="popup_container sharewokoutform">
            <div class="header">
                <div class="upper_header"><h1>Adding an Appointment</h1></div>
            </div>

            <div class="calendar">
                <!-- TAB: SHARE -->
                <section  id="tab_search" class="share_search">
                      <div class="input_container">
                          <!-- Type -->
                          <div class="input_label">What is your appointment about?</div>
                          <select name="type" class="chosen-select">
                            <option value="Availability" style="color:#000">Availability</option>
                            <option value="Appointment" style="color:#000">Appointment</option>
                          </select>

                          <!-- Name -->
                          <div class="input_label">What is your appointment about?</div>
                          <input type="text" name='appointment' placeholder="New Appointment"  autocomplete="off" required>

                          <!-- To -->
                          <div style="float:right;width:50%">
                            <div class="input_label">To</div>
                            <input  style="width:100px" type="text" class="mdwidthinput datepicker" name="dateEnd" placeholder="Date" value="{{ date("Y-m-d",strtotime($end)) }}"/> 
                            <input  style="width:100px" type="text" class="mdwidthinput time" name="timeEnd" placeholder="Time" required/>
                          </div>

                          <!-- From -->
                          <div style="float:right;width:50%">
                            <div class="input_label">From</div>
                            <input  style="width:100px" type="text" class="mdwidthinput datepicker" name="dateStart" placeholder="Date" value="{{ date("Y-m-d",strtotime($start)) }}"/> 
                            <input  style="width:100px" type="text" class="mdwidthinput time" name="timeStart" placeholder="Time" w/>
                          </div>

                          <!-- Friend -->
                          <div class="input_label">Search a target client</div>
                          <input type="hidden" id="appointmentTarget" name="appointmentTarget">
                          <input type="text" class="fullwidthinput" name="searchAppointmentTarget" id="searchAppointmentTarget" autocomplete="off" placeholder="Client’s name (optional)" />
                          <div class="friendholder"></div>
                      </div>
                      <div class="button_container">
                          <input type="hidden" class="mdwidthinput" value="yes" name="status"  />
                          <input name="" type="submit"  value="Save" class="ajaxSaveFancyBox save" widget="w_appointments">
                      </div>
                </section>
            </div>
       </div>
       {{ Form::close() }}
@endsection

@section("scripts")
<script>
     $(function() {

        $( "#searchAppointmentTarget" ).autocomplete({
                source: "/widgets/people/suggestWithClient",
                minLength: 2,
                response: function(event,ui){
                     if (ui.content.length === 0) {
                        $("#resultsTarget").text("No results found");
                        $("#appointmentTarget").val("");

                    }
                },
                select: function( event, ui ) {
                    $( "#searchAppointmentTarget" ).val( ui.item.firstName+" "+ui.item.lastName );

                    $( "#appointmentTarget" ).val( ui.item.id );
                    return false;
                }
        }) .autocomplete( "instance" )._renderItem = function( ul, item ) {
            $("#resultsTarget").text("");
            var image = "/img/default.gif";
            if(item.thumb != null){
                image = "/"+item.thumb;
            }
            return $( "<li style='cursor:pointer' class='clientinfo marginleftnone clearfix'>" )
            .append( "<a class='image fltleft'><img width:45; height:45; src='"+image+"'/></a><div class='detail'>" + item.firstName + "<br>" + item.lastName + "</div>")
            .appendTo( ul );
        };
    });


</script>
@endsection