var exercise_groups = {1:[]};
//exercise_groups[1].restBetweenCircuitExercises = [];
var exercise_groups_rest = {1:[]};
exercise_groups_rest[1] = { "restBetweenCircuitExercises":[] };
var currently_edited_exercises = {};

var panel_count = 1;

var isPanelOn = 0; //0 for not active, 1 for active

// Classical Sets
var DEFAULT_SETS   = 3;
var DEFAULT_REPS   = 12;
var DEFAULT_WEIGHT = 0;
var DEFAULT_REST   = 0;

var USER_DEFAULT_SETS   = "";
var USER_DEFAULT_REPS   = "";
var USER_DEFAULT_WEIGHT = "";
var USER_DEFAULT_REST   = "";


// CIRCUIT Sets
var DEFAULT_SETS_CIRCUIT   = 1;
var DEFAULT_REPS_CIRCUIT   = 12;
var DEFAULT_WEIGHT_CIRCUIT = 0;
var DEFAULT_REST_CIRCUIT   = 0;

// CIRCUIT Sets
var USER_DEFAULT_SETS_CIRCUIT   = "";
var USER_DEFAULT_REPS_CIRCUIT   = "";
var USER_DEFAULT_WEIGHT_CIRCUIT = "";
var USER_DEFAULT_REST_CIRCUIT   = "";

// Cardio Sets
var DEFAULT_TIME       = 15;
var DEFAULT_DISTANCE   = 0;
var DEFAULT_SPEED      = 0;
var DEFAULT_BPM        = 0;

var USER_DEFAULT_TIME       = "";
var USER_DEFAULT_DISTANCE   = "";
var USER_DEFAULT_SPEED      = "";
var USER_DEFAULT_BPM        = "";


var USER_DEFAULT_ROUNDS = "";
var USER_DEFAULT_ROUNDS_REST = "";
var USER_DEFAULT_REST_EXERCISES_CIRCUIT = "";


var totalExercises = 0;
var savedExercises = 0;



/**
 *  Add new panel to drag and drop
 */
function addNewPanel(){
  if( exercise_groups[panel_count] === undefined || exercise_groups[panel_count].length > 0){
    panel_count++;
  }

  panelHtml = tmpl_addWorkoutPanel(panel_count);
  $("#dataValidation3").validate();
  // // Add new panel
  $(panelHtml).hide().appendTo($("#workout_builder")).fadeIn(500);
  adjustPanelNumbers();
  // Create new builder list
  
  exercise_groups[panel_count] = [];
  exercise_groups_rest[panel_count] = { "restBetweenCircuitExercises":[] };

  // Bind droppable area
  bindDragDropEvent();
  // Bind circuit inputs
  bindCircuitInputs();
  // Init edit
  generateWorkoutEditPanels();
}

/*
 *    Removes a panel if it has no elements
 */
function removePanel(panel_number) {
  // Remove tick notification
  $("#workout_builder_"+panel_number+" li").each(function(){
    removeExercise(false,$(this).attr("exercise-id"));
  });

  // Remove panel
  $("#workout_builder_panel_"+panel_number).remove();

  // Remove corresponding array
  delete exercise_groups[panel_number];
  delete exercise_groups_rest[panel_number];

  adjustPanelNumbers();
  generateWorkoutEditPanels();
}
function adjustPanelNumbers(){
  //$('#create_step_3 .workout_builder_panel').each(function(index){
    //$(this).children(".panel_number").html('<div>'+(index+1)+'</div>');
  //});
}
function adjustExerciseIndexes(workout_builder_number){
  $("#workout_builder_"+workout_builder_number+" li").each(function(index,li){
    $(this).attr("exercise-index",index);
    // var array = $(this).attr("edit-id").split("_");
    // array[3] = index;
    // var newID = array.join("_");
    // $(this).attr("edit-id",newID);
    var i=0;
    exercise_groups[workout_builder_number].forEach(function(exercise){
      var array = exercise.editID.split("_");
      array[3] = i;
      var newID = array.join("_");
      exercise.editID = newID;
      i++;
    });
  })
}

function monitorWorkoutSaved(){
  if(savedExercises < totalExercises){
    //$("#savedExercises").show();
    $("#savedExercises").removeClass("success");
    $("#savedExercises").text(dict["someOfThe"]);
  } else {
    //$("#savedExercises").hide();
    $("#savedExercises").addClass("success");
    $("#savedExercises").text(dict["allSavedEx"]);
  }
}

/**
 *  Bind drag/drop event
 */
function bindDragDropEvent(){
  $( ".draggable" ).draggable({
      containment: 'document',
      helper: 'clone',
      zIndex:10000
  });

  $( ".droppable" ).droppable({
    drop: function( event, ui ) {
      var panel_number = $(this).closest("ul").attr("builder-number");
      dropExercise($(ui.draggable), $(this),event);
      if(isInCicruitMode(panel_number))
        addNewCircuitExercise(panel_number);
      event.stopImmediatePropagation();
      totalExercises++;
       monitorWorkoutSaved();
    },
    out:function(event, ui){
        var panel_number = $(this).closest("ul").attr("builder-number");
    },
    over:function(event,ui){
        var panel_number = $(this).closest("ul").attr("builder-number");
    }
  });
  $(".draggable")
    .mouseover(function(){
      var exerciseID = $(this).attr("id");
      $('li[exercise-id="'+exerciseID+'"]').append('<div class="exercise_state_hover"><i class="fa fa-check"></i></div>');
    })
    .mouseout(function(){
        var exerciseID = $(this).attr("id");
        $('li[exercise-id="'+exerciseID+'"]').children(".exercise_state_hover").remove();
    });
}

