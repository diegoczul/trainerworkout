<div class="lightBox typeOfWorkout" onclick="hidelightbox(event)";>
    <div class="popup_container" >
        <div class="header">
            <div class="upper_header"><h1>{{ Lang::get("content.addWorkoutTo") }} {{ $user->user->firstName }}{{ Lang::get('content.sProfile') }}</h1></div>
            <div class="lower_header">
                <ul>
    
                </ul>
            </div>
        </div>

        <div class="share_content">
            <div class="input_container">
                <div class="newWorkoutOptions">
                    <a href="{{ Lang::get("routes./Trainer/CreateWorkout/Client/").$client->user->id }}">{{ Lang::get('content.createNewWorkoutForClient')}}</a>
                    <label>or</label>
                    <a href="javascript:void(0)" onclick="loadWorkouts()">{{ Lang::get('content.startFromLibrary')}}</a>
                    <label>or</label>
                    <a href="javascript:void(0)" onclick="loadWorkoutsAdd()">{{ Lang::get('content.addFromLibrary')}}</a>
                </div>
            </div>
            <div class="workoutsLibrary">

            
                <div class="widget searchWorkout" style="display:none">
                    <h1>{{ Lang::get("content.SearchWorkouts") }}</h1>
                    <p>{{ Lang::get("content.workouts/message1") }}</p>
                
                    <div class="search_group">
                        <input type="text" placeholder="{{ Lang::get("content.searchworkouts") }}" id="searchWorkouts" name="searchWorkouts" class="inputBox input_search_workout"  onkeyup="searchWorkouts(this.value)" />  

                        <a class="searchButton" href="javascript:void(0)" onclick="searchWorkouts($('#searchWorkouts').val())">{{ Lang::get("content.Search") }}</a>

                        <div class="hide-show_tags" id="showButton"><a href="javascript:void(0)" onClick="show()" >{{ Lang::get("content.workouts/message2") }}</a></div>   
                        <div class="hide-show_tags" id="hideButton"><a href="javascript:void(0)" onClick="hide()">{{ Lang::get("content.workouts/message3") }}</a></div>
                        <script> $('#showButton').show(); $('#hideButton').hide(); $("#w_tags").hide();</script>
                    </div>
                </div>
            

                <div class="addingWorkouts" id="w_workoutsLibrary">
                    <!-- Load the wokrouts here -->
                </div>
            </div>
        </div>
    </div>
</div>


<div class="lightbox_mask" onclick="hidelightbox();"></div>


{{ HTML::script(asset('assets/fw/awesomplete-gh-pages/awesomplete.js')); }}
{{ HTML::script(asset('assets/js/twLightbox.js')); }}


<script type="text/javascript">


function loadWorkouts() {
    $('.input_container').hide();
    $('.typeOfWorkout').addClass("typeOfWorkout-activated");
    $('.workoutsLibrary').show();
    callWidget("w_workoutsLibrary",null,{{  Auth::user()->id }},null,{ client:<?php echo $client->id;?>, startFrom:true });
}


function loadWorkoutsAdd() {
    $('.input_container').hide();
    $('.typeOfWorkout').addClass("typeOfWorkout-activated");
    $('.workoutsLibrary').show();
    callWidget("w_workoutsLibrary",null,{{  Auth::user()->id }},null,{ client:<?php echo $client->id; ?>, addWorkout:true });
}





</script>
