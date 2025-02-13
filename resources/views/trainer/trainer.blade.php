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
                      <!-- <a class="editicon fltright" href="/Trainer/EditProfile/">edit</a> -->
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
      <div class="widgets fullwidthwidget shadow clearfix">
       <div class="fltright"><!-- <a href="javascript:void(0)" onClick="toggle('w_addvideoword');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a> --></div>
       <h1>A Word from {{ $user->firstName }}</h1>
            <div id="w_addvideoword" class="add">
                 
                </div>
                    <div id="w_video_word_full" >
                      
                    </div>
                </div>
           
                <div  class="widgets fullwidthwidget shadow clearfix">
                               <div class="fltright"><!-- <a href="javascript:void(0)" onClick="toggle('w_addbiography');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a> --></div>
       <div id="w_addbiography" class="add">
                
                </div>
                  <div id="w_biography_full" >
                  
                  </div>
      
            </div>

         <div class="widgets fullwidthwidget shadow customertestimonial clearfix">
             <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addtestimonials');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
              <h1>Testimonials</h1>
              <div id="w_addtestimonials" class="add">
             
                 {{ View::make("widgets.addfull.testimonials",array("user"=>$user))}}
                </div>
               
               <div id="w_testimonials_full">
                 
               </div>
         </div> 
               
        <div class="widgets fullwidthwidget shadow customertestimonial clearfix">
            <h1>Sessions & Packages for Sale</h1>
           
            <div id="w_add_sessions" style="display:none;">
                
            </div>
            <div id="w_sessions_full"></div>
       <div class="clearfix"></div>
      </div>

      <div class="widgets fullwidthwidget shadow customertestimonial clearfix">
            <h1>Workouts offered by {{ $user->firstName }}</h1>
           
            <div id="w_workoutsTrainer_full"></div>
       <div class="clearfix"></div>
      </div>        

        
        </div>
</section>

@endsection

@section('scripts')

<script>callWidgetExternal("w_video_word_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_testimonials_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_biography_full",null,{{ $user->id }});</script>  
<script>callWidgetExternal("w_sessions_full",null,{{ $user->id }});</script> 
<script>callWidgetExternal("w_workoutsTrainer_full",null,{{ $user->id }});</script> 


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