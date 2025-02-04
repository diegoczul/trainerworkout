<div class="exerciseOverlay overlayKillParent">
  <div class="overlayKillChild"></div>


  <div class="addExerciseContainer overlayKillContent">

    <svg class="c-menu__close" width="12" height="12" viewBox="0 0 12 12" onclick="closeExercise()" xmlns="http://www.w3.org/2000/svg">
        <title>
            closeDark
        </title>
        <path d="M8.138 6L11.71 2.43c.383-.382.384-1.02-.01-1.413L10.988.3A.996.996 0 0 0 9.574.29L6 3.864 2.426.29A.996.996 0 0 0 1.013.3L.3 1.016a1.005 1.005 0 0 0-.01 1.413L3.86 6 .29 9.576c-.383.38-.384 1.02.01 1.413l.713.714a.996.996 0 0 0 1.413.01L6 8.14l3.574 3.573a.996.996 0 0 0 1.413-.01l.714-.715a1.005 1.005 0 0 0 .01-1.413L8.14 6z" fill="#2C3E50" fill-rule="evenodd"/>
    </svg>

    <div class="addexercise">
      <h1>{{Lang::get("content.AddingAnExercise")}}</h1>
      <div class="addexercise-indicatorContainer">
        <div class="indicatorSubContainer">
          <label>step</label><label class="step labelStepOne"></label><label>/2</label> 
        </div>
        <div class="indicatorSubContainer">
          <div class="switcher" onclick="addExerciseSwtichStep()">
            <div class="indicator stepOne"></div> 
          </div>
        </div>
      </div>

      {{ Form::open(array('url' => Lang::get("routes./Exercises/AddExerciseInWorkout"), "files"=>true, 'name' => 'exercise', 'id' => 'exercise_form')); }}

          <p class="required">*{{ Lang::get("content.required") }}</p>


          <div class="addexercise-stepContainer"> 


            <div class="addexercise-step active">

              <label for="">{{ Lang::get("content.Exercisename") }}*</label>
              <input type="text" id="name" name="name" placeholder="bench press, bicep curl, etc..." value="{{ Input::old('name') }}" tabindex="1">

              <label for="">{{ Lang::get("content.Otherexercisenames") }}</label>
              <input type="text" name="nameEngine" placeholder="bench press, bicep curl, etc..." value="{{ Input::old('nameEngine') }}" tabindex="2">

              <input type="hidden" name="id" />
              <input name="action" value="addexercise" type="hidden" />

              <fieldset class="exerciseDescription">
                <label for="description">{{ Lang::get("content.Exercisedescription") }}</label>
                <textarea id="description" class="addexdescription" name="description" maxlength="500" placeholder="{{ Lang::get("content.Exercisedescription") }}" tabindex="3">{{ Input::old("description") }}</textarea>
                <div id="textarea_counter"></div>
              </fieldset>

              <div class="muscleGroup">
                <label for="" tabindex="4">{{ Lang::get("content.Musclegroups") }}*</label>                       
                <select name="bodygroup">
                  @foreach($bodygroups as $bodygroup)
                    <option value="{{ $bodygroup->id }}">{{ $bodygroup->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="submit">
                <label class="next" onclick="addExerciseSwtichStep()" tabindex="5">Next step <img src="/img/svg/arrowNext.svg"></label>
              </div>

            </div>


            <div class="addexercise-step">          

              <fieldset class="exerice_images">
                <div class="col image">
                  <label for="img1" onclick="updateName(this)">{{ Lang::get("content.Uploadpicture") }} 1</label>
                  <input id="img1" onclick="updateName(this)" type="file" name="image1" placeholder="" class="imageInput">
                  <label for="img1" onclick="updateName(this)" class="button">{{ Lang::get("content.selecteimage")}} 1</label>
                </div>
                
                <div class="col image">
                  <label for="img2" onclick="updateName(this)">{{ Lang::get("content.Uploadpicture") }} 2</label>
                  <input id="img2" onclick="updateName(this)" type="file" name="image2" placeholder="" class="imageInput">
                  <label for="img2" onclick="updateName(this)" class="button">{{ Lang::get("content.selecteimage")}} 2</label>
                </div>
              </fieldset>

              <fieldset class="exercise_video">
                <div class="video uploadVideo image">
                  <label for="video1" onclick="updateName(this)">{{ Lang::get("content.Uploadavideo") }} *{{ Lang::get("content.Max Size") }}: 256mb</label>
                  <input id="video1" onclick="updateName(this)" type="file" name="video" placeholder="" class="imageInput">
                  <label for="video1" onclick="updateName(this)" class="button">{{ Lang::get("content.selectVideo")}}</label>
                </div>
                
                <div class="video youtubeVideo">
                  <label for="img2" tabindex="6">{{ Lang::get("content.Linktoyourvideoofthisexercise") }}</label>
                 <input type="text" placeholder="http://www.youtube.com/watch?" name="youtube">
                </div>
              </fieldset>


              <fieldset class="execise_equipments">
                <div class="equipment">
                  <label for="equipment">{{ Lang::get("content.Listtheequipmentneeded") }}*</label>
                  {{ Form::select("equipment[]",$equipmentsList,"",array("id"=>"equipment", "data-placeholder"=> Lang::get("content.selectequipment"), "class"=>"chosen-select","multiple",)) }}
                </div>

                <div class="equipment">
                  <label for="equipmentOptional">{{ Lang::get("content.Listoptionalequipment") }}</label>
                  {{ Form::select("equipmentOptional[]",$equipmentsList,"",array("id"=>"equipmentOptional", "data-placeholder"=> Lang::get("content.selectequipment"), "class"=>"chosen-select","multiple")) }}
                </div>
              </fieldset>

              <div class="previous">
                <span onclick="addExerciseSwtichStep()"><div class="previousButton"><img src="/img/svg/arrowPrevious.svg"></div> previous step</span>
              </div>

              <div class="submit">
                <button type="submit" class="saveex reBindajaxSave" onClick="closeExercise();" tabindex="7">{{ Lang::get("content.CreateExercise") }}</button>
              </div>

            </div>
          </div>         

       {{ Form::close()}} 
    </div>
  </div>
</div>


            

<script type="text/javascript">



  //ALL AJAX FORMS SAVE
  $(".reBindajaxSave").click(function(event){
    
    
    var handler = $(this);
    tForm = $(this).closest("form");
    widget = $(this).attr("widget");

    tForm.submit(function(e)
      {
        e.preventDefault(); //STOP default action
        e.stopImmediatePropagation();
        
          //var postData = $(this).serializeArray();
          var formURL = $(this).attr("action");
          var preload;
          console.log("Sent");
          $.ajax(
          {
              url : formURL,
              type: "POST",
              data: new FormData( this ),
            processData: false,
            contentType: false,
              beforeSend:function() 
              {
                                lightBoxLoadingTwSpinner();
                    },
              success:function(data, textStatus, jqXHR) 
              {
                closeLoadingOverlay();
                successMessage(data);
                $("#exercise_search").val($("#name").val());
                searchExercise($("#exercise_search"), event);
                $("#new_exercise_form").html("");
                $(".overlayKillParent").removeClass("overlayKillParent-active");
                tForm.reset();
                return false;

              },
              error: function(jqXHR, textStatus, errorThrown) 
              {
                closeLoadingOverlay();
                errorMessage(jqXHR.responseText);
                $("#addExerciseForm").hide();
                handler.clear();
                return false;
              },
              statusCode: {
                500: function() {
                  if(jqXHR.responseText != ""){
                    errorMessage(jqXHR.responseText);
                    $("#addExerciseForm").hide();
                    handler.clear();
                  }
                    
                }
            }
          });
          
      }
    );
  
  });




function goStepOne() {
  // adjust the step number
    $('.addexercise .indicatorSubContainer').find('.step').removeClass('labelStepTwo').addClass('labelStepOne');
    // move the step indicator
    $('.switcher .indicator').removeClass('stepTwo').addClass('stepOne');
    // move the input container
    $('.addexercise-stepContainer').removeClass('stepTwoActive');
    // place active class on the right addexercise-set
    var $addStep = $('.addexercise-stepContainer').find('.addexercise-step');

    $addStep.removeClass("active inactive");
    $addStep.first().addClass("active");
    setTimeout(function() {
      $addStep.last().addClass("inactive");
    }, 300);
    $("div.addExerciseContainer").scrollTop( 0 );
}

function goStepTwo() {
    // adjust the step number
    $('.indicatorSubContainer').find('.step').removeClass('labelStepOne').addClass('labelStepTwo');
    // move the step indicator
    $('.switcher .indicator').removeClass('stepOne').addClass('stepTwo');
    // move the input container
    $('.addexercise-stepContainer').addClass('stepTwoActive');
    // place active class on the right addexercise-set
    var $addStep = $('.addexercise-stepContainer').find('.addexercise-step');

    $addStep.removeClass("active inactive");
    $addStep.last().addClass("active");
    setTimeout(function() {
      $addStep.first().addClass("inactive");
    }, 300);
    $("div.addExerciseContainer").scrollTop( 0 );
}

// swtich step add exercise
function addExerciseSwtichStep() {
  if($('.indicatorSubContainer').find('.step').hasClass('labelStepTwo')) {
    goStepOne();
  } else {
    goStepTwo();
  }
}

//update the name of the fake button
function updateName(ob) {
    var $input = $(ob).closest(".image").find(".imageInput");
    $input.change(function() {
        var filename = $input.val().replace(/^.*\\/, "");
        $(ob).closest(".image").find(".button").html(filename);
    });
}


  function detectFile(id){
     var file = $("#"+id).val();
     // console.log("-"+file+"-");
     if(file != ""){
        $("#label_"+id).text(dict["File Chosen"]);
        // console.log("Chosen0");
     } else {
        $("#label_"+id).text(dict["Select image"]);
        // console.log("empty");
     }
  }
</script>