function bindCircuitInputs(){
  $(".circuit_round").change(function(){
    var val = $(this).val();
    var pan = $(this).attr("panel-id");
    
    exercise_groups_rest[pan]["circuitRound"] = val;
  });
  $(".circuit_rest").change(function(){
    var val = $(this).val();
    var pan = $(this).attr("panel-id");
    exercise_groups_rest[pan]["circuitRest"] = val;
  });
}

function saveCircuitInfo(panel){

    var val = $("#circuit_"+panel+"_rounds").val();
    exercise_groups_rest[panel]["circuitRound"] = val;
    val = $("#circuit_"+panel+"_rest").val();
    exercise_groups_rest[panel]["circuitRest"] = val;


}

function saveCircuitInfoAllPanels(){
  for(var x = 1; x <= panel_count; x++){
    saveCircuitInfo(x);
  }
}

function cleanExerciseGroups(){
  var counter = 1;
  var temp_exercise_groups = {};
  var temp_exercise_groups_rest = {};
  //console.log(exercise_groups);

  for(var x in exercise_groups){
    //console.log(exercise_groups[x].length);
    if(exercise_groups[x].length == 0){

    } else {
      //console.log("Counter: "+counter);
      temp_exercise_groups[counter] = exercise_groups[x];
      if(exercise_groups_rest[x] !== undefined){
        temp_exercise_groups_rest[counter] = exercise_groups_rest[x];
      } else {
        temp_exercise_groups_rest[counter] = { "restBetweenCircuitExercises":[], "circuitRound":1, "circuitRest":30 };
      }
      counter = counter + 1;
    }
    
  }
  //console.log("Panel: "+panel_count);
  //console.log("===");
  exercise_groups = {};
  exercise_groups = temp_exercise_groups;
  exercise_groups_rest = temp_exercise_groups_rest;

}



function recreateStep4(addNewPanelVar,noscroll){
  var html = '';
  $("#workout_builder").empty();
  panel_count = 1;
  cleanExerciseGroups();


  for(var x in exercise_groups){
    html += tmpl_addWorkoutPanelWithExercises(x,exercise_groups[x]);
    panel_count++;
  }
  $("#workout_builder").html(html);
  for(var x in exercise_groups){
    if(exercise_groups[x].length > 1){
      setCircuitModeLabel(x);
      addNewCircuitExercise(x);
      $("#edit_switch_circuit_"+x).prop('checked',true);
    }
    if(exercise_groups_rest[x] !== undefined){
      if(exercise_groups_rest[x].circuitRound !== undefined){
          $("#circuit_"+x+"_rounds").val(exercise_groups_rest[x].circuitRound);
      }
    }
    if(exercise_groups_rest[x] !== undefined){
      if(exercise_groups_rest[x].circuitRest !== undefined){
          $("#circuit_"+x+"_rest").val(exercise_groups_rest[x].circuitRest);
      }
    }
  }

  if(addNewPanelVar === undefined) addNewPanel();
  if(noscroll === undefined) showStep4();
  
  generateWorkoutEditPanels();
  //showStep5();
  //redrawTags();
}

/*
 *  Drop Event 
 */
function dropExercise(exercise, target,event){
  // Get current panel number
  var workout_builder_number = target.closest("ul").attr("builder-number");
  // Generate reference ID for the dragged element
  var draggedID = exercise.attr("id");
  var index = exercise_groups[workout_builder_number].length;
  var editID = buildExerciseID(draggedID,workout_builder_number,index);
  exercise_groups[workout_builder_number].push(getExerciseFromID(draggedID, editID));

  // Show checkmark for dragged exercise
  var selected = exercise.children(".exercisesimages").children(".exercise_added");
  selected.css("opacity","1");

  // Add dragged count
  var select    = exercise.children(".exercisesimages");
  var dragCount = select.attr("drag-count");
  select.attr("drag-count", ++dragCount); 

  // Prepend "dragged" to the ID of the element
  var selectedExercise = exercise.children(".exercisesimages").clone();
  selectedExercise.children(".exercise_state")
                  .removeClass("exercise_added")
                  .addClass("unselect_exercise")
                  .attr("onclick",'removeExercise(event,"'+draggedID+'")')
                  .html("+");
  target.html(selectedExercise);
  target.addClass("change_droppable");
  target.attr("exercise-id",draggedID); 
  target.attr("edit-id",editID);
  target.attr("builder-number",workout_builder_number);
  adjustExerciseIndexes(workout_builder_number);


  if(isInCicruitMode(workout_builder_number)){
    exercise.repArray = [DEFAULT_REPS_CIRCUIT];
    exercise.templateSets = [DEFAULT_REPS_CIRCUIT];
  }

  callForEvent('workout-add-exercise-to-group',{"workout-name":workoutName});

  if(target.closest(".workout_builder_panel").hasClass("last_builder_panel")){
    addNewPanel();
    target.closest(".workout_builder_panel").removeClass("last_builder_panel").append('<div class="remove_panel" onclick="removePanel('+(panel_count-1)+')">+</div>');
  }  

  generateWorkoutEditPanels();
  event.stopImmediatePropagation();
}

/**
 *
 */
function removeExercise(event,exerciseID){
  var exercise = $("#"+exerciseID);
  var workout_builder_number = $(event.target).closest("li").attr("builder-number");

  var dragCount = exercise.children(".exercisesimages").attr("drag-count");
  if(dragCount == 1){
    // Show checkmark for dragged exercise
    var selected = exercise.children(".exercisesimages").children(".exercise_added");
    selected.css("opacity","0");
  }
  exercise.children(".exercisesimages").attr("drag-count", (dragCount-1));

  if(event){
    $(event.target).closest("li").remove();
    // Remove from list
    var exercise_index = $(event.target).closest("li").attr("exercise-index");
    removeExerciseFromGroupList(parseInt(workout_builder_number),parseInt(exercise_index));
  }
  callForEvent('workout-remove-exercise',{"workout-name":workoutName});
  totalExercises--;
  if(!isInCicruitMode(workout_builder_number)){
    addNewCircuitExercise(workout_builder_number);
  }
  adjustExerciseIndexes(workout_builder_number);
  generateWorkoutEditPanels();
}


