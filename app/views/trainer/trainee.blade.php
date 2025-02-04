@extends('layouts.trainer')

@section('content')
<section id="content" class="clearfix contenttoptouch">
<div class="bannerholder">
          <div class="wrapper clearfix">
              <div class="profileimage">
                  <img src="/{{ Helper::image($user->thumb) }}" alt="profile image">
                </div>
                <div class="profieldetails">
                  <h1>{{ $user->firstName }}</h1>
                     <h3>
                      Training with TrainerWorkout since: {{ Helper::date($user->created_at) }}
                     </h3>
                     <ul class="clearfix">
                      <li>{{ $user->lastName }}</li>
                        <li>
                          @if($user->birthday != "")
                            {{ Helper::getAge($user->birthday)}} years old
                          @endif
                        </li>
                        <li>{{ $user->email }}</li>
                        <li>{{ $user->phone }}</li>
                     </ul>
                </div>
            </div>
          
        </div>
    <div class="wrapper">
    <div class="widwrapper clearfix">
            <div class="widgets threefourthwidget shadow marginleftnone">
             
              <h1>Latest Workout</h1>

                
                  <div id="w_workouts">
                    
                  </div>
                
                
            </div>
            <div class="widgets onethirdwidthwidget shadow">
              <h1>Trending Workouts</h1>
                <div id="w_trendingWorkouts">
               
            
                </div>
            </div>
            </div>
          <div class="widgets fullwidthwidget shadow clearfix">

              @if($permissions["add"])
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addobjectives');" class="bluebtn"><i class="fa fa-plus"></i>Add Objective</a></div>
              @endif
              <h1>Objective</h1>
              <div id="w_addobjectives" class="add">
              {{ View::make("widgets.addfull.objectives")->with("permissions",$permissions)}}
              </div>
               <div id="w_objectives_full">
         
              </div>
                
                
              <!-- bottom button holder -->
                
          </div>
          <div class="widgets fullwidthwidget shadow clearfix">
              @if($permissions["add"])
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addweights');" class="bluebtn"><i class="fa fa-plus"></i>Add Weight</a></div>
              @endif
              <h1>Weight</h1>
               
                <div id="w_addweights" class="add">
                {{ View::make("widgets.addfull.weight")->with("permissions",$permissions)}}
                </div>
                <div id="w_weights_full">
              
                </div>
                
          </div>
          
          
          <div class="widgets fullwidthwidget shadow marginleftnone">
            @if($permissions["add"])
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)"  onClick="toggle('w_addmeasurements')"><i class="fa fa-plus"></i>Add Body  Measurement</a></div>
            @endif
              <h1>Body Measurement</h1>
              <div class="bodymeasurements clearfix">
              <div id="w_addmeasurements" class="add">
              {{ View::make("widgets.addfull.measurements")->with("permissions",$permissions)}}
         
                </div>
                
               <div id="w_measurements_full">
                <div id="w_body">
       
                </div>
                </div>
               

                </div>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone">
            @if($permissions["add"])
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)" onClick="w_addpictures($(this))"><i class="fa fa-plus"></i>Add Pictures</a></div>
            @endif
              <h1>Pictures</h1>
                <div id="w_addpictures" class="add">
                 {{ View::make("widgets.addfull.pictures")->with("permissions",$permissions)}}
                </div>
                <div id="w_pictures_full">
   

                </div>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone" style="display:none">
              
              <h1>Past Transactions</h1>
                <table width="100%" border="0" class="tabulardata transactionlist" cellspacing="0" cellpadding="0">
                      <tr>
                        <th width="15%">Transaction Id</th>
                        <th width="20%">Type</th>
                        <th width="25%">Item</th>
                        <th width="15%">Status</th>
                        <th width="25%">Payment method</th>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row1">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row1">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                      <tr class="row0">
                        <td>134987</td>
                        <td>workout</td>
                        <td>Marathon essential</td>
                        <td>Paid</td>
                        <td>Master Card</td>
                      </tr>
                    </table>
                
                <!-- bottom button holder -->
                <div class="btmbuttonholder">
                  <span class="hrborder"></span>
                  <a href="#" class="greybtn">More Transactions</a>
                </div>
            </div>
            
        </div>
</section>
@endsection

@section('scripts')
<script>callWidgetExternal("w_workouts",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_weights_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_objectives_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_pictures_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_measurements_full",null,{{ $user->id }});</script> 
<script>callWidget("w_trendingWorkouts");</script>

<script type="text/javascript">
$(document).ready(function(){ $("#m_profile").addClass('active'); });
</script>


@if(isset($newUser) and $newUser)
 <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '400688060094236', {
    em: '{{ Auth::user()->email }},'
    });
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=400688060094236&ev=PageView&noscript=1"
    /></noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
@endif

@endsection