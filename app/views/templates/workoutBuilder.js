//
function tmpl_addWorkoutPanel(panel_count){
	return '<div class="workout_builder_panel last_builder_panel" id="workout_builder_panel_'+panel_count+'">'+
				'<div class="panel_number">'+

				'<div class="panel_overlay_step4" id="movePanel" onclick="">'+
                		'<div class="panel_overlay_arrows">'+

                			'<img class="overlay_upArrow" src="/img/move_up_button_.png" onClick="moveGroup(\'Up\','+panel_count+')">'+
                  			'<img class="overlay_downArrow" src="/img/move_down_button_.png" onClick="moveGroup(\'Down\','+panel_count+')">'+

                		'</div>'+
                	'</div>'+
                	'<div class="panel_index">'+''+'</div></div>'+

				'<div class="workout_drop_area exercise_list_small">'+
				  '<ul id="workout_builder_'+panel_count+'" builder-number="'+panel_count+'">'+
				    '<li class="droppable"><div class="add_workout_tile plus_button">+</div></li>'+
				  '</ul>'+
				'</div>'+
				'<div class="edit_type_switch">'+

					'<div class="toggle_holder">'+
						'<div class="circuit_on_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOn"]+'</div><div class="circuit_off_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOff"]+'</div>'+
						'<input type="checkbox" id="edit_switch_circuit_'+panel_count+'" name="edit_switch_circuit_'+panel_count+'" class="switch" onclick="createCircuit('+panel_count+')"/>'+
						'<label for="edit_switch_circuit_'+panel_count+'"></label>'+

					'</div>'+
  
				  '<div id="circuit_input_'+panel_count+'" class="circuit_input">'+  
				    '<input value="'+USER_DEFAULT_ROUNDS+'" type="text" onBlur="setDefault(\'USER_DEFAULT_ROUNDS\',this.value,\'circuit_round\')"  type="text" placeholder="# of rounds" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rounds" id="circuit_'+panel_count+'_rounds"  class="circuit_round"  data-validate="rounds"/>'+
				    '<input value="'+USER_DEFAULT_ROUNDS_REST+'" onBlur="setDefault(\'USER_DEFAULT_ROUNDS_REST\',this.value,\'circuit_rest\')" type="text" placeholder="rest time/sec" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rest" id="circuit_'+panel_count+'_rest" class="circuit_rest no_margin_right_fix"  data-validate="rest"/>'+
				  '</div>'+
				'</div>'+
			'</div>';
}