function recreateStep2(){
  var html = "";
  $("#selected_exercises").empty();
  html = '<h2 id="no_exercise_selected" class="step_message exerciseSearchHeader">Your exercises will be added here.</h2>';
  html += "<ul>";

  for(x = 0; x < exercises.length; x++){
      html += '<li id="selected_'+exercises[x].elementID+'">';
      html += '<div class="exercisesimages">';
      html += '<div class="exercise_overlay"><div onclick="expopup(\''+exercises[x].elementID+'\')" id="xyz" class="exercise_overlay_view overlay_component">View</div><div onclick="removeFromExerciseListAndSelectedView(\''+exercises[x].elementID+'\')" class="exercise_overlay_remove overlay_component">Remove</div></div>';
      //html += '<div class="exercise_overlay"><div class="exercise_overlay_view overlay_component">View</div><div class="exercise_overlay_remove overlay_component">Remove</div></div>';
      html += '<span>'+exercises[x].name+'</span><img onerror="imgError(this);" alt="'+exercises[x].name+'" src="/'+exercises[x].thumb+'"></div>';
      html += "</li>";
  }

  $("#selected_exercises").html(html);
}

function recreateStep3FromExerciseGroups(){
  var html = "";
  $("#editable_exercises").empty();
  html = "<ul>";

  for(x = 0; x < exercises.length; x++){
      html += '<li id="editable_'+exercises[x].elementID+'" class="draggable ui-draggable ui-draggable-handle">';
      html += '<div class="exercisesimages" drag-count="0"><div class="exercise_added exercise_state"><i class="fa fa-check"></i></div><span class="small">'+exercises[x].name+'</span>  <img onerror="imgError(this);" alt="Standing Bicep Curl with Barbell" src="/'+exercises[x].thumb+'"></div>';
      html += "</li>";
  }
  $("#editable_exercises").html(html);
}



function removeExerciseFromGroupList(panel_number, exercise_index){
  exercise_groups[panel_number].splice(exercise_index,1);
  exercise_groups_rest[panel_number]["restBetweenCircuitExercises"].splice(exercise_index,1);
}

function removeAllExercisesButFirstOne(panel_number) {
  var exercise_list = $("#workout_builder_"+panel_number).children("ul li:not(:first-child)");
  exercise_list.each(function(index, exercise){
    var exerciseID    = $(exercise).attr("exercise-id");
    if(exerciseID){
      removeExerciseFromGroupList(parseInt(panel_number), 1);
      removeExercise(false, exerciseID);
    }
    $(exercise).remove();
  });
}

/*
 *  Handle Switch To Classical Mode
 */
function createCircuit(panel_number){
  var test = !$("#workout_builder_"+panel_number).children("ul li:last-child div").hasClass("add_workout_tile");

  if(!isInCicruitMode(panel_number)){
    setCircuitModeLabel(panel_number);
    addNewCircuitExercise(panel_number);
    $("#edit_switch_circuit_"+panel_number).prop('checked',true);
    $("#workout_builder_"+panel_number).addClass("circuitOn_workout_builder_panel");
    saveCircuitInfo(panel_number);
    for(var x = 0; x < exercise_groups[panel_number].length; x++){
      exercise_groups[panel_number][x].repArray = [DEFAULT_REPS_CIRCUIT];
      exercise_groups[panel_number][x].templateSets = [DEFAULT_REPS_CIRCUIT];
    }
  } else {
    setClassicalModeLabel(panel_number);
    removeAllExercisesButFirstOne(panel_number);
    $("#edit_switch_circuit_"+panel_number).prop('checked',false);
    $("#workout_builder_"+panel_number).removeClass("circuitOn_workout_builder_panel");
  }
}

/*
 *   Append new drop area
 */
function addNewCircuitExercise(panel_number){
  var html = '<li class="droppable"><div class="add_workout_tile plus_button">+</div></li>';
  $(html).hide().appendTo($("#workout_builder_"+panel_number)).fadeIn(500);
  bindDragDropEvent();
}

/*
 *  Change UI when clicked.
 */
function setCircuitModeLabel(panel_number){
  $("#edit_switch_circuit_"+panel_number).addClass("active");
  $("#circuit_input_"+panel_number).css("visibility","visible");
  callForEvent('workout-set-group-to-circuit',{"workout-name":workoutName});
}
function setClassicalModeLabel(panel_number){
  $("#edit_switch_circuit_"+panel_number).removeClass("active");
  $("#circuit_input_"+panel_number).css("visibility","hidden");
  callForEvent('workout-set-group-to-normal',{"workout-name":workoutName});
}

/*
 *  Check current mode of workotu builder
 */
function isInCicruitMode(panel_number){
  return $("#edit_switch_circuit_"+panel_number).hasClass("active");
}
function isInClassicalMode(panel_number){
  return $("#edit_switch_circuit_"+panel_number).hasClass("active");
}

/**
 *  Returns actual object from ID
 */
function getExerciseFromID(exerciseID, editID){
  var exercise_obj = {};
  var idArray = exerciseID.split("_");
  idArray.shift();
  var exercise_id = idArray.join("_");
  exercises.forEach(function(exercise){
    if(exercise.elementID == exercise_id ){
      exercise.editID = editID;
      exercise_obj = $.extend(true, {}, exercise);
    }
  });
  return exercise_obj;
} 

