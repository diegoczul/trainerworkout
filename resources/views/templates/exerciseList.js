// Classical Sets
var DEFAULT_SETS   = 3;
var DEFAULT_REPS   = 12;
var DEFAULT_WEIGHT = 0;
var DEFAULT_REST   = 30;

// CIRCUIT Sets
var DEFAULT_SETS_CIRCUIT   = 1;
var DEFAULT_REPS_CIRCUIT   = 20;
var DEFAULT_WEIGHT_CIRCUIT = 0;
var DEFAULT_REST_CIRCUIT   = 0;

// Cardio Sets
var DEFAULT_INTERVALS  = 1;

var DEFAULT_TIME       = 15;
var DEFAULT_DISTANCE   = 0;
var DEFAULT_SPEED      = 0;
var DEFAULT_BPM        = 0;

var saved = 0;
//
function tmpl_editExerciseCircuit(exercise, panel_index,exerciseNumber,exercise_groups_rest){
	var html = '<li class="change_droppable" id="circuit_edit_'+exercise.editID+'" exercise-id="'+exercise.elementID+'" edit-id="'+exercise.editID+'">'+
			'<div class="exercisesimages">'+
				(exercise.edited ? '<div class="exercise_edited exercise_state" style="opacity:1"><i class="fa fa-check"></i></div>' : '')+
				'<div class="exercise_overlay">'+
                  '<div class="exercise_overlay_move overlay_step4" onclick="">'+
                  			'<img class="overlay_leftArrow" src="/img/move_left_button_.png" onClick="moveLeft('+panel_index+','+exerciseNumber+')">'+
                  			'<img class="overlay_rightArrow" src="/img/move_right_button_.png" onClick="moveRight('+panel_index+','+exerciseNumber+')">'+
              		'</div>'+
              		'<div class="exercise_overlay_edit overlay_step4" id="editOverlay" onclick="addExerciseToCircuitEdit(this,'+panel_index+',\''+exercise.editID+'\');editExOnCircuit(event,'+panel_index+')" exercise-id="'+exercise.elementID+'" edit-id="'+exercise.editID+'">'+dict["Edit"]+'</div>'+
                '</div>'+
			  '<span>' + exercise.name + '</span><img src="/' + exercise.image + '" alt="' + exercise.name + '" onerror="imgError(this);">'+
			'</div>'+
			// (exercise.edited ? '<div class="exercise_edited exercise_state" style="opacity:1"><i class="fa fa-pencil"></i></div>' : '')+
		   '</li>';
		   var hide = "";
		   if(exercise_groups[panel_index].length-1 == exerciseNumber){
		   		hide = "display:none;";
		   }
			   if(exercise_groups_rest[panel_index].restBetweenCircuitExercises !== undefined && exercise_groups_rest[panel_index].restBetweenCircuitExercises[exerciseNumber] !== undefined && exercise_groups_rest[panel_index].restBetweenCircuitExercises[exerciseNumber] != ""){
			   		html += '<li style="background-color:transparent!important '+hide+'" class="rest_time" panel-id="'+panel_index+'"><div><input type="number" placeholder="" value="'+exercise_groups_rest[panel_index].restBetweenCircuitExercises[exerciseNumber] +'" name="circuit_rest_'+panel_index+'" min="0" max="1000" step="1" class="number_input rest_time_circuit rest_time_circuit_'+panel_index+'"/></div></li>';
				} else {
					html += '<li style="'+hide+'" class="rest_time" panel-id="'+panel_index+'"><div class="listenerRestTime" onclick="addRestTime(event,'+panel_index+')">'+dict["addRestTime"]+'<input onBlur="setDefault(\'USER_DEFAULT_REST_EXERCISES_CIRCUIT\',this.value,\'rest_time_circuit\')" class="rest_time_circuit rest_time_circuit_'+panel_index+'" type="hidden" value="'+USER_DEFAULT_REST_EXERCISES_CIRCUIT+'" name="circuit_rest_'+panel_index+'" /></div></li>';
				}
			
		return html;
}

function tmpl_editExerciseClassical(exercise, panel_index){
	return '<li class="change_droppable" id="classical_edit_'+exercise.editID+'" exercise-id="'+exercise.elementID+'" edit-id="'+exercise.editID+'" onclick="toggleEditExercisePanel(event,'+panel_index+')">'+
            '<div class="exercisesimages" >'+
              '<span>' + exercise.name + '</span><img src="/' + exercise.image + '" alt="' + exercise.name + '" onerror="imgError(this);">'+
            '</div>'+
            (exercise.edited ? '<div class="exercise_edited exercise_state" style="opacity:1"><i class="fa fa-check"></i></div>' : '')+
          '</li>';
}

