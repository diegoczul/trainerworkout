var preload = 0;
function displayData(diva,data){
	//$(diva).html("");
	$(diva).html(data);
}


function w_workouts(items){
	
	$.post("/w/w_workouts.php",{numero: items},function (data){ displayData("#w_workouts",data); });
}

function w_workouts(items,el){
	preLoad = showLoadWithElement(el, 0, 'center');
	$.post("/w/w_workouts.php",{numero: items},function (data){ hideLoadWithElement(preLoad); displayData("#w_workouts",data); });
}

function w_trainerworkouts(items){
	$.post("/w/w_trainerworkouts.php",{numero: items},function (data){ displayData("#w_trainerworkouts",data); });
}

function w_exercises(items){
	$.post("/w/w_exercises.php",{numero: items},function (data){ displayData("#w_exercises",data); });
}

function w_trendingworkouts(items){
	$.post("/w/w_trendingworkouts.php",{numero: items},function (data){ displayData("#w_trendingworkouts",data); });
}

function w_marketworkouts(items){
	$.post("/w/w_marketworkouts.php",{numero: items},function (data){ displayData("#w_marketworkouts",data); });
}

function w_marketworkouts(items, searche){
	$.post("/w/w_marketworkouts.php",{numero: items, search:searche},function (data){ displayData("#w_marketworkouts",data); });
}

function w_weights(items){
	$.post("/w/w_weights.php",{numero: items},function (data){ displayData("#w_weights",data); });
}

function w_weights(items,size){
	$.post("/w/w_weights.php",{numero: items, size: size},function (data){ displayData("#w_weights",data); });
}

function w_addweights(el){
	
	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addweights").slideToggle();
}


function w_bodyfull(items){
	$.post("/w/w_body.php",{dated: items},function (data){ displayData("#w_bodyfull",data); });
}
function w_body(items,divid){
	$.post("/w/w_body.php",{dated: items},function (data){ displayData("#"+divid,data); });
	$.post("/w/w_body.php",{dated: items},function (data){ displayData("#w_body",data); });
	$.post("/w/w_body.php",{dated: items},function (data){ displayData("#w_bodyfull",data); });
}


function w_body_full(items,divid){
	$.post("/w/w_body_full.php",{dated: items},function (data){ displayData("#"+divid,data); });
	$.post("/w/w_body_full.php",{dated: items},function (data){ displayData("#w_body",data); });
	$.post("/w/w_body_full.php",{dated: items},function (data){ displayData("#w_bodyfull",data); });
}

function w_clientmeasurment(items,id){
	$.post("/w/w_client_body.php",{dated: items ,id: id},function (data){ displayData("#w_trainerclientaddmeasurements",data); });
}
function w_clientmeasurment(items,divid,id){
	$.post("/w/w_client_body.php",{dated: items,id : id},function (data){ displayData("#"+divid,data); });
}
function w_addmeasurements(el){

	if (el !== undefined )  if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addmeasurements").slideToggle();
}


function w_measurements_full(items){
	$.post("/w/w_measurements_full.php",{numero: items},function (data){ displayData("#w_measurements",data); });
}

function w_measurements(items,client){
	if(client === undefined) $.post("/w/w_measurements.php",{numero: items},function (data){ displayData("#w_measurements",data); });
	$.post("/w/w_measurements.php",{numero: items,client:client},function (data){ displayData("#w_measurements",data); });
}


function w_objectives(items){
	$.post("/w/w_objectives.php",{numero: items},function (data){ displayData("#w_objectives",data); });
}


function w_addobjectives(el){
	
	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addobjectives").slideToggle();
}

function w_pictures(items){
	$.post("/w/w_pictures.php",{numero: items},function (data){ displayData("#w_pictures",data); });
}

function w_pictures(items,size){
	$.post("/w/w_pictures.php",{numero: items, size: size},function (data){ displayData("#w_pictures",data); });
}
function w_trainerpictures(items){
	$.post("/w/w_trainerpictures.php",{numero: items},function (data){ displayData("#w_pictures",data); });
}
function w_trainerpictures(items,size){
	$.post("/w/w_trainerpictures.php",{numero: items, size: size},function (data){ displayData("#w_pictures",data); });
}
function w_addpictures(){
	$("#w_addpictures").slideToggle();
}


function w_friends(items){
	$.post("/w/w_friends.php",{numero: items},function (data){ displayData("#w_friends",data); });
}