function buildExerciseID(draggedID,workout_builder_number,index){
  var idArray = draggedID.split("_");
  idArray.shift();
  idArray[2] = workout_builder_number
  idArray[3] = index
  var exercise_id = idArray.join("_");
  return exercise_id;
}

/**
 *    Workout Edit Functions
 */
function generateWorkoutEditPanels(){
  // Generate based on exercise list added
  var i=1;
  $("#workout_builder_edit").empty();
  for (var key in exercise_groups) {
      var exercises = exercise_groups[key];
      var circuitMode = false;
      var exerciseNumber = 0;
      exercise_li = '';
      // If circuit mode
      if(exercises.length > 1){
        exercises.forEach(function(exercise){
          exercise_li +=  tmpl_editExerciseCircuit(exercise,parseInt(key),exerciseNumber,exercise_groups_rest);
          exerciseNumber = exerciseNumber+1;
          if(exercise.repArray === undefined){
            exercise.repArray = [DEFAULT_REPS_CIRCUIT];
          }
          if(exercise.bodygroupId == 18){
            if(exercise.intervals === undefined){ exercise.intervals = [{'speed':DEFAULT_SPEED,'bpm':DEFAULT_BPM,'dist':DEFAULT_DISTANCE,'time':DEFAULT_TIME}]; }
          }
          
          if(!exercise.notes){
            exercise.notes = "";
          }
        });
        circuitMode = true;
      } else {
        exercises.forEach(function(exercise){
          exercise_li +=  tmpl_editExerciseClassical(exercise,i);
          if(exercise.repArray === undefined){
            exercise.repArray = [DEFAULT_REPS,DEFAULT_REPS,DEFAULT_REPS];
          }
          if(exercise.bodygroupId == 18){
            if(exercise.intervals === undefined){ exercise.intervals = [{'speed':DEFAULT_SPEED,'bpm':DEFAULT_BPM,'dist':DEFAULT_DISTANCE,'time':DEFAULT_TIME}]; }
          }
          if(!exercise.notes){
            exercise.notes = "";
          }
        });
      }

      var html = tmpl_editExercisePanel(exercise_li, i, parseInt(key),circuitMode, exercises);
      
      if(exercise_li != '')
        $("#workout_builder_edit").append(html);
      i++;
      bindNumberInputs();
  }
  
}

function editExOnCircuit(event, panel_number){
  if(isPanelOn == 0){
    toggleEditExercisePanel(event, panel_number);
  }
}

function toggleEditExercisePanel(event, panel_number){
  callForEvent('workout-edit-exercise',{"workout-name":workoutName});
  if(!$("#exercise_edit_panel_"+panel_number).hasClass("active")){
    $("#exercise_edit_panel_"+panel_number).slideDown("slow").addClass("active");
    $("#edit_type_switch_"+panel_number).children(".edit_button").html(dict["clickToHide"]);
    $("#edit_type_switch_"+panel_number).children(".edit_expand").html("-").css("margin-top","-5px");
    isPanelOn = 1;
  }else {
    $("#exercise_edit_panel_"+panel_number).slideUp("slow").removeClass("active");
    $("#edit_type_switch_"+panel_number).children(".edit_button").html("Click to edit");
    $("#edit_type_switch_"+panel_number).children(".edit_expand").html("+").css("margin-top","0");
    var elements = $("#exercise_edit_panel_"+panel_number).find("exercise_added").remove(); 
    isPanelOn = 0;
  }
}

function toggleMoreOptions(event, panel_number){
  if(!$("#more_option_container_"+panel_number).hasClass("active")){
    $("#more_option_container_"+panel_number).slideDown("slow").addClass("active");
    $(event.target).html(dict["hideOptions"]);
  } else {
    $("#more_option_container_"+panel_number).slideUp("slow").removeClass("active");
    $(event.target).html(dict["showMoreOptions"]);
  }
}

function toggleTimeRepMode(event, panel_number){
  if(!$(event.target).hasClass("active")){
    $(event.target).addClass("active");
    $(event.target).siblings("a.active").removeClass("active");
    
    if($(event.target).hasClass("time_switch")){
      $(".time_rep_switch_"+panel_number).html("Time (sec)");
      $("input[name='reps_"+panel_number+"']").attr("placeholder","time (sec)");
      $("input[name='more_reps_"+panel_number+"']").attr("placeholder","time (sec)");
    }else{
      $(".time_rep_switch_"+panel_number).html("Repetitions");
      $("input[name='reps_"+panel_number+"']").attr("placeholder","reps");
      $("input[name='more_reps_"+panel_number+"']").attr("placeholder","reps");
    }
  }
}

function bindNumberInputs(){
  $(".number_input").change(function(evt){
    checkNumberInput($(this));
  });

  $(".num_of_sets_input").on("change",function(evt){
    manageSets($(this),evt);
  });

  $(".weight_input").on("change", function(evt){
    copyWeightInputs($(this), evt);
  });

  $(".reps_input").on("change", function(evt){
    copyRepsInputs($(this), evt);
  });
}

function checkNumberInput(element){
  var min = parseInt(element.attr("min"));
  var val = element.val();
  if(isNaN(val) || val < min){
    element.val("");
    return false;
  }
  return true;
}