function tmpl_addWorkoutPanelWithExercises(panel_count,exercisesArray){
	var html = '';
	//alert(1);
	if(exercisesArray.length > 1){
		html += '<div class="workout_builder_panel last_builder_panel" id="workout_builder_panel_'+panel_count+'">'+
				'<div class="panel_number">'+
				'<div class="panel_overlay_step4" id="movePanel" onclick="">'+
                		'<div class="panel_overlay_arrows">'+

                			'<img class="overlay_upArrow" src="/img/move_up_button_.png" onClick="moveGroup(\'Up\','+panel_count+')">'+
                  			'<img class="overlay_downArrow" src="/img/move_down_button_.png" onClick="moveGroup(\'Down\','+panel_count+')">'+

                		'</div>'+
                	'</div>'+
                	'<div class="panel_index">'+''+'</div></div>'+
				'<div class="workout_drop_area exercise_list_small">'+
				  '<ul id="workout_builder_'+panel_count+'" builder-number="'+panel_count+'" class="circuitOn_workout_builder_panel">';
				  	for(x = 0; x < exercisesArray.length; x++){
				      html += '<li class="droppable ui-droppable change_droppable" exercise-id="editable_'+exercisesArray[x].elementID+'" edit-id="'+exercisesArray[x].elementID+'_'+panel_count+'_'+x+'" builder-number="'+panel_count+'" exercise-index="'+x+'">';
				      html += '<div class="exercisesimages" drag-count="1"><div class="exercise_state unselect_exercise" style="opacity: 1;" onclick="removeExercise(event,&quot;editable_'+exercisesArray[x].elementID+'&quot;)">+</div><span class="small">'+exercisesArray[x].name+'</span>  <img onerror="imgError(this);" alt="" src="/'+exercisesArray[x].thumb+'"></div>';
				      html += "</li>";
				  }
				  html += '</ul>'+
				'</div>'+
				'<div class="edit_type_switch">'+

					'<div class="toggle_holder">'+
						'<div class="circuit_on_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOn"]+'</div><div class="circuit_off_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOff"]+'</div>'+
						'<input type="checkbox" id="edit_switch_circuit_'+panel_count+'" name="edit_switch_circuit_'+panel_count+'" class="switch" onclick="createCircuit('+panel_count+')"/>'+
						'<label for="edit_switch_circuit_'+panel_count+'"></label>'+

					'</div>'+
  
				  '<div id="circuit_input_'+panel_count+'" class="circuit_input">'+  
				    '<input value="'+USER_DEFAULT_ROUNDS+'" type="text" onBlur="setDefault(\''+USER_DEFAULT_ROUNDS+'\',this.value,\'circuit_round\')" placeholder="# of rounds" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rounds" id="circuit_'+panel_count+'_rounds"  class="circuit_round"  data-validate="number"/>'+
				    '<input value="'+USER_DEFAULT_ROUNDS_REST+'" onBlur="setDefault(\''+USER_DEFAULT_ROUNDS_REST+'\',this.value,\'circuit_rest\')" type="text" placeholder="rest time/sec" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rest" id="circuit_'+panel_count+'_rest" class="circuit_rest no_margin_right_fix"  data-validate="number"/>'+
				  '</div>'+
				'</div>'+
			'</div>';
	} else if(exercisesArray.length == 1) {
		html += '<div class="workout_builder_panel last_builder_panel" id="workout_builder_panel_'+panel_count+'">'+
				'<div class="panel_number">'+
				'<div class="panel_overlay_step4" id="movePanel" onclick="">'+
                		'<div class="panel_overlay_arrows">'+

                			'<img class="overlay_upArrow" src="/img/move_up_button_.png" onClick="moveGroup(\'Up\','+panel_count+')">'+
                  			'<img class="overlay_downArrow" src="/img/move_down_button_.png" onClick="moveGroup(\'Down\','+panel_count+')">'+

                		'</div>'+
                	'</div>'+
                	'<div class="panel_index">'+''+'</div></div>'+
				'<div class="workout_drop_area exercise_list_small">'+
				  '<ul id="workout_builder_'+panel_count+'" builder-number="'+panel_count+'">';
				  	for(x = 0; x < exercisesArray.length; x++){
				      html += '<li class="droppable ui-droppable change_droppable" exercise-id="editable_'+exercisesArray[x].elementID+'" edit-id="'+exercisesArray[x].elementID+'_'+panel_count+'_'+x+'" builder-number="'+panel_count+'" exercise-index="'+x+'">';
				      html += '<div class="exercisesimages" drag-count="1"><div class="exercise_state unselect_exercise" style="opacity: 1;" onclick="removeExercise(event,&quot;editable_'+exercisesArray[x].elementID+'&quot;)">+</div><span class="small">'+exercisesArray[x].name+'</span>  <img onerror="imgError(this);" alt="'+exercisesArray[x].name+'" src="/'+exercisesArray[x].thumb+'"></div>';
				      html += "</li>";
				  }
				  html +=  '</ul>'+
				'</div>'+
				'<div class="edit_type_switch">'+

					'<div class="toggle_holder">'+
						'<div class="circuit_on_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOn"]+'</div><div class="circuit_off_label" onclick="createCircuit('+panel_count+')">'+dict["CircuitOff"]+'</div>'+
						'<input type="checkbox" id="edit_switch_circuit_'+panel_count+'" name="edit_switch_circuit_'+panel_count+'" class="switch" onclick="createCircuit('+panel_count+')"/>'+
						'<label for="edit_switch_circuit_'+panel_count+'"></label>'+

					'</div>'+
  
				  '<div id="circuit_input_'+panel_count+'" class="circuit_input">'+  
				    '<input value="'+USER_DEFAULT_ROUNDS+'" type="text" onBlur="setDefault(\''+USER_DEFAULT_ROUNDS+'\',this.value,\'circuit_round\')" placeholder="# of rounds" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rounds" id="circuit_'+panel_count+'_rounds"  class="circuit_round"  data-validate="number"/>'+
				    '<input value="'+USER_DEFAULT_ROUNDS_REST+'" onBlur="setDefault(\''+USER_DEFAULT_ROUNDS_REST+'\',this.value,\'circuit_rest\')" type="text" placeholder="rest time/sec" panel-id="'+panel_count+'" name="circuit_'+panel_count+'_rest" id="circuit_'+panel_count+'_rest" class="circuit_rest no_margin_right_fix"  data-validate="number"/>'+
				  '</div>'+
				'</div>'+
			'</div>';
	}
	return html;
	
}

//'<a href="javascript:void(0)" class="edit_switch" id="edit_switch_circuit_'+panel_count+'" onclick="createCircuit('+panel_count+')">Create Circuit</a>'+