function w_friends(items,searche){
	$.post("/w/w_friends.php",{numero: items, search:searche},function (data){ displayData("#w_friends",data); });
}

function w_addappointments(el){
	
	if (el !== undefined )  if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addappointments").slideToggle();
}


function w_appointments(items){
	$.post("/w/w_appointments.php",{numero: items},function (data){ displayData("#w_appointments",data); });
}

function w_addfriends(el){
	
	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addfriends").slideToggle();
}

function w_workoutmarket(items){
	$.post("/w/w_workoutmarket.php",{numero: items},function (data){ displayData("#w_workoutmarket",data); });
}

function w_workoutmarket_2(items){
	$.post("/w/w_workoutmarket_2.php",{numero: items},function (data){ displayData("#w_workoutmarket",data); });
}

function w_clients(items){

	$.post("/w/w_clients.php",{numero: items},function (data){ displayData("#w_clients",data); });
}
function w_addclients(el){

	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addclients").slideToggle();
}

function w_workoutshare(id){		
	$("#w_workoutshare_"+id).slideToggle();
	$("#hide_"+id).addClass("hide");
	
}

function w_workoutpublish(id){	
	$("#w_workoutpublish_"+id).slideToggle();
	$("#hide_"+id).addClass("hide");
}

function w_workoutShareCancel(id){	
	$("#w_workoutshare_"+id).slideToggle();
	$("#hide_"+id).addClass("show");
}

function w_workoutPublishCancel(id){	
	$("#w_workoutpublish_"+id).slideToggle();
        $("#hide_"+id).removeClass("hide");
	$("#hide_"+id).addClass("show");
}


function w_trainertrendingworkouts(items){
	$.post("/w/w_trainertrendingworkouts.php",{numero: items},function (data){ displayData("#w_trainertrendingworkouts",data); });
}

function w_trainerPworkouts(items){
	$.post("/w/w_trainerPworkouts.php",{numero: items},function (data){ displayData("#w_trainerPworkouts",data); });
}
function w_reminder(items){
	 $("#w_addreminder").slideUp();
	$.post("/w/w_reminder.php",{numero: items},function (data){ displayData("#w_reminder",data); });
}


function w_appointments(items){
	$("#w_addappointments").slideUp();
	$.post("/w/w_appointments.php",{numero: items},function (data){ displayData("#w_appointments",data); });
}

function w_clientreminder(items,id){

	$.post("/w/w_clienttaskreminderlist.php",{total: 'total' , id : id},function (data){ displayData("#w_clienttaskreminderlist",data); });
}
function w_addtask(el){
	
		if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
        $("#w_reminder").show();
        $("#w_remider_list").hide();
	$("#w_addtask").slideToggle();
        $("#w_addreminder").hide();
        $('#dropdownmenu').removeClass('active');
        $('.menu-link').removeClass('active');
}
function w_addsession(el){

	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addsession").slideToggle();
        $(".blocklement").hide();
}
function w_addsession_close(){

	
	$("#w_addsession").hide();
        $(".blocklement").show();
}
function w_session_list(items){

	$.post("/w/w_listsession.php",{total: 'total'},function (data){ displayData("#w_listsession",data); });
}
function w_addtrainerworkouts(el){

	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	$("#w_addtrainerworkouts").slideToggle();
}
function w_addreminder(el){
	
		if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
        $("#w_reminder").show();
        $("#w_remider_list").hide();
          
        $("#w_addtask").hide();
       
	$("#w_addreminder").slideToggle();

        $('#dropdownmenu').removeClass('active');
        $('.menu-link').removeClass('active');
}
function w_reminder_list(){
        $("#w_addtask").hide();
         $("#w_addreminder").hide();
        $("#w_reminder").hide();
	$("#w_remider_list").show();
}