function manageSets(element,evt){
  var val = parseInt(element.val());
  if(val > 0 && !isNaN(val)){
    var old = parseInt(element.attr("old-value"));

    var panel_index = element.closest(".exercise_field_panel").attr("panel-index");
    var reps = element.closest(".exercise_field_panel").find(".reps_input").val();
    var weight = element.closest(".exercise_field_panel").find(".weight_input").val();
    var timeBound = $("#bound_switch_"+panel_index).children(".time_switch").hasClass("active");
    if(val > old && val != 1){
      for(var i=old+1;i<=val;i++)
        $(addSetToEdit(i,panel_index,reps,weight,timeBound)).hide().appendTo($("#more_option_container_"+panel_index+" ul")).fadeIn(500);
     
      element.attr("old-value",val);
    } else {
      for(var i=val;i<old;i++)
        removeLastSetToEdit(panel_index);
      element.attr("old-value",val);
    }
  }
  bindNumberInputs();
  if(evt !== null && evt !== undefined){
    evt.stopPropagation();
    evt.preventDefault();
    evt.stopImmediatePropagation();
  }
}

function copyWeightInputs(element, evt){
  var panel_index = element.closest(".exercise_field_panel").attr("panel-index");
  $("input[name='more_weight_"+panel_index+"']").val(element.val());
}
function copyRepsInputs(element, evt){
  var panel_index = element.closest(".exercise_field_panel").attr("panel-index");
  $("input[name='more_reps_"+panel_index+"']").val(element.val());
}

function fetchRepFields(panel_id){
  // Get Sets : Will be an array
  var sets    = parseInt($("input[name='sets_"+panel_id+"']").val());
  sets = ( isNaN(sets) ? DEFAULT_SETS : sets);
  
  // Get Weights : Will be an array
  var weights = [];
  $("input[name='more_weight_"+panel_id+"']").each(function(){
    var t_weight = parseFloat($(this).val());
    weight = (isNaN(t_weight) ? DEFAULT_WEIGHT : t_weight);
    weights.push(weight);
  });
  
  // Get Rep Number : Will be an int
  var reps    = [];
  $("input[name='more_reps_"+panel_id+"']").each(function(){
    var t_rep = parseInt($(this).val());
    rep = (isNaN(t_rep) ? DEFAULT_REPS : t_rep);
    reps.push(rep);
  });
  
  // Get rest btwn sets : Will be an int
  var rest    = parseInt($("input[name='rest_"+panel_id+"']").val());
  rest = (isNaN(rest) ? DEFAULT_REST : rest);

  // Get note
  var note = $("textarea[name='note_"+panel_id+"']").val();
  var tempo1 = $("input[name='tempo1_"+panel_id+"']").val();
  var tempo2 = $("input[name='tempo2_"+panel_id+"']").val();
  var tempo3 = $("input[name='tempo3_"+panel_id+"']").val();
  var tempo4 = $("input[name='tempo4_"+panel_id+"']").val();

  return {"sets":sets,"weights":weights,"reps":reps,"rest":rest,"note":note,"tempo1":tempo1,"tempo2":tempo2,"tempo3":tempo3,"tempo4":tempo4};
}

function fetchCardioFields(panel_id){
  // Get Sets : Will be an array
  var times = [];
  $("input[name='time_"+panel_id+"']").each(function(){
     var time = parseFloat($(this).val());
     M_DEFAULT_TIME = (USER_DEFAULT_TIME != "") ? USER_DEFAULT_TIME : DEFAULT_TIME;
     M_DEFAULT_DISTANCE = (USER_DEFAULT_DISTANCE != "") ? USER_DEFAULT_DISTANCE : DEFAULT_DISTANCE;
     M_DEFAULT_SPEED = (USER_DEFAULT_SPEED != "") ? USER_DEFAULT_SPEED : DEFAULT_SPEED;
     M_DEFAULT_BPM = (USER_DEFAULT_BPM != "") ? USER_DEFAULT_BPM : DEFAULT_BPM;

     time =  ( isNaN(time) ? (M_DEFAULT_TIME) : time);
     times.push(time);
  });
  
  // Get Distances : Will be an array
  var dists = [];
  $("input[name='distance_"+panel_id+"']").each(function(){
     var dist = parseFloat($(this).val());
     dist =  ( isNaN(dist) ? M_DEFAULT_DISTANCE: dist);
     dists.push(dist);
  });

  // Get Speeds : Will be an array
  var speeds = [];
  $("input[name='speed_"+panel_id+"']").each(function(){
     var speed = parseFloat($(this).val());
     speed =  ( isNaN(speed) ? M_DEFAULT_SPEED : speed);
     speeds.push(speed);
  });

  // Get Bpms : Will be an array
  var bpms = [];
  $("input[name='bpm_"+panel_id+"']").each(function(){
     var bpm = parseFloat($(this).val());
     bpm =  ( isNaN(bpm) ? M_DEFAULT_BPM : bpm);
     bpms.push(bpm);
  });

  var intervals = fieldsToInterval(times,dists,speeds,bpms);

  return intervals;
}

function fetchNote(panel_id){
  return $("textarea[name='note_"+panel_id+"']").val();
}

function fetchTempo(panel_id,index){
  return $("input[name='tempo"+index+"_"+panel_id+"']").val();
}

function saveRepExerciseField(panel_id, circuitMode){
  var fields = fetchRepFields(panel_id);
  var timeBound = $("#bound_switch_"+panel_id).children(".time_switch").hasClass("active");

  if(circuitMode){
    var indexes = getCircuitEditExercises(panel_id);
    for(var i=0;i<indexes.length;++i){
        saveClassicalExercise(fields, exercise_groups[panel_id][indexes[i]],timeBound);
    }
  }else{
    saveClassicalExercise(fields, exercise_groups[panel_id][0],timeBound);
  }

  toggleEditExercisePanel(false, panel_id);
  clearEditPanel(panel_id);
}