function tmpl_editExercisePanel(exercise_list, panel_index, panel_id,circuitMode, exercises){
	return '<div class="workout_builder_panel" id="workout_builder_edit_'+panel_id+'"  >'+
                '<div class="panel_number">'+
                	'<div class="panel_overlay_step4" id="movePanel" onclick="">'+
                		'<div class="panel_overlay_arrows">'+

                			'<img class="overlay_upArrow" src="/img/move_up_button_.png" onClick="moveGroup(\'Up\','+panel_index+')">'+
                  			'<img class="overlay_downArrow" src="/img/move_down_button_.png" onClick="moveGroup(\'Down\','+panel_index+')">'+

                		'</div>'+
                	'</div>'+
                	'<div class="panel_index">'+''+'</div></div>'+
                '<div class="workout_drop_area exercise_list_small workout_edit_exercise_area">'+
                  '<ul>'+
                    exercise_list+
                  '</ul>'+
                '</div>'+
                '<div class="edit_type_switch click_expand_fix" id="edit_type_switch_'+panel_id+'" onclick="toggleEditExercisePanel(event,'+panel_id+')"   >'+
                  '<span class="edit_button">'+dict["clickToEdit"]+'</span>'+
                  '<div class="edit_expand">+</div>'+
                '</div>'+
            '</div>'+
            (exercises.length == 1 ?
            	(exercises[0].bodygroupId == 18 ?
            		tmpl_editExerciseFieldsCardioBound(panel_id,false,exercises[0])
            		:
            		tmpl_editExerciseFieldsRepBound(panel_id,false,exercises[0])
            	)
             	: 
             	tmpl_editExerciseFieldsCircuitMode(panel_id,exercises)
            ); 
}


