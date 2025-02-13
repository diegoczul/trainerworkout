@extends("layouts.trainer")

@section("content")
 <section id="content" class="clearfix">
 
      <div class="wrapper">
         
        <div class="widgets fullwidthwidget shadow marginleftnone">
            <div class="step_wrapper">
              <div id="addExerciseButton">
                <div class="add-exercises clearfix">
                <!-- add exercises form -->
                  <div class = "addexinstruction">
                    <h2>{{ Lang::get("content.addExerciseInWorkout/title") }}.</h2></br>
                  </div>

                  <div class="center addexbtn">
                    <a class="bluebtn whitebtn addexbtn" href="javascript:void(0)" onclick="showForm()"><i class="fa fa-plus"></i>{{ Lang::get("content.addExercise") }}</a>
                  </div>
                </div>
              </div>

              <div id="addExerciseForm">
                <div class="addexercise">
                {{ Form::open(array('url' => Lang::get("routes./Exercises/AddExercise"), "files"=>true)); }}
                  <form>
                    <div class="exercise_field_panel">
                      <label for="">{{ Lang::get("content.Exercisename") }}*</label>
                      <input type="text" name="name" placeholder="" value="{{ Input::old("name") }}"></input>

                      <label for="">Other exercise names</label>
                      <input type="text" name="nameEngine" placeholder="" value="{{ Input::old("nameEngine") }}"></input>

                      <input type="hidden" name="id" />
                      <input name="action" value="addexercise" type="hidden" />

                      <label for="">{{ Lang::get("content.Musclegroups") }}*</label>
                      <select name="bodygroup">
                      @foreach($bodygroups as $bodygroup)
                        
                      @endforeach
                      </select>                 
                     
                    </div>
                    
                    <textarea class="addexdescription" name="description" placeholder="{{ Lang::get("content.Exercisedescription") }}">{{ Input::old("description") }}</textarea>
                    <div class="addexLine"></div>

                    <div class="exercise_field_panel">
                      <label for="">{{ Lang::get("content.Uploadpicture") }} 1</label>
                      <input type="file" name="image1" placeholder="" class="whitebutton title"></input>

                      <label for="" style="display:none">{{ Lang::get("content.Uploadvideo") }}</label>
                      <input type="file" name="youtube" placeholder="" class="whitebutton title" style="display:none"></input>
                      
                      <label for="">{{ Lang::get("content.Listtheequipmentneeded") }}*</label>
                      
                      <select name="equipment[]" id="equipment" class="chose-select" multiple="multiple">
                      @foreach($equipments as $equipment)
                        <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                      @endforeach
                      </select>

                    </div>

                    <div class="exercise_field_panel addexsecond">
                      <label for="">{{ Lang::get("content.Uploadpicture") }} 2</label>
                      <input type="file" name="image2" placeholder="" class="whitebutton title"></input>

                      <label for="" style="display:none">{{ Lang::get("content.addExerciseInWorkout/Linkvideo") }}</label>
                      <input type="text" name="" placeholder="" class="whitebutton title" style="display:none"></input>

                      <label for="">{{ Lang::get("content.Listoptionalequipment") }}</label>
                      <select name="equipmentOptional[]" id="equipmentOptional" class="chose-select" multiple="multiple">
                      @foreach($equipments as $equipment)
                        <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                      @endforeach
                      </select>
                     

                    </div>

                      <div class="addexsavecontainer">
                        <input type="submit" class="saveex"  value="{{ Lang::get("content.CreateExercise") }}"></input>
                        <p class="required">*{{ Lang::get("content.required") }}</p>
                      </div>
                      
                    </div>
                  </form>
                {{ Form::close()}}
                </div>

                

              </div>
            </div>
        </div>     
      </div>
    </section>
  


@endsection

@section("scripts")

<script type="text/javascript">
  $(document).ready(function(){ $("#m_exercises").addClass('active'); });
  activateChosen();

  function showForm(){
    $('/Trainer/CreateWorkout #test').load('/Exercises/addExerciseInWorkout #addExerciseForm');
  }
</script>

@endsection