function saveCardioExerciseField(panel_id, circuitMode){
  var intervs = fetchCardioFields(panel_id);
  var note    = fetchNote(panel_id);
  var tempo1    = fetchTempo(panel_id,1);
  var tempo2    = fetchTempo(panel_id,2);
  var tempo3    = fetchTempo(panel_id,3);
  var tempo4    = fetchTempo(panel_id,4);
  if(circuitMode){
    var indexes = getCircuitEditExercises(panel_id);
    for(var i=0;i<indexes.length;++i){
      exercise_groups[panel_id][indexes[i]].intervals = intervs;
      exercise_groups[panel_id][indexes[i]].notes      = note;
      exercise_groups[panel_id][indexes[i]].tempo1      = tempo1;
      exercise_groups[panel_id][indexes[i]].tempo2      = tempo2;
      exercise_groups[panel_id][indexes[i]].tempo3      = tempo3;
      exercise_groups[panel_id][indexes[i]].tempo4      = tempo4;
      if(!exercise_groups[panel_id][indexes[i]].edited){
        savedExercises++;
      }
      exercise_groups[panel_id][indexes[i]].edited    = true;
     
      exercise_groups[panel_id][indexes[i]].edited = true;
      
      
      // New
      markAsEdited(exercise_groups[panel_id][indexes[i]]);
    }
  } else {
    exercise_groups[panel_id][0].intervals = intervs;
    exercise_groups[panel_id][0].notes     = note;
    exercise_groups[panel_id][0].tempo1      = tempo1;
    exercise_groups[panel_id][0].tempo2      = tempo2;
    exercise_groups[panel_id][0].tempo3      = tempo3;
    exercise_groups[panel_id][0].tempo4      = tempo4;
    if(!exercise_groups[panel_id][0].edited){
        savedExercises++;
      }
    exercise_groups[panel_id][0].edited    = true;
    
    // New
    markAsEdited(exercise_groups[panel_id][0]);
  }

  callForEvent('workout-save-edit-set-cardio',{"workout-name":workoutName});

  toggleEditExercisePanel(false, panel_id);
  
  clearEditPanel(panel_id);

}


function saveClassicalExercise(fields, exercise,timeBound){
  exercise.repArray = fields.reps;
  exercise.sets     = fields.sets;
  exercise.rest     = fields.rest;
  exercise.weights  = fields.weights;
  exercise.type     = (timeBound ? "time" : "reps");
  exercise.notes    = fields.note; 
  exercise.tempo1    = fields.tempo1; 
  exercise.tempo2    = fields.tempo2; 
  exercise.tempo3    = fields.tempo3; 
  exercise.tempo4    = fields.tempo4; 
  if(!exercise.edited){
    savedExercises++;
  }
  exercise.edited   = true;
  exercise.modified = true;

  callForEvent('workout-save-edit-set-normal',{"workout-name":workoutName});

  markAsEdited(exercise);
}

function addCardioInterval(event, panel_id){
  var cardio_panel = $("#cardio_interval_"+panel_id);

  var last_panel = cardio_panel.children(".exercise_field_panel:last-child");
  var last_time   = (USER_DEFAULT_TIME != "") ? USER_DEFAULT_TIME : DEFAULT_TIME;
  var last_dist   = (USER_DEFAULT_DISTANCE != "") ? USER_DEFAULT_DISTANCE : DEFAULT_DISTANCE;
  var last_speed  = (USER_DEFAULT_SPEED != "") ? USER_DEFAULT_SPEED : DEFAULT_SPEED;
  var last_bpm    = (USER_DEFAULT_BPM != "") ? USER_DEFAULT_BPM : DEFAULT_BPM;

  var html = addIntervalCardioToEdit(panel_id, {"last_time":last_time,"last_dist":last_dist,"last_speed":last_speed,"last_bpm":last_bpm});
  $(html).hide().appendTo(cardio_panel).fadeIn(500);

  callForEvent('workout-add-cardio-interval',{"workout-name":workoutName});


}


function fieldsToInterval(times,dists,speeds,bpms){
  var intervals = [];

  for(var i=0;i<times.length;++i){
    var interval = {};
    interval.time  = times[i];
    interval.dist  = dists[i];
    interval.speed = speeds[i];
    interval.bpm   = bpms[i];
    intervals.push(interval);
  }

  return intervals;
}

function clearEditPanel(panel_id){
  $("#circuit_edit_panel_"+panel_id).remove();
  $("#edit_list_"+panel_id+" ul").html("");
}