function tmpl_editExerciseFieldsRepBound(panel_id, circuitMode,exercise){
	var id 		= (circuitMode ? "circuit_edit_panel_"+panel_id : "exercise_edit_panel_"+panel_id);

	//var sets    = (exercise.sets ? exercise.sets : DEFAULT_SETS);
	//var weights = (exercise.weights ? exercise.weights : [DEFAULT_WEIGHT,DEFAULT_WEIGHT,DEFAULT_WEIGHT]);
	//var reps 	= (exercise.repArray ? exercise.repArray : [DEFAULT_REPS,DEFAULT_REPS,DEFAULT_REPS]);
	//var rest 	= (exercise.rest ? exercise.rest : DEFAULT_REST);

	if(exercise.modified !== undefined){
		exercise.modified = false;
	}


	//console.log("ExerciseModified = "+exercise.edited);
	//console.log("Sets = "+exercise.sets);

	var sets = DEFAULT_SETS;
	var intervals = DEFAULT_INTERVALS;

	if(exercise.sets && exercise.edited){
		sets = exercise.sets;
	} else {
		if(circuitMode){
			sets = (USER_DEFAULT_SETS != "" ) ? USER_DEFAULT_SETS : DEFAULT_SETS_CIRCUIT;
		} else {
			sets = (USER_DEFAULT_SETS != "" ) ? USER_DEFAULT_SETS : DEFAULT_SETS;
		}
	}

	//console.log("Sets = "+exercise.sets);


	exercise.sets = sets;

	var rest = 0;
	if(exercise.rest  && exercise.edited){
		rest = exercise.rest;
	} else {
		if(circuitMode){
			rest = (USER_DEFAULT_REST != "" ) ? USER_DEFAULT_REST : DEFAULT_REST_CIRCUIT; 
		} else {
			rest = (USER_DEFAULT_REST != "" ) ? USER_DEFAULT_REST : DEFAULT_REST;
		}
	}

	exercise.rest = rest;
	

	var reps = [];
	if(exercise.repArray && exercise.edited){
		reps = exercise.repArray;
	} else {
		if(circuitMode){
			reps = [];
			for(x = 0; x< sets; x++){
				reps[x] = (USER_DEFAULT_REPS != "" ) ? USER_DEFAULT_REPS : DEFAULT_REPS_CIRCUIT;
			}
		} else {
			reps = [];
			for(x = 0; x< sets; x++){
				reps[x] = (USER_DEFAULT_REPS != "" ) ? USER_DEFAULT_REPS : DEFAULT_REPS;
			}
		}
	}

	if(exercise.bodygroupId == 18){
		var intervals = [];
		if(exercise.intervals && exercise.edited){
			intervals = exercise.intervals;
		} else {
				intervals = [];
				for(x = 0; x< intervals; x++){
					intervals[x]["bpm"] = (USER_DEFAULT_BPM != "" ) ? USER_DEFAULT_BPM : DEFAULT_BPM;
					intervals[x]["speed"] = (USER_DEFAULT_SPEED != "" ) ? USER_DEFAULT_SPEED : DEFAULT_SPEED;
					intervals[x]["dist"] = (USER_DEFAULT_DISTANCE != "" ) ? USER_DEFAULT_DISTANCE : DEFAULT_DISTANCE;
					intervals[x]["time"] = (USER_DEFAULT_TIME != "" ) ? USER_DEFAULT_TIME : DEFAULT_TIME;
				}
		}
	}

	var weights = [];
	if(exercise.weights && exercise.edited){
		weights = exercise.weights;
	} else {
		if(circuitMode){
			weights = [];
			for(x = 0; x< sets; x++){
				weights[x] = (USER_DEFAULT_WEIGHT != "" ) ? USER_DEFAULT_WEIGHT : DEFAULT_WEIGHT_CIRCUIT;
			}
		} else {
			weights = [];
			for(x = 0; x< sets; x++){
				weights[x] = (USER_DEFAULT_WEIGHT != "" ) ? USER_DEFAULT_WEIGHT : DEFAULT_WEIGHT;
			}
		}
	}

	var setsView = '';

	var notes = (exercise.notes ? exercise.notes : "");
	// if(note == "")
	// 	exercise.note = "";


	//ADDED BY DIEGO --------------------------------------------------------------------------------
	var ttype = "";
	ttype = (exercise.type !== undefined) ? exercise.type : "reps";
	var activeReps = "";
	var activeTime = "";
	var secondsLabel = "disappear";
	$(".time_switch").removeClass("active");
	$(".rep_switch").removeClass("active");
	var activeTime = "";
	var activeReps = "";
	if(ttype == "time"){

		$(".time_switch").addClass("active");
      $(".time_rep_switch_"+panel_id).html(dict["Time"]);

      $("input[name='reps_"+panel_id+"']").attr("placeholder",dict["time"]+" ("+dict["sec"]+")");
      $("input[name='more_reps_"+panel_id+"']").attr("placeholder",dict["time"]+" ("+dict["sec"]+")");
      activeTime = "active";
     
    }else{
    	
    	activeReps = "active";
    	$(".rep_switch").addClass("active");
      $(".time_rep_switch_"+panel_id).html(dict["Repetitions"]);
      $("input[name='reps_"+panel_id+"']").attr("placeholder",dict["reps"]);
      $("input[name='more_reps_"+panel_id+"']").attr("placeholder",dict["reps"]);
      activeReps = "active";
      
    }
    //ADDED BY DIEGO --------------------------------------------------------------------------------

	for(var i=0;i<weights.length;++i){
		setsView += '<li>'+
						'<div class="set_label">'+dict["Set#"]+(i+1)+'</div>'+
						'<div class=""><input type="number" value="'+reps[i]+'" placeholder="'+dict["reps"]+'" min="1" max="500" step="1" name="more_reps_'+panel_id+'" class="number_input"/></div>'+
						'<div class=""><input type="number" onblur="propagateFirstEdit(this,this.value,\'weight_input\')" value="'+weights[i]+'" placeholder="'+dict["weight"]+'" min="1" max="10000" step="1" name="more_weight_'+panel_id+'" class="number_input weight_input"/></div>'+
					'</li>';
	}

	var editHTML='';
	editHTML+= 	'<div class="exercise_edit_panel" id="'+id+'" edit-panel-id="'+panel_id+'">'+
              '<div class="bound_switch" id="bound_switch_'+panel_id+'" onclick="toggleTimeRepMode(event,'+panel_id+')">'+
              	'<a href="javascript:void(0)" class="'+activeReps+' rep_switch">'+dict["RepetitionBound"]+'</a>'+
              	'<a href="javascript:void(0)" class="'+activeTime+' time_switch">'+dict["TimeBound"]+'</a>'+
              '</div>'+
              '<div class="exercise_field_panel" panel-index="'+panel_id+'">'+
				'<label for="">'+dict["numberOfSets"]+'</label>'+
		   		'<input type="number" onblur="setDefaultOverrideSets(\'USER_DEFAULT_SETS\',this.value,\'num_of_sets_input\')" value="'+sets+'" placeholder="'+dict["#ofSets"]+'" name="sets_'+panel_id+'" min="1" max="100" step="1" class="number_input num_of_sets_input" old-value="'+sets+'"   data-validate="number"/>'+
		   		'<label for="">'+dict["Weight"]+' (Lbs)</label>'+
		   		'<input type="number" onblur="setDefaultOverride(\'USER_DEFAULT_WEIGHT\',this.value,\'weight_input\')" value="'+weights[0]+'" placeholder="'+dict["Weight"]+'" name="weight_'+panel_id+'" min="1" max="10000" step="1" class="number_input weight_input"   data-validate="number"/>'+
		   		'<label for="" class="time_rep_switch_'+panel_id+'">'+dict["Repetitions"]+'</label>'+

		   		'<input type="number" onblur="setDefaultOverride(\'USER_DEFAULT_REPS\',this.value,\'reps_input\')"  value="'+reps[0]+'" placeholder="'+dict["reps"]+'" name="reps_'+panel_id+'" min="1" max="500" step="1" class="number_input reps_input"   data-validate="number"/>'+

		   		'<label for="">'+dict["RestBetweenSets"]+'</label>'+
		   		'<input type="number" onblur="setDefaultOverride(\'USER_DEFAULT_REST\',this.value,\'rest_input\')"   value="'+rest+'" placeholder="'+dict["rest"]+' ('+dict["sec"]+')" name="rest_'+panel_id+'" min="0" max="1000" step="5" class="number_input rest_input"   data-validate="number"/>'+
		   	  '</div>';

		   	  if(circuitMode == false){
					editHTML+='<div class="exercise_field_panel exercise_field_tempo" panel-index="'+panel_id+'">';
				}else{
					editHTML+='<div class="exercise_field_panel exercise_field_tempo exercise_field_tempo_circuit" panel-index="'+panel_id+'">';
				}

	editHTML+=	'<span>'+dict["Tempo"]+'</span></br>'+
		   	  		'<div class="tempos_input">'+
		   	  		'<input type="text" name="tempo1_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo2_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo3_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo4_'+panel_id+'"   data-validate="number"/> '+
		   	  		'</div>'+
		   	  '</div>' +

		   	  '<div style="text-align:center">'+
		   	  	'<textarea rows="2" placeholder="'+dict["addAComment"]+'" class="exercise_note" name="note_'+panel_id+'">'+notes+'</textarea>'+
		   	  '</div>'+
		   	  

              '<div class="more_option_button_container">'+
              	'<a href="javascript:void(0)" class="exercise_edit_more" onclick="toggleMoreOptions(event,'+panel_id+')">'+dict["showMoreOptions"]+'</a>'+
              '</div>'+
              '<div class="more_option_container" id="more_option_container_'+panel_id+'">'+
              	'<div class="set_holder">'+
				'<ul>'+
					'<li class="row_label">'+
						'<div class="time_rep_switch_'+panel_id+'">'+dict["Repetitions"]+'</div>'+
						'<div class="">'+dict["Weight"]+'</div>'+
					'</li>'+
					setsView+
				'</ul>'+
		   	  '</div>'+
              '</div>'+
              '<a href="javascript:void(0)" class="exercise_edit_save" onclick="saveRepExerciseField('+panel_id+','+circuitMode+')">'+dict["save"]+'</a>'+
            '</div>';
    return editHTML;
}

