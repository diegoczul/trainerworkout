@extends("layouts.visitor")




@section("content")



 <section id="content" class="clearfix">
    <div class="wrapper">
          
            <div class="widgets fullwidthwidget shadow marginleftnone">
              <div class="fltright"><a class="bluebtn" href="javascript:void(0)" onclick="addExercise();">+ Add Exercises</a></div>
              <h1>Exercises</h1>
              <input type="text" style="margin:40px;" onKeyUp="searchExercise(this.value)" />

                <!-- exercises starts here -->
                <div class="exercises clearfix">
                <div id="exercises_full">
                	 @if ($exercises->count() > 0)
     <?php $i = 0; ?>
      @foreach ($exercises as $exercise)

                        <div class="exercisesimages {{ ($i % 6 == 0 ? ' marginleftnone':'') }}" >
                        <a href="javascript:void(0)" onClick="deleteExercise({{ $exercise->id }},$(this)); arguments[0].stopPropagation(); return false;" class="deleteicon2"></a>
                             <a href="javascript:void(0)" onClick="requestExercise({{ $exercise->id }})"><span>{{ $exercise->name }}</span>
                            <img alt="{{$exercise->name}}" width="206" src="/{{ Helper::image($exercise->thumb) }}"/></a>
                        </div>
                        <?php $i++; ?>
                    

                  
                    @endforeach



    @else
        {{ Messages::showEmptyMessage("ExercisesEmpty") }}
    @endif
                
                </div>
                </div>
                </div>
            </div>
        </div>
</section>
 {{ Form::open(array('url' => '/Exercises/AddEdit', "files"=>true)); }}
		<div class="wrapper">
        	
            <div class="widgets fullwidthwidget shadow marginleftnone">
            	<h1>Add an exercises</h1>
                <!-- add exercises -->
                <div class="add-exercises clearfix">
                  <!-- add exercises form -->
                  <form id="frm-create-excercise" action="/async/exercises.php" method="post" enctype="multipart/form-data" class="formholder">
                  <div class="fltleft exercisesblockleft marginleftnone">
                  <p>Exercise Name*</p>
                    <input type="text" name="name" id="name" class="input border-radius" placeholder="Exercise name" value="{{ Input::old("name") }}">
 <p>Other known names</p>
                    <textarea name="nameEngine" id="nameEngine" class="input border-radius" placeholder="Exercise name">{{ Input::old("nameEngine") }}</textarea>
                    <input type="hidden" name="id" id="id" />
                    <input name="action" value="addexercise" type="hidden" />
                    <p>Muscle Group</p>
                        {{ FORM::select( "bodygroup",
                                                            $bodygroups,
                                                            Input::old("bodygroup"),
                                                            array("class"=>"chosen-select","id"=>"bodygroup")) 
                                            }}
                    <p>Description</p>
                    <textarea  name="description"  id="description" class="description border-radius">{{ Input::old("description") }}</textarea>
                  </div>
                  <div class="fltright exercisesblockright">
                    <fieldset>
                     <p>Upload Picture 1</p>
                      <input id="image1" name="image1" type="file" class=" whitebutton title border-radius"  />
                      <img src="" id="imageSrc1" width="300" />
                    </fieldset>
                    <fieldset>
                      <p>Upload Picture 2</p>
                      <input id="image2" name="image2" type="file" class="whitebutton title border-radius" />
                      <img src="" id="imageSrc2" width="300" />
                    </fieldset>

                    <fieldset>
                      <p>Upload Picture 3</p>
                      <input id="image3" name="image3" type="file" class="whitebutton title border-radius" />
                      <img src="" id="imageSrc3" width="300" />
                    </fieldset>

                    <fieldset>
                      <p>Upload Picture 4</p>
                      <input id="image4" name="image4" type="file" class="whitebutton title border-radius" />
                      <img src="" id="imageSrc4" width="300" />
                    </fieldset>

                    <fieldset>
                      <p>Upload Picture 5</p>
                      <input id="image5" name="image5" type="file" class="whitebutton title border-radius" />
                      <img src="" id="imageSrc5" width="300" />
                    </fieldset>

                    <fieldset>
                      <p>Upload Picture 6</p>
                      <input id="image6" name="image6" type="file" class="whitebutton title border-radius" />
                      <img src="" id="imageSrc6" width="300" />
                    </fieldset>

                    <fieldset>
                    	<p>Upload a video</p>
                        <input id="video" name="video" type="file" class=" whitebutton title border-radius"  />
                      	<input id="URLVIDEO" name="URLVIDEO" type="text" class=" whitebutton title border-radius"  />
                    </fieldset>
                    <fieldset>
                    	<p>Link to your video of this exercise</p>
                      	<input class="title linkblock border-radius" type="text" placeholder="https://www.youtube.com/watch?" name="youtube" id="youtube">

                    </fieldset>
                     <fieldset>
                     <p>List the equipment needed (separate each equipment with comma)</p>
                      <input class="title linkblock border-radius" type="text" placeholder="Equipment" name="equipment" id="equipment" value="{{ Input::old("equipment") }}">
                    </fieldset>
                    <fieldset>
                      <div class="fltleft checkbox">
                        <input type="checkbox" name="publicLicense"  id="publicLicense"><label>Check if you want to make your exercise available to the world</label>
                      </div>
                      <div class="fltright">
                        <input type="submit" class="bluebtn heavy ajaxSave"  value="Add Exercises">
                      </div>
                    </fieldset>
                  </div>
                  </form>

                </div>
                <p>* required</p>
            </div>
            
        </div>
    </section>
  {{ Form::close()}}
    {{ Form::close()}}