function addExerciseToCircuitEdit(element, panel_id,exerciseId){

  var exercise = getExerciseFromElement(exerciseId);
  //var is_single_view = $("#edit_list_"+panel_id+" ul").children("li").hasClass("single_exercise_view");
  var is_single_view = $("#edit_list_"+panel_id+" ul").children("li").length;
  //console.log(is_single_view);
  //console.log("Edited: "+exerciseId);
  //console.log(exercise);
  callForEvent('workout-edit-exercise-in-circuit',{"workout-name":workoutName});
  if($("#workout_builder_edit_"+panel_id).find("edit_button").text() == "Click to edit"){
    toggleEditExercisePanel(event,panel_id);
  }
  if(!exercise.edited){
    if(is_single_view == 0){
      //console.log("panelLce");
      clearEditPanel(panel_id);
    }
    var latest_bodygroupID = "";

    if(panel_id in currently_edited_exercises){
      latest_bodygroupID = currently_edited_exercises[panel_id];
    } else {
      currently_edited_exercises[panel_id] = exercise.bodygroupId;
      latest_bodygroupID = exercise.bodygroupId;
    }

    var len =  $("#edit_list_"+panel_id+" ul").children("li").length;
    //console.log("Elements: "+len);
    if(len == 0){
      latest_bodygroupID = exercise.bodygroupId;
      currently_edited_exercises[panel_id] = exercise.bodygroupId;
    }


    var isSelected = $(element).closest(".exercisesimages").find("div.exercise_state").length > 0;
   
    if( (exercise.bodygroupId == 18 && latest_bodygroupID != 18) || (exercise.bodygroupId != 18 && latest_bodygroupID == 18) || isSelected){
      //console.log("Cant.");
    } else {
      var is_single_viewText = (is_single_view ? "" : "single_exercise_view");
      var html = '<li edit-id="'+exercise.editID+'" class="'+is_single_viewText+'">'+
                  '<div class="exercisesimages">'+
                    '<div class="exercise_state unselect_exercise" onclick="removeFromCircuitEdit(this,'+panel_id+')">+</div>'+
                    '<img src="/' + exercise.image + '" alt="' + exercise.name + '" onerror="imgError(this);">'+
                  '</div>'+
                '</li>';
      $(html).hide().appendTo($("#edit_list_"+panel_id+" ul")).fadeIn(500);
      $(element).closest(".exercisesimages").append('<div class="exercise_added exercise_state" style="opacity:1"><i class="fa fa-pencil"></i></div>');

      if(len == 0){
        
        if(latest_bodygroupID == 18)
          $(tmpl_editExerciseFieldsCardioBound(panel_id,true,exercise)).hide().appendTo($("#exercise_edit_panel_"+panel_id)).fadeIn(500);
        else
          $(tmpl_editExerciseFieldsRepBound(panel_id,true,exercise)).hide().appendTo($("#exercise_edit_panel_"+panel_id)).fadeIn(500);
      }
    }

  } else {
    $("#circuit_edit_panel_"+panel_id).remove();
    $("#edit_list_"+panel_id+" ul").html("");
    var is_single_viewText = (is_single_view ? "" : "single_exercise_view");
    var html = '<li edit-id="'+exercise.editID+'" class="'+is_single_viewText+'">'+
                  '<div class="exercisesimages">'+
                    '<div class="exercise_state unselect_exercise" onclick="removeFromCircuitEdit(this,'+panel_id+')">+</div>'+
                    '<img src="/' + exercise.image + '" alt="' + exercise.name + '" onerror="imgError(this);">'+
                  '</div>'+
                '</li>';
    $(html).hide().appendTo($("#edit_list_"+panel_id+" ul")).fadeIn(500);

    if(exercise.bodygroupId == 18)
      $(tmpl_editExerciseFieldsCardioBound(panel_id,true,exercise)).hide().appendTo($("#exercise_edit_panel_"+panel_id)).fadeIn(500);
    else
      $(tmpl_editExerciseFieldsRepBound(panel_id,true,exercise)).hide().appendTo($("#exercise_edit_panel_"+panel_id)).fadeIn(500);
  }

  bindNumberInputs();
}


function removeFromCircuitEdit(element,panel_id){
  var list_element = $(element).parent().parent("li"); 
  list_element.remove();
  callForEvent('workout-remove-exercise-in-circuit',{"workout-name":workoutName});
  $("#circuit_edit_"+list_element.attr("edit-id")).find(".exercise_state").remove();
  var len =  $("#edit_list_"+panel_id+" ul").children("li").length;
  if(len == 0){
    $("#circuit_edit_panel_"+panel_id).remove();
  }
}

function getExerciseFromElement(editID){
  var array = editID.split("_");
  var list_number  = array[2];
  var index_number = array[3];
  return exercise_groups[list_number][index_number]; 
}

function getCircuitEditExercises(panel_id){
  var indexes = [];
  $("#edit_list_"+panel_id+" ul li").each(function(){
      var index = $(this).attr("edit-id");

      // Change exercise state
      // $("#circuit_edit_"+index).children(".exercise_state").removeClass("exercise_added").addClass("exercise_edited");

      index = index.split("_");
      index = index[3];
      indexes.push(parseInt(index));
    });
  return indexes;
}

function addRestTime(event, panel_id) {
  $(event.target).parent().css("background-color","transparent");
  $(event.target).html('<input type="number"  onBlur="defaultRestTimeBetween(this.value,'+panel_id+')" placeholder="" name="circuit_rest_'+panel_id+'" min="0" max="1000" step="1" class="number_input rest_time_circuit_'+panel_id+'"/>');
  $(event.target).removeAttr("onclick");
  event.stopImmediatePropagation();
}

function defaultRestTimeBetween(value,panel_id){
  if(USER_DEFAULT_REST_EXERCISES_CIRCUIT != "") USER_DEFAULT_REST_EXERCISES_CIRCUIT = value;
  $(".rest_time_circuit_"+panel_id).each(function(){
      if($(this).val() == ""){
        $(this).parent().parent().css("background-color","transparent");
        $(this).parent().removeAttr("onclick");
        $(this).parent().html('<input type="number" onBlur="defaultRestTimeBetween(this.value)" value="'+value+'" placeholder="" name="circuit_rest_'+panel_id+'" min="0" max="1000" step="1" class="number_input rest_time_circuit_'+panel_id+'"/>');
      }
  });
  addRestBetweenCircuitExercise();
}

function addRestBetweenCircuitExercise(){
  
  callForEvent('workout-add-rest-between-exercises-in-circuit',{"workout-name":workoutName});
  for(x = 1; x < panel_count; x++){
    if(exercise_groups_rest[x] === undefined) exercise_groups_rest[x] = { "restBetweenCircuitExercises":[], "circuitRound":USER_DEFAULT_ROUNDS, "circuitRest":USER_DEFAULT_ROUNDS_REST };
    exercise_groups_rest[x]["restBetweenCircuitExercises"] = [];
    $(".rest_time_circuit_"+x).each(function(){
      var val = $(this).val();
      if(exercise_groups_rest[x]["restBetweenCircuitExercises"])
        exercise_groups_rest[x]["restBetweenCircuitExercises"].push(val);
      else{
        exercise_groups_rest[x]["restBetweenCircuitExercises"] = [val];
      }
    });
  }
}


