@extends('layouts.trainer')

@section("header")
    {{ Helper::seo("trainerMemebership") }}
@endsection


@section('content')
 
 <section id="content" class="contenttoptouch clearfix">
    	<div class="bannerholder">
          <div class="wrapper clearfix">
              <div class="profileimage">
                  <img src="/{{ Helper::image(Auth::user()->thumb) }}" alt="profile image">
                </div>
                <div class="profieldetails">
                  <h1>{{ $user->firstName }}</h1>
                     <h3>
                      <a class="editicon fltright" href="/Trainee/EditProfile/">{{ Lang::get("content.edit") }}</a>
                      {{ Lang::get("content.TrainingwithTrainerWorkoutsince") }}: {{ Helper::date($user->created_at) }}
                     </h3>
                     <ul class="clearfix">
                      <li>{{ $user->lastName }}</li>
                        <li>
                          @if($user->birthday != "")
                            {{ Helper::getAge($user->birthday)}} {{ Lang::get("content.yearsold") }}
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
            	 <h6 class="lightblue">{{ Lang::get("content.Upgradeyourmembership") }}</h6>
           
    
               @foreach($memberships as $membership)


               <div class="membershipContainer">
                  <a href="/Store/addToCart/{{ $membership->id }}/Membership">{{ $membership->name }} {{ $membership->price }} </a> {{ (isset($membershipsSelected) && $membershipsSelected->membershipId == $membership->id) ? "Current" : "" }}
               </div>

               @endforeach


    </div>
        	
          
          
        
         
            
            
        </div>
    </section>
 
    @endsection

@section('scripts')
<script>
$(document).ready(function(){
      $(".chosen-select").trigger("chosen:updated");
});

$(document).ready(function(){
    $(".menu_membership").addClass("selected");
});


</script>

@endsection