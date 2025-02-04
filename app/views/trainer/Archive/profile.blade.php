@extends('layouts.trainer')

@section('content')
<section id="content" class="clearfix contenttoptouch">
<div class="bannerholder">
          <div class="wrapper clearfix">
              <div class="profileimage">
                  <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
                </div>
                <div class="profieldetails">
                  <h1>{{ $user->firstName }}</h1>
                     <h3>
                      <a class="editicon fltright" href="/Trainer/EditProfile/">edit</a>
                      Training with TrainerWorkout since: {{ Helper::date($user->created_at) }}
                     </h3>
                     <ul class="clearfix">
                      <li>{{ $user->lastName }}</li>
                        <li>
                        <?php //dd($user); ?>
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
      <div class="widgets fullwidthwidget shadow clearfix word">
       <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addvideoword');" class="bluebtn"><i class="fa fa-pencil"></i>Edit</a></div>
       <h1>A Word from {{ $user->firstName }}</h1>
            <div id="w_addvideoword" class="add">
                  {{ View::make("widgets.addfull.addVideoWord",array("user"=>$user))}}
                </div>
                    <div id="w_video_word_full" >
                      
                    </div>
                </div>
               
                <div  class="widgets fullwidthwidget shadow clearfix bio">
                               <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addbiography');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
                               <h1>Information</h1>
       <div id="w_addbiography" class="add">

                 {{ View::make("widgets.addfull.biography",array("user"=>$user))}}
                </div>
                  <div id="w_biography_full" >
                
                  </div>
      
            </div>
         <div class="widgets fullwidthwidget shadow customertestimonial clearfix testimonial">
             <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addtestimonials');" class="bluebtn"><i class="fa fa-plus"></i>Edit</a></div>
              <h1>Testimonials</h1>
              <div id="w_addtestimonials" class="add">
                 {{ View::make("widgets.addfull.testimonials",array("user"=>$user))}}
                </div>
               
               <div id="w_testimonials_full">
                 
               </div>
         </div> 
               
                

          <div class="widgets fullwidthwidget shadow clearfix objectives">
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addobjectives');" class="bluebtn"><i class="fa fa-plus"></i>Add Objective</a></div>
              <h1>Objective</h1>
              <div id="w_addobjectives" class="add">
              {{ View::make("widgets.addfull.objectives")}}
              </div>
               <div id="w_objectives_full">
         
              </div>
                
                
              <!-- bottom button holder -->
                
          </div>
          <div class="widgets fullwidthwidget shadow clearfix weight">
              <div class="fltright"><a href="javascript:void(0)" onClick="toggle('w_addweights');" class="bluebtn"><i class="fa fa-plus"></i>Add Weight</a></div>
              <h1>Weight</h1>
               
                <div id="w_addweights" class="add">
                {{ View::make("widgets.addfull.weight")->with("user",$user)}}
                </div>
                <div id="w_weights_full">
              
                </div>
                
          </div>
          
          
          <div class="widgets fullwidthwidget shadow marginleftnone bodyM">
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)"  onClick="toggle('w_addmeasurements')"><i class="fa fa-plus"></i>Add Body  Measurement</a></div>
              <h1>Body Measurement</h1>
              <div class="bodymeasurements clearfix">
              <div id="w_addmeasurements" class="add">
              {{ View::make("widgets.addfull.measurements")->with("user",$user)}}
         
                </div>
                
               <div id="w_measurements_full">
                <div id="w_body">
       
                </div>
                </div>
               

                </div>
            </div>
            <div class="widgets fullwidthwidget shadow marginleftnone pictures">
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)" onClick="w_addpictures($(this))"><i class="fa fa-plus"></i>Add Pictures</a></div>
              <h1>Pictures</h1>
                <div id="w_addpictures" class="add">
                 {{ View::make("widgets.addfull.pictures")}}
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

<script>callWidget("w_video_word_full");</script> 
<script>callWidget("w_testimonials_full");</script> 
<script>callWidget("w_biography_full");</script> 
<script>callWidget("w_weights_full");</script> 
<script>callWidget("w_objectives_full");</script> 
<script>callWidget("w_pictures_full");</script> 
<script>callWidget("w_measurements_full");</script>


<script type="text/javascript">
$(document).ready(function(){ $("#m_profile").addClass('active'); });
</script>


@endsection