function markAsEdited(exercise,circuitMode){
  var index = exercise.editID;
  $("#circuit_edit_"+index).find(".exercise_state").remove();
  //$("#circuit_edit_"+index).append('<div class="exercise_edited exercise_state" style="opacity:1"><i class="fa fa-check"></i></div>')
  $("#circuit_edit_"+index).append('<div class="exercise_edited exercise_state" style="opacity:1"><i class="fa fa-check"></i></div>');
  //$("#classical_edit_"+index).append('<div class="exercise_edited" style="opacity:1"><i class="fa fa-check"></i></div>')
  $("#classical_edit_"+index).append('<div class="exercise_edited  exercise_state" style="opacity:1"><i class="fa fa-check"></i></div>');
  console.log("Saved "+savedExercises);
  console.log("Total "+totalExercises);

  monitorWorkoutSaved();
}


function moveGroup(where,group){
  if (where == "Up" && group != 1){
    if(exercise_groups[group-1] !== undefined){
      var temp = exercise_groups[group-1];
      exercise_groups[group-1] = exercise_groups[group];
      exercise_groups[group] = temp;
      fixIdsExercises(group);
      fixIdsExercises(group-1);
      if(exercise_groups_rest[group-1] !== undefined && exercise_groups_rest[group] !== undefined){
        temp = exercise_groups_rest[group-1];
        exercise_groups_rest[group-1] = exercise_groups_rest[group];
        exercise_groups_rest[group] = temp;
      }
    }
  } 
  if(where == "Down" && group != panel_count-1){
    if(exercise_groups[group+1] !== undefined){
      var temp = exercise_groups[group+1];
      exercise_groups[group+1] = exercise_groups[group];
      exercise_groups[group] = temp;
      fixIdsExercises(group);
      fixIdsExercises(group+1);
      if(exercise_groups_rest[group+1] !== undefined && exercise_groups_rest[group] !== undefined){
        temp = exercise_groups_rest[group+1];
        exercise_groups_rest[group+1] = exercise_groups_rest[group];
        exercise_groups_rest[group] = temp;
      }
    }
  }
  callForEvent('workout-move-group-position',{"workout-name":workoutName});
  recreateStep3FromExerciseGroups();
  recreateStep4(false,true);
  addNewPanel();
}

function fixIdsExercises(origin){
  for(var x = 0; x < exercise_groups[origin].length; x++){
    var exerciseIdString = exercise_groups[origin][x].editID;
    exerciseIdArray = exerciseIdString.split("_");
    exerciseIdArray[2] = origin;
    exerciseIdArray[3] = x;
    var newID = exerciseIdArray.join("_");
    exercise_groups[origin][x].editID = newID;
  }
}

function moveLeft(group,exerciseIndex){
    if(exerciseIndex !=  0 && exercise_groups[group][exerciseIndex-1] !== undefined){
      var temp = exercise_groups[group][exerciseIndex-1];
      exercise_groups[group][exerciseIndex-1] = exercise_groups[group][exerciseIndex];
      exercise_groups[group][exerciseIndex] = temp;
    }
    fixIdsExercises(group);
    callForEvent
    ('workout-move-exercise-position',{"workout-name":workoutName});
    recreateStep4(false);
}

function moveRight(group,exerciseIndex){
  if(exerciseIndex !=  (exercise_groups[group].length-1) && exercise_groups[group][exerciseIndex+1] !== undefined){
      var temp = exercise_groups[group][exerciseIndex+1];
      exercise_groups[group][exerciseIndex+1] = exercise_groups[group][exerciseIndex];
      exercise_groups[group][exerciseIndex] = temp;
    }
    fixIdsExercises(group);
    callForEvent('workout-move-exercise-position',{"workout-name":workoutName});
    recreateStep4(false);
}

function setDefault(variableName,value,className){
  if(window[variableName] !== "undefined"){
    if(window[variableName] == ""){
      window[variableName] = value;
      if(className !== "undefined" && className != ""){
        $("."+className).each(function(){
          if($(this).val() == ""){
            $(this).val(value);
          }
        });
      }
    }
  }
  if(className == "circuit_round" || className == "circuit_rest"){
    saveCircuitInfoAllPanels();
  }
}

function setDefaultOverride(variableName,value,className){
  if(window[variableName] !== "undefined"){
    if(window[variableName] == ""){
      window[variableName] = value;
      if(className !== "undefined" && className != ""){
        $("."+className).each(function(){
            $(this).val(value);
        });
      }
    }
  }
}

function setDefaultOverrideSets(variableName,value,className){
  if(window[variableName] !== "undefined"){
    if(window[variableName] == ""){
      window[variableName] = value;
      if(className !== "undefined" && className != ""){
        $("."+className).each(function(){
            $(this).val(value);
            manageSets($(this),null);
        });
      }
    }
  }
}

function propagateFirstEdit(target,value,classs){
  
  $(target).closest("ul").find("."+classs).each(function(){
 
    if($(this).val() == 0 || $(this).val() == "" || $(this).val() == "0"){
        $(this).val(value);
    }
  });
}

function propagateCardio(target,value,classs){
  
  $(target).closest(".cardio_interval").find("."+classs).each(function(){
 
    if($(this).val() == 0 || $(this).val() == "" || $(this).val() == "0"){
        $(this).val(value);
    }
  });
}

//SAVE EXERCISES

//JSON.parse()

//recreateStep3FromExerciseGroups();

//showStep3();

//bindDragDropEvent();

// classical_edit_2667__1_0