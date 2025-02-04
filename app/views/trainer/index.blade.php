@extends('layouts.trainer')
@section('headerScripts')
<script>
window.location = '/Trainer/Workouts';
</script>
@endsection
@section('content')
<section id="content" class="clearfix">
   <div class="wrapper dashboardone clearfix">
      <div class="widgets fullwidthwidget shadow clearfix">
         <div class="fltright"><a href="/Trainer/Settings"   class="bluebtn"><i class="fa fa-plus"></i>Feed Settings</a></div>
         <h1>Clients Feed</h1>
         <div id="w_feedClients_full">
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="widgets fullwidthwidget shadow clearfix">
         <div class="calendarwrapper fltleft">
            <div class="toprow">
               <!-- <div class="fltright"><a href="#" class="bluebtn"><i class="fa fa-plus"></i>Book Appoitnments</a></div>
                  <div class="fltright"><a href="#" class="bluebtn availabilitiesbtn"> <span></span> Set Availabilities</a></div> -->
               <h1>Calendar (Coming soon... Not yet functional)</h1>
            </div>
            <div id="w_calendar_full"></div>
         </div>
      </div>
      <div class="clearfix"></div>
      
      <div class="clearfix"></div>
      <?php /* 
         <div class="widwrapper clearfix">
         <!--Workouts on the Market-->
         <div class="widgets threefourthwidget fltleft shadow marginleftnone">
         <h1>Workout Market</h1>
                       <div id="w_workoutMarket"></div>
         
         <div class="clearfix"></div>
         <!--sessionsalewrapper-->
         
         </div>
         <!--Sessions & Packages for Sale-->
         
         <div class="widgets onethirdwidthwidget fltright shadow ">
         <h1>Sessions & Packages for Sale</h1>
         <a class="bluebtn blocklement" onclick="toggle('w_add_sessions')" href="javascript:void(0)">Add Session / Package</a>
         <div id="w_add_sessions" style="display:none;">
            {{ View::make("widgets.add.sessions")}}  
         </div>
         <div id="w_sessions"></div>
         <div class="clearfix"></div>
         </div></div> 
         
         <?php */ ?>
      <?php /* 
         <div class="widgets threefourthwidget fltleft shadow marginleftnone">
         <h1>Workout Sales</h1>
          <div class="clearfix"></div>
                 
         <div id="w_workoutSales"></div>
               
               </div> 
           
         <?php */ ?>
      <div class="clearfix"></div>
   </div>
   </div>
</section>
@endsection
@section('scripts')
<script>//callWidget("w_workoutMarket");</script> 
<script>callWidget("w_calendar_full");</script> 
<script>//callWidget("w_tasks");</script> 
<script>//callWidget("w_appointments");</script> 
<script>callWidget("w_feedClients_full");</script> 
<script>//callWidget("w_sessions");</script> 
<script>//callWidget("w_workoutSales");</script> 
<script type="text/javascript">
   $(document).ready(function(){ $("#m_dashboard").addClass('active'); });
</script>
@endsection