function tmpl_editExerciseFieldsCardioBound(panel_id,circuitMode, exercise){
	var id = (circuitMode ? "circuit_edit_panel_"+panel_id : "exercise_edit_panel_"+panel_id);
	//console.log(exercise.intervals );
	var intervals = (exercise.intervals ? exercise.intervals : [{
		time  : (USER_DEFAULT_TIME != "") ? USER_DEFAULT_TIME : DEFAULT_TIME,
	    dist  : (USER_DEFAULT_DISTANCE != "") ? DEFAULT_DISTANCE : DEFAULT_DISTANCE,
	    speed : (USER_DEFAULT_SPEED != "") ? DEFAULT_SPEED : DEFAULT_SPEED,
	    bpm   : (USER_DEFAULT_BPM != "") ? DEFAULT_BPM : DEFAULT_BPM

	}]);
	//console.log(intervals );
	//console.log("USER_DEFAULT_TIME "+USER_DEFAULT_TIME);
	//console.log("USER_DEFAULT_SPEED "+USER_DEFAULT_SPEED);
	//console.log("USER_DEFAULT_TIME "+USER_DEFAULT_TIME);
	var notes = (exercise.notes ? exercise.notes : "");
	// if(note == "")
	// 	exercise.note = "";

	var intervalHTML = '';
	for(var i=0;i<intervals.length;++i){
		intervalHTML += '<div id="cardio_interval_'+panel_id+'" class="cardio_interval">'+
							'<div class="exercise_field_panel" panel-index="'+panel_id+'">'+
								'<label for="">'+dict["Time"]+' (min)</label>'+
						   		'<input onBlur="setDefaultOverride(\'USER_DEFAULT_TIME\',this.value,\'fTime\')" type="number" value="'+intervals[i].time+'" placeholder="in min" name="time_'+panel_id+'" min="0" max="1000" step="0.5" class="number_input fTime"  data-validate="number"/>'+
						   		'<label for="">'+dict["Distance"]+' (km)</label>'+
						   		'<input onBlur="setDefaultOverride(\'USER_DEFAULT_DISTANCE\',this.value,\'fDistance\')" type="number" value="'+intervals[i].dist+'" placeholder="in m" name="distance_'+panel_id+'" min="0" max="100000" step="50" class="number_input fDistance"  data-validate="decimal"/>'+
						   		'<label for="">'+dict["Speed"]+' (km/h)</label>'+
						   		'<input onBlur="setDefaultOverride(\'USER_DEFAULT_SPEED\',this.value,\'fSpeed\')" type="number" value="'+intervals[i].speed+'" placeholder="in km/h" name="speed_'+panel_id+'" min="0" max="1000" step="1" class="number_input fSpeed"  data-validate="number"/>'+
						   		'<label for="">'+dict["HeartRate"]+' ('+dict["Bpm"]+')</label>'+
						   		'<input onBlur="setDefaultOverride(\'USER_DEFAULT_BPM\',this.value,\'fBPM\')" type="number" value="'+intervals[i].bpm+'" placeholder="in bpm" name="bpm_'+panel_id+'" min="0" max="1000" step="1" class="number_input fBPM"  data-validate="number"/>'+
						   '</div>';
		if(i == intervals.length-1 && i != 0) intervalHTML += '<a class="removeInterval" href="javascript:void(0)" onClick="removeInterval('+panel_id+')" > '+dict["Removeinterval"]+'</a>';				   
		intervalHTML +=	'</div>';
	}
	intervalHTML += '<div class="more_option_button_container">'+
              		'<a href="javascript:void(0)" class="exercise_edit_more" onclick="addCardioInterval(event,'+panel_id+')">'+dict["addinterval"]+'</a>'+
               '</div>';

	var editHTML = '';
	editHTML+= 	'<div class="exercise_edit_panel" id="'+id+'" edit-panel-id="'+panel_id+'">'+
				intervalHTML;

				if(circuitMode == false){
					editHTML+='<div class="exercise_field_panel exercise_field_tempo" panel-index="'+panel_id+'">';
				}else{
					editHTML+='<div class="exercise_field_panel exercise_field_tempo exercise_field_tempo_circuit" panel-index="'+panel_id+'">';
				}
				//'<span style="display:none">Tempo</span></br>'+
				//'<div class="tempos_input" style="display:none">'+
		   	  	//'<input type="text" name="tempo1_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo2_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo3_'+panel_id+'"   data-validate="number"/> '+'<input type="text" name="tempo4_'+panel_id+'"   data-validate="number"/> '+
		   	  	//	'</div>'+
   	  	editHTML+=	
		   	  		
		   	  	'</div>' +

				'<div style="text-align:center">'+
		   	  	 	'<textarea rows="2" placeholder="'+dict["addAComment"]+'" class="exercise_note" name="note_'+panel_id+'">'+notes+'</textarea>'+
		   	    '</div>'+
		   	    '<a href="javascript:void(0)" class="exercise_edit_save" onclick="saveCardioExerciseField('+panel_id+','+circuitMode+')">'+dict["save"]+'</a>'+
		   	  '</div>' +

			   
			   
			'</div>';
	return editHTML;
}