function w_more_reminder(items){

	$.post("/w/w_reminderlist.php",{total: 'total'},function (data){ displayData("#w_remider_list",data); });
}
function w_trainerweights(items){
	$.post("/w/w_trainerweights.php",{numero: items},function (data){ displayData("#w_weights",data); });
}
function w_trainerweights(items,size){
	$.post("/w/w_trainerweights.php",{numero: items, size: size},function (data){ displayData("#w_weights",data); });
}
function w_trainerobjectives(items){
	$.post("/w/w_trainerobjectives.php",{numero: items},function (data){ displayData("#w_objectives",data); });
}
function w_trainermeasurements(items){
	$.post("/w/w_trainermeasurements.php",{numero: items},function (data){ displayData("#w_measurements",data); });
}
function w_trainerfriendobjectives(items,id){
	$.post("/w/w_trainerfriendobjectives.php",{numero: items , id : id},function (data){ displayData("#w_objectives",data); });
}
function w_trainerfriendweights(items,id){
	$.post("/w/w_trainerfriendweights.php",{numero: items , id: id},function (data){ displayData("#w_weights",data); });
}
function w_trainerfriendweights(items,id,size){
	$.post("/w/w_trainerfriendweights.php",{numero: items , id: id ,size: size},function (data){ displayData("#w_weights",data); });
}
function w_trainerfriendbody(items,id){
	$.post("/w/w_trainerfriendbody.php",{dated: items , id:id},function (data){ displayData("#w_body",data); });
}
function w_trainerfriendbody(items,id,divid){
	$.post("/w/w_trainerfriendbody.php",{dated: items , id: id},function (data){ displayData("#"+divid,data); });
}
function w_trainerfriendpictures(items,id){
	$.post("/w/w_trainerfriendpictures.php",{numero: items ,id: id},function (data){ displayData("#w_pictures",data); });
}
function w_trainerfriendpictures(items,id,size){
	$.post("/w/w_trainerfriendpictures.php",{numero: items, size: size ,id:id},function (data){ displayData("#w_pictures",data); });
}
function w_trainersaleworkouts(items){

	$.post("/w/w_tarinerworkoutsale.php",{numero: items},function (data){ displayData("#w_saleworkout",data); });
}
function w_trainerfriendsaleworkouts(items, id){

	$.post("/w/w_tarinerfriendworkoutsale.php",{numero: items , id: id},function (data){ displayData("#w_saleworkout",data); });
}

function w_traineractionfeed(items){
	$.post("/w/w_traineractionfeed.php",{numero: items},function (data){ displayData("#w_traineractionfeed",data); });
}


function w_clienttrainerweights(items){
	$.post("/w/w_clienttrainerweights.php",{numero: items},function (data){ displayData("#w_clienttrainerweights",data); });
}

function w_clienttraineraddweights(){
	$("#w_clienttraineraddweights").slideToggle();
}

function w_clienttraineraddreminder(){
	$("#w_clienttraineraddreminder").slideToggle();
        $("#w_clienttraineraddtask").hide();
}

function w_clienttraineraddtask(){
	$("#w_clienttraineraddtask").slideToggle();
        $("#w_clienttraineraddreminder").hide();
}

function w_trainerclientaddmeasurements(){
	$("#w_trainerclientaddmeasurements").slideToggle();
}
function w_trainerclientfeed(items){
	$.post("/w/w_trainerclientfeed.php",{numero: items},function (data){ displayData("#w_clientsfeed",data); });
}
function w_trainerexercises(items){
	$.post("/w/w_trainerexercises.php",{numero: items},function (data){ displayData("#w_exercises",data); });
}
function w_trainertrendingmarketworkouts(items){
	$.post("/w/w_trainertrendingmarketworkouts.php",{numero: items},function (data){ displayData("#w_workout_on_market",data); });
}
function w_trainerprofilemarketworkouts(items,id){
	$.post("/w/w_trainerprofilemarketworkouts.php",{numero: items, id : id},function (data){ displayData("#w_p_workout_on_market",data); });
}
function w_addotestimonial(){
	$("#w_addotrainertestimonial").slideToggle();
}
function w_trianercustomertestimonial(items,id){
	$.post("/w/w_customertestimonials.php",{numero: items, id : id},function (data){ displayData("#w_customer_testimonial",data); });
}
function w_clienttrainerworkouts(items,id){
	$.post("/w/w_clienttrainerworkouts.php",{numero: items , id:id},function (data){ displayData("#w_clienttrainerworkouts",data); });
}
function w_add_video_word(){
	$("#w_add_video_word").slideToggle();
     
}
function w_trainertestimonial(items){
	$.post("/w/w_trainertestimonials.php",{numero: items},function (data){ displayData("#w_trainer_testimonial",data); });
}
function w_add_biography(el){
	
	if (el !== undefined ) if( $(el.closest(".widgets").find(".emptyMessage")).length ) $(el.closest(".widgets").find(".emptyMessage")).hide() ;
	
	$("#w_add_bio").slideToggle();
     
}
