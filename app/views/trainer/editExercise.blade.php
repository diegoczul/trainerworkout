@extends("layouts.trainer")

@section("header")
    {{ Helper::seo("AddExercise") }}
@endsection

@section("content")
 <section id="content" class="clearfix">
 {{ Form::open(array('url' => Lang::get("routes./Exercises/AddExercise"), "files"=>true)); }}
		<div class="wrapper">
        	
            <div class="widgets fullwidthwidget shadow marginleftnone">
            	<h1>{{ Lang::get("content.Addanexercise") }}</h1>
                <!-- add exercises -->
                <div class="add-exercises clearfix">
                  <!-- add exercises form -->
                  <form id="frm-create-excercise" action="/async/exercises.php" method="post" enctype="multipart/form-data" class="formholder">
                  <input type="hidden" id="id" name="id" value="{{ $exercise->id }}">
                  <div class="fltleft exercisesblockleft marginleftnone">
                  <p>{{ Lang::get("content.ExerciseName") }}*</p>
                    <input type="text" name="name" class="input border-radius" placeholder="{{ Lang::get("content.Exercisename") }}" value="{{ $exercise->name }}">
                    <p>{{ Lang::get("content.Othernamesfortheexercise") }}</p>
                    <input type="text" name="nameEngine" class="input border-radius" placeholder="{{ Lang::get("content.Othernamesfortheexercise") }}" value="{{ $exercise->nameEngine }}">
                    <input name="action" value="addexercise" type="hidden" />
                    <p>{{ Lang::get("content.MuscleGroup") }}</p>
                        {{ FORM::select( "bodygroup",
                                                            $bodygroups,
                                                            $exercise->bodygroupId,
                                                            array("class"=>"chosen-select","id"=>"bodygroup")) 
                                            }}
                    <p style="margin-top:10px">Description</p>
                    <textarea  name="description" class="description border-radius">{{ $exercise->description }}</textarea>
                  </div>
                  <div class="fltright exercisesblockright">
                    <fieldset>
                     <p>{{ Lang::get("content.UploadPicture") }} 1</p>
                      @if($exercise->image)
                      <img src="/{{ Helper::image($exercise->thumb) }}" width="200" class="refreshImage">

                      <a href="javascript:void(0)" onClick="clearAttribute({{ $exercise->id }},'image')"> {{ Lang::get("content.Delete") }} </a>
                      @endif
                      <input id="image1" name="image1" type="file" class=" whitebutton title border-radius"  />
                    </fieldset>
                    <fieldset>
                      <p>{{ Lang::get("content.UploadPicture") }} 2</p>
                      @if($exercise->image)
                      <img src="/{{ Helper::image($exercise->thumb2) }}" width="200" class="refreshImage">

                      <a href="javascript:void(0)" onClick="clearAttribute({{ $exercise->id }},'image2')"> {{ Lang::get("content.Delete") }} </a>
                      @endif
                      <input id="image2" name="image2" type="file" class="whitebutton title border-radius" />
                    </fieldset>
                    <fieldset style="display:block">
                    	<p>{{ Lang::get("content.Uploadavideo") }}</p>
                      <?php
                      if($exercise->video != ""){
                     ?>
                        <a  

                                            href="/{{ $exercise->video}}"

                                            style="display:block;width:200px; height:200px"  

                                            id="player"> 

                                        </a>
                                        <a href="javascript:void(0)" onClick="clearAttribute({{ $exercise->id }},'video')"> {{ Lang::get("content.Delete") }} </a>

                                        <?php   
                      }
                    ?>
                      	<input id="video" name="video" type="file" class=" whitebutton title border-radius"  />
                    </fieldset>
                    <fieldset style="display:block">
                    	<p>{{ Lang::get("content.Linktoyourvideoofthisexercise") }}</p>
                      	<input class="title linkblock border-radius" type="text" placeholder="http://www.youtube.com/watch?" name="youtube" value="{{ ($exercise->youtube) ? "https://www.youtube.com/watch?v=".$exercise->youtube : "" }}">
                    </fieldset>
                     <fieldset>
                     <p>{{ Lang::get("content.Listtheequipmentneeded") }}</p>
                      {{ Form::select("equipment[]",$equipments,$equipmentsSelected,array("id"=>"equipment","class"=>"chosen-select","multiple"=>"multiple")) }}
                    </fieldset>
                    <fieldset>
                    <p>{{ Lang::get("content.Listtheequipmentoptional") }} </p>
                     {{ Form::select("equipmentOptional[]",$equipments,$equipmentsSelectedOptional,array("id"=>"equipmentOptional","class"=>"chosen-select","multiple"=>"multiple")) }}
                      
                    </fieldset>
                    <fieldset>
                      <div class="fltleft checkbox" style="display:none">
                        <input type="checkbox" name="publicLicense"  id="publicLicense"><label>{{ Lang::get("Checkifyouwant") }}</label>
                      </div>
                      <div class="fltleft checkbox" style="display:none">
                        <input type="checkbox" name="removeGreenScreen"  id="removeGreenScreen"><label>{{ Lang::get("content.Removegreenscreen") }}</label>
                      </div>
                      <div class="fltright">
                        <input type="submit" class="bluebtn heavy"  value="{{ Lang::get("content.UpdateExercise") }}" onClick="lightBoxLoadingTwSpinner()"></input>
                      </div>
                    </fieldset>
                  </div>
                  </form>

                </div>
                <p>* {{ Lang::get("content.required") }}</p>
            </div>
            
        </div>
    </section>
  {{ Form::close()}}


@endsection

@section("scripts")

    <script type="text/javascript">
    $(document).ready(function(){ 
      $("#m_exercises").addClass('active');
      $(".chosen-select").trigger("chosen:updated");
       });

    function clearAttribute(id,attribute,objectId){

      $.ajax(
            {
                url :"{{ Lang::get("routes./Exercises/ClearAttribute") }}",
                type: "POST",
                data: { id:id,attribute:attribute },
                success:function(data, textStatus, jqXHR) 
                {
                    successMessage(data);
                    if(attribute == "image"){
                        $("#image1").hide();
                    }

                    if(attribute == "image2"){
                       $("#image2").hide();
                    }

                    if(attribute == "video"){
                      $("#player").hide();
                    }
                    //callWidget("exercises_full");
                    refreshImages("refreshImage");
                    //location.reload();
                    //window.location = window.location;

                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    errorMessage(jqXHR.responseText);
                },
                statusCode: {
                    500: function() {
                        if(jqXHR.responseText != ""){
                            errorMessage(jqXHR.responseText);
                        }else {
                            
                        }
                        
                    }
                }
            });
    }

    </script>


                      <script type="text/javascript" src="/fw/flowplayer/flowplayer-3.2.2.min.js"></script>

                                        <script>

                                            jQuery(document).ready(function() {

                                                flowplayer('player', '/fw/flowplayer/flowplayer-3.2.2.swf', {wmode: "transparent", clip: {

                                                        autoPlay: true,

                                                        autoBuffering: true }   

                                                });
                                            });


                                        </script>

@endsection