function tmpl_editExerciseFieldsCircuitMode(panel_id){
	return 	'<div class="exercise_edit_panel" id="exercise_edit_panel_'+panel_id+'" edit-panel-id="'+panel_id+'">'+
				'<div class="edit_no_exercises">'+dict["Selectexercisestoedit"]+'</div>'+
				'<div class="exercise_list_xsmall white_border" style="min-height: 72px;margin-bottom: 30px;" id="edit_list_'+panel_id+'"><ul></ul></div>'+
			'</div>';
}

function tmpl_populateSet(panel_id) {
	return '<div class="set_holder">'+
				'<ul>'+
					'<li class="row_label">'+
						'<div class="time_rep_switch_'+panel_id+'">'+dict["Repetitions"]+'</div>'+
						'<div class="">'+dict["Weight"]+'</div>'+
					'</li>'+
					'<li>'+
						'<div class="set_label">'+dict["Set#"]+'1</div>'+
						'<div class=""><input type="number" placeholder="'+dict["reps"]+'" min="1" max="500" step="1" name="more_reps_'+panel_id+'" class="number_input"  data-validate="number"/></div>'+
						'<div class=""><input type="number weight_input" placeholder="'+dict["weight"]+'" min="2.5" max="10000" step="2.5" name="more_weight_'+panel_id+'" class="number_input"  data-validate="number"/></div>'+
					'</li>'+
				'</ul>'+
		   '</div>';

}