@endsection

@section("scripts")

    <script type="text/javascript">

    function addExercise(){

                                        $("#name").val('');
                                        $("#nameEngine").val('');
                                        $("#id").val('');
                                        $("#bodygroup").val('');
                                        $("#bodygroup").trigger("")
                                        $("#description").val('');
                                        $("#imageSrc1").attr("src","");
                                        $("#imageSrc2").attr("src","");
                                        $("#imageSrc3").attr("src","");
                                        $("#imageSrc4").attr("src","");
                                        $("#imageSrc5").attr("src","");
                                        $("#imageSrc6").attr("src","");

                                        $("#image1").val('');
                                        $("#image2").val('');
                                        $("#image3").val('');
                                        $("#image4").val('');
                                        $("#image5").val('');
                                        $("#image6").val('');
                                        $("#video").val('');
                                        


                                        $("#URLVIDEO").val('');
                                        $("#youtube").val('');
                                        $("#equipment").val('');
                                        successMessage("ADD YOUR EXERCISE");
    }

    function requestExercise(id){
      $.ajax(
                                {
                                    url : "{{ Lang::get('routes./Exercises/show/') }}"+id,
                                    type: "GET",
  
                                    success:function(data, textStatus, jqXHR) 
                                    {
                                        
                                        $("#name").val(data.name);
                                        $("#nameEngine").val(data.nameEngine);
                                        $("#id").val(data.id);
                                        //alert(data.bodygroupId);
                                        $("#bodygroup").val(data.bodygroupId);
                                        $("#bodygroup").trigger("chosen:updated")
                                        $("#description").val(data.description);
                                        $("#imageSrc1").attr("src","/"+data.image);
                                        $("#imageSrc2").attr("src","/"+data.image2);
                                        $("#URLVIDEO").val(data.video);
                                        $("#youtube").val(data.youtube);
                                        $("#equipment").val(data.equipment);
                                        requestExtraPictures(data.id);
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) 
                                    {
                                        errorMessage(jqXHR.responseText);
                                    },
                                });
    } 


    function requestExtraPictures(id){
      $.ajax(
                                {
                                    url : "{{ Lang::get('routes./ExercisesImages/') }}"+id,
                                    type: "GET",
  
                                    success:function(data, textStatus, jqXHR) 
                                    {
                                        
                                        if(data[0] !== undefined) $("#imageSrc3").attr("src","/"+data[0].image);
                                        if(data[1] !== undefined) $("#imageSrc4").attr("src","/"+data[1].image);
                                        if(data[2] !== undefined) $("#imageSrc5").attr("src","/"+data[2].image);
                                        if(data[3] !== undefined) $("#imageSrc6").attr("src","/"+data[3].image);
                                        if(data[4] !== undefined) $("#imageSrc7").attr("src","/"+data[4].image);
                                        if(data[5] !== undefined) $("#imageSrc8").attr("src","/"+data[5].image);
                                        

                                    },
                                    error: function(jqXHR, textStatus, errorThrown) 
                                    {
                                        errorMessage(jqXHR.responseText);
                                    },
                                });
    } 
    

    function searchExercise(search){
    	$.ajax(
                                {
                                    url : "/ControlPanel/exercises",
                                    type: "POST",
                                    data:{search:search},
                                    success:function(data, textStatus, jqXHR) 
                                    {
                                        $("#exercises_full").html(data);
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) 
                                    {
                                        errorMessage(jqXHR.responseText);
                                    },
                                });
    }

    </script>

@endsection


