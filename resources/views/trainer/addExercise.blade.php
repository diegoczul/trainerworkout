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
                  <div class="fltleft exercisesblockleft marginleftnone">
                  <p>{{ Lang::get("content.ExerciseName") }}*</p>
                    <input type="text" name="name" class="input border-radius" placeholder="{{ Lang::get("content.Exercisename") }}" value="{{ Input::old("name") }}">
                    <p>{{ Lang::get("content.Othernamesfortheexercise") }}</p>
                    <input type="text" name="nameEngine" class="input border-radius" placeholder="{{ Lang::get("content.Othernamesfortheexercise") }}" value="{{ Input::old("nameEngine") }}">
                    <input type="hidden" name="id" />
                    <input name="action" value="addexercise" type="hidden" />
                    <p>{{ Lang::get("content.MuscleGroup") }}</p>
                        {{ FORM::select( "bodygroup",
                                                            $bodygroups,
                                                            Input::old("bodygroup"),
                                                            array("class"=>"chosen-select","id"=>"bodygroup")) 
                                            }}
                    <p style="margin-top:10px">Description</p>
                    <textarea  name="description" class="description border-radius">{{ Input::old("description") }}</textarea>
                  </div>
                  <div class="fltright exercisesblockright">
                    <fieldset>
                     <p>{{ Lang::get("content.UploadPicture") }} 1</p>
                      <input id="image1" name="image1" type="file" class=" whitebutton title border-radius"  />
                    </fieldset>
                    <fieldset>
                      <p>{{ Lang::get("content.UploadPicture") }} 2</p>
                      <input id="image2" name="image2" type="file" class="whitebutton title border-radius" />
                    </fieldset>
                    <fieldset style="display:block">
                    	<p>{{ Lang::get("content.Uploadavideo") }}</p>
                      	<input id="video" name="video" type="file" class=" whitebutton title border-radius"  />
                    </fieldset>
                    <fieldset style="display:block">
                    	<p>{{ Lang::get("content.Linktoyourvideoofthisexercise") }}</p>
                      	<input class="title linkblock border-radius" type="text" placeholder="http://www.youtube.com/watch?" name="youtube">
                    </fieldset>
                     <fieldset>
                     <p>{{ Lang::get("content.Listtheequipmentneeded") }}</p>
                      {{ Form::select("equipment[]",$equipments,"",array("id"=>"equipment","class"=>"chosen-select","multiple"=>"multiple")) }}
                    </fieldset>
                    <fieldset>
                    <p>{{ Lang::get("content.Listtheequipmentoptional") }} </p>
                     {{ Form::select("equipmentOptional[]",$equipments,"",array("id"=>"equipmentOptional","class"=>"chosen-select","multiple"=>"multiple")) }}
                      
                    </fieldset>
                    <fieldset>
                      <div class="fltleft checkbox" style="display:none">
                        <input type="checkbox" name="publicLicense"  id="publicLicense"><label>{{ Lang::get("Checkifyouwant") }}</label>
                      </div>
                      <div class="fltleft checkbox" style="display:none">
                        <input type="checkbox" name="removeGreenScreen"  id="removeGreenScreen"><label>{{ Lang::get("content.Removegreenscreen") }}</label>
                      </div>
                      <div class="fltright">
                        <input type="submit" class="bluebtn heavy"  value="{{ Lang::get("content.CreateExercise") }}" onClick="lightBoxLoadingTwSpinner()"></input>
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
    $(document).ready(function(){ $("#m_exercises").addClass('active'); });
    </script>

@endsection