function addSetToEdit(index,panel_id,reps,weight,type){
	console.log("Type: "+type);
	var output =   '<li>'+
				'<div class="set_label">'+dict["Set#"]+index+'</div>';
        		console.log("Type "+type);
				if(type){
					output += '<div class=""><input  data-validate="number" type="number" placeholder="reps" min="1" max="500" step="1" name="more_reps_'+panel_id+'" class="number_input" value="'+reps+'"  style="padding-right: 36px !important;"/>'+'<span onclick="selectPrevious($(this))" class="edit time unselectable" style="display:table">sec</span></div>';
				} else {
					output += '<div class=""><input  data-validate="number" type="number" placeholder="reps" min="1" max="500" step="1" name="more_reps_'+panel_id+'" class="number_input" value="'+reps+'"/></div>';
				}
				output += '<div class=""><input  data-validate="number" type="number" placeholder="weight" min="2.5" max="10000" step="2.5" name="more_weight_'+panel_id+'" class="number_input weight_input" value="'+weight+'"/></div>'+

			'</li>';

	return output;
}

function addIntervalCardioToEdit(panel_id, exercise){
	var output = "";
	$('.exerciseRemove'+exercise.editID).remove();
	output = '<div class="exercise_field_panel exercise_field_panel_'+panel_id+'" panel-index="'+panel_id+'">'+
				'<label for="">'+dict["Time"]+' (min)</label>'+
		   		'<input type="number" placeholder="'+dict["inmin"]+'" name="time_'+panel_id+'" min="0" max="1000" step="0.5" value="'+exercise.last_time+'" class="number_input fTime"  data-validate="number"/>'+
		   		'<label for="">'+dict["Distance"]+' (km)</label>'+
		   		'<input type="number" placeholder="in m" name="distance_'+panel_id+'" min="0" max="100000" step="50" value="'+exercise.last_dist+'" class="number_input fDistance"  data-validate="decimal"/>'+
		   		'<label for="">'+dict["Speed"]+' (km/h)</label>'+
		   		'<input type="number" placeholder="in km/h" name="speed_'+panel_id+'" min="0" max="1000" step="1" value="'+exercise.last_speed+'" class="number_input fSpeed"  data-validate="number"/>'+
		   		'<label for="">'+dict["HeartRate"]+' ('+dict["bpm"]+')</label>'+
		   		'<input type="number" placeholder="'+dict["inbpm"]+'" name="bpm_'+panel_id+'" min="0" max="1000" step="1" value="'+exercise.last_bpm+'" class="number_input fBPM"  data-validate="number"/>'+
		   '</div>';

	output += '<a class="removeInterval exerciseRemove'+exercise.editID+'" href="javascript:void(0)" onClick="removeInterval('+panel_id+',this)" > '+dict["Removeinterval"]+' </a>';

	return output;
}

function removeInterval(panel_id,evento){
		if($('.exercise_field_panel_'+panel_id).length == 1){

			$(evento).remove();
		}
		$(".exercise_field_panel_"+panel_id+":last-of-type").remove();
}

function removeLastSetToEdit(panel_id){
	$("#more_option_container_"+panel_id+" ul li:last-child").remove();
}