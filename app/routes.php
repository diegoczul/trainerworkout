<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
Route::get(Lang::get('routes./', function()
{
return View::make('hello');
});
 */

// Pay Page
Route::get(Lang::get('routes./payment/')."{package}", array('uses' => "OrdersController@indexPaypage"));
Route::post(Lang::get('routes./payment/')."{package}", array('uses' => "OrdersController@processPaymentNoLogin"));
Route::get(Lang::get('routes./payment'), array('uses' => "OrdersController@indexPaypage"));
Route::post(Lang::get('routes./payment'), array('uses' => "OrdersController@processPaymentNoLogin"));
Route::get(Lang::get('routes./thankyou'), array('as'=>'thankyouPayment','uses' => "OrdersController@thankyou"));

Route::get("/user/confirmation/{param1}", array('as'=>'','uses' => "UsersController@confirmEmail"));

Route::get('/phpini', array(function () { return View::make('phpini'); }));


//|--------------------------------------------------------------------------
//| GENERAL ROUTES
//|--------------------------------------------------------------------------

//LOGIN VIEW
Route::get(Lang::get('routes./login'), array("as"=>"login","uses"=>function(){ return View::make(Helper::translateOverride('login')); }));
Route::get(Lang::get('routes./logout'), array('uses' => "UsersController@logout"));
Route::post(Lang::get('routes./login'), array('uses' => "UsersController@login"));
Route::post(Lang::get('routes./registerNewsletter'), array('uses' => "UsersController@registerNewsletter"));
Route::get(Lang::get('routes./login/facebook'), array('uses' => "UsersController@loginFacebook"));
Route::get(Lang::get('routes./login/trainee/facebook')."/{param1}", array('uses' => "UsersController@loginTraineeFacebook"));
Route::get(Lang::get('routes./login/trainee/facebook'), array('uses' => "UsersController@loginTraineeFacebook"));
Route::get(Lang::get('routes./password/reset'), array( 'uses' => 'RemindersController@getRemind', 'as' => 'password.remind'));
Route::post(Lang::get('routes./password/reset'), array( 'uses' => 'RemindersController@postRemind', 'as' => 'password.request'));
Route::get(Lang::get('routes./password/reset/')."{token}", array( 'uses' => 'RemindersController@getReset', 'as' => 'password.reset'));
Route::post(Lang::get('routes.password/reset/')."{token}", array( 'uses' => 'RemindersController@postReset', 'as' => 'password.update'));


//CLIENTS
Route::post(Lang::get('routes./widgets/clients'), array('before' => 'auth','uses' => "ClientsController@index"));
Route::post(Lang::get('routes./widgets/clients/full'), array('before' => 'auth','uses' => "ClientsController@indexFull"));
Route::get(Lang::get('routes./widgets/clients'), array('before' => 'auth','uses' => "ClientsController@index"));
Route::get(Lang::get('routes./widgets/clients/')."{param}", array('before' => 'auth','uses' => "ClientsController@show"));
Route::post(Lang::get('routes./widgets/clients/addEdit/'), array('before' => 'auth','uses' => "ClientsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/clients/')."{param}", array('before' => 'auth','uses' => "ClientsController@destroy"));
Route::post(Lang::get('routes./Trainer/addClient'), array('before' => 'auth','as'=>"ProfileTrainer",'uses' => "ClientsController@addClient"));
Route::post(Lang::get('routes./Trainer/addClientWithId'), array('before' => 'auth','as'=>"ProfileTrainer",'uses' => "ClientsController@addClientWithId"));
//NO NEED FOR AUTHENTICATION
Route::get(Lang::get('routes./Clients/Invitation/')."{invite}", array('as'=>"ProfileTrainer",'uses' => "ClientsController@confirmClientByInvitation"));
Route::get(Lang::get('routes./Client/')."{id}/{username}", array('before' => 'auth','as'=>"Profile",'uses' => "ClientsController@clientProfile"));
Route::get(Lang::get('routes./Client/')."{id}", array('before' => 'auth','as'=>"Profile",'uses' => "ClientsController@clientProfile"));
Route::get(Lang::get('routes./Trainer/Clients'), array('as'=>"TrainerClients",'before' => 'auth','uses' => "ClientsController@showClients"));

Route::post(Lang::get('routes./Clients/AddClient'), array('before' => 'auth','uses' => "ClientsController@addClientTrainer"));
Route::post(Lang::get('routes./Clients/ModifyClient'), array('before' => 'auth','uses' => "ClientsController@modifyClient"));

Route::post(Lang::get('routes./Clients/subscribe/toggle'), array('before' => 'auth','uses' => "ClientsController@subscribeClient"));

//|--------------------------------------------------------------------------
//| REPORTS
//|--------------------------------------------------------------------------

//workoutsPerformanceClients

Route::post("/reports/workoutsPerformance", array('before' => 'auth','uses' => "WorkoutsperformanceController@workoutsPerformance"));
Route::get("/reports/workoutsPerformanceDetail", array('before' => 'auth','uses' => "WorkoutsperformanceController@workoutsPerformanceDetail"));


//|--------------------------------------------------------------------------
//| WIDGETS
//|--------------------------------------------------------------------------

//WORKOUTS

Route::post(Lang::get('routes./widgets/workouts/archive/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@archiveWorkout"));
Route::post(Lang::get('routes./widgets/workouts/unarchive/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@unarchiveWorkout"));
Route::get(Lang::get('routes./widgets/workouts/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@show"));
Route::post(Lang::get('routes./widgets/workouts/addEdit/'), array('before' => 'auth','uses' => "WorkoutsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/workouts/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@destroy"));
Route::post(Lang::get('routes./widgets/workoutsTrainer'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutTrainer"));
Route::post(Lang::get('routes./widgets/workoutsClient'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutsClient"));
Route::post(Lang::get('routes./widgets/workoutsLibrary'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutsLibrary"));
Route::post(Lang::get('routes./widgets/workoutsTrainer/full'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutTrainerFull"));



Route::get(Lang::get('routes./widgets/workoutsTrainee/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@show"));
Route::post(Lang::get('routes./widgets/workoutsTrainee/addEdit/'), array('before' => 'auth','uses' => "WorkoutsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/workoutsTrainee/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@destroy"));
Route::post(Lang::get('routes./widgets/workoutsTrainee'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutTrainee"));
Route::post(Lang::get('routes./widgets/workoutsTrainee/full'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutTraineeFull"));

// WORKOUT MARKET
Route::get(Lang::get('routes./WorkoutMarket'), array('before' => 'auth','uses' => "WorkoutsController@indexMarket"));
Route::get(Lang::get('routes./widgets/workoutMarket/')."{param}", array('before' => 'auth','uses' => "WorkoutsController@showWorkoutMarket"));
Route::post(Lang::get('routes./widgets/workoutMarket'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutMarket"));
Route::post(Lang::get('routes./widgets/workoutMarket/full'), array('before' => 'auth','uses' => "WorkoutsController@indexWorkoutMarketFull"));
Route::get(Lang::get('routes./Workout/Preview/')."{workoutid}/{workoutName}/{workoutAuthor}", array('uses' => "WorkoutsController@previewWorkout"));
Route::get(Lang::get('routes./Workout/Preview/')."{workoutid}/{workoutName}", array('uses' => "WorkoutsController@previewWorkout"));
Route::get(Lang::get('routes./Workout/Preview/')."{workoutid}", array('uses' => "WorkoutsController@previewWorkout"));
Route::post(Lang::get('routes./Workouts/Search'), array('before' => 'auth','uses' => "WorkoutsController@searchWorkout"));

Route::get(Lang::get('routes./Workouts/Client/')."{param1}/{param2}", array('before' => 'auth','uses' => "WorkoutsController@clientWorkouts"));
Route::get(Lang::get('routes./Workouts/Client/')."{param1}//", array('before' => 'auth','uses' => "WorkoutsController@clientWorkouts"));



Route::get("/Client/editWorkout/{param1}/{param2}", array('before' => 'auth','uses' => "WorkoutsController@assignWorkoutToClientEdit"));
Route::get("/Client/AssignWorkout/{param1}/{param2}", array('before' => 'auth','uses' => "WorkoutsController@assignWorkoutToClient"));



//WORD
Route::post(Lang::get('routes/widgets/videoWord'), array('before' => 'auth','uses' => "UsersController@indexVideoWord"));
Route::post(Lang::get('routes./widgets/videoWord/full'), array('before' => 'auth','uses' => "UsersController@indexVideoWordFull"));
Route::get(Lang::get('routes./widgets/videoWord'), array('before' => 'auth','uses' => "UsersController@index"));
Route::get(Lang::get('routes./widgets/videoWord/')."{param}", array('before' => 'auth','uses' => "UsersController@show"));
Route::post(Lang::get('routes./widgets/videoWord/addEdit/'), array('before' => 'auth','uses' => "UsersController@AddEditVideoWord"));



//Reminders and Tasks
Route::post(Lang::get('routes./widgets/tasks'), array('before' => 'auth','uses' => "TasksController@index"));
Route::post(Lang::get('routes./widgets/tasks/completeTask'), array('before' => 'auth','uses' => "TasksController@completeTask"));
Route::get(Lang::get('routes./widgets/tasks'), array('before' => 'auth','uses' => "TasksController@index"));
Route::get(Lang::get('routes./widgets/tasks/')."{param}", array('before' => 'auth','uses' => "TasksController@show"));
Route::post(Lang::get('routes./widgets/tasks/addEdit/'), array('before' => 'auth','uses' => "TasksController@AddEdit"));
Route::delete(Lang::get('routes./widgets/tasks/')."{param}", array('before' => 'auth','uses' => "TasksController@destroy"));


//WorkoutSales
Route::post(Lang::get('routes./widgets/workoutSales'), array('before' => 'auth','uses' => "WorkoutsController@workoutSales"));


//APPOINTMENTS
Route::post(Lang::get('routes./widgets/appointments'), array('before' => 'auth','uses' => "AppointmentsController@index"));
Route::post(Lang::get('routes./widgets/appointments/full'), array('before' => 'auth','uses' => "AppointmentsController@indexFull"));
Route::get(Lang::get('routes./widgets/appointments'), array('before' => 'auth','uses' => "AppointmentsController@index"));
Route::get(Lang::get('routes./widgets/appointments/')."{param}", array('before' => 'auth','uses' => "AppointmentsController@show"));
Route::post(Lang::get('routes./widgets/appointments/addEdit/'), array('before' => 'auth','uses' => "AppointmentsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/appointments/destroy'), array('before' => 'auth','uses' => "AppointmentsController@destroy"));

//AVAILABILITIES
Route::get(Lang::get('routes./widgets/availabilities/addEntry/')."{start}/{end}", array('before' => 'auth','uses' => "AvailabilitiesController@addEntry"));
Route::post(Lang::get('routes./widgets/availabilities/addEdit/'), array('before' => 'auth','uses' => "AvailabilitiesController@AddEdit"));
Route::post(Lang::get('routes./widgets/availabilities/getCalendar'), array('before' => 'auth','uses' => "AvailabilitiesController@getCalendar"));
Route::post(Lang::get('routes./widgets/availabilities/updateEvent'), array('before' => 'auth','uses' => "AvailabilitiesController@updateEvent"));

//CALENDAR
Route::post(Lang::get('routes./widgets/calendar'), array('before' => 'auth','uses' => "CalendarController@index"));
Route::post(Lang::get('routes./widgets/calendar/full'), array('before' => 'auth','uses' => "CalendarController@indexFull"));
Route::get(Lang::get('routes./widgets/calendar'), array('before' => 'auth','uses' => "CalendarController@index"));
Route::get(Lang::get('routes./widgets/calendar/')."{param}", array('before' => 'auth','uses' => "CalendarController@show"));
Route::post(Lang::get('routes./widgets/calendar/addEdit/'), array('before' => 'auth','uses' => "CalendarController@AddEdit"));


//Activity Calendar
Route::post(Lang::get('routes./widgets/calendarActivity'), array('before' => 'auth','uses' => "CalendarController@index"));
Route::post(Lang::get('routes./widgets/calendarActivity/full'), array('before' => 'auth','uses' => "CalendarController@indexFull"));
Route::get(Lang::get('routes./widgets/calendarActivity'), array('before' => 'auth','uses' => "CalendarController@index"));
Route::get(Lang::get('routes./widgets/calendarActivity/')."{param}", array('before' => 'auth','uses' => "CalendarController@show"));
Route::post(Lang::get('routes./widgets/calendarActivity/addEdit/'), array('before' => 'auth','uses' => "CalendarController@AddEdit"));


//BIOGRAPHY
Route::post(Lang::get('routes./widgets/biography'), array('before' => 'auth','uses' => "UsersController@indexBio"));
Route::post(Lang::get('routes./widgets/biography/full'), array('before' => 'auth','uses' => "UsersController@indexBioFull"));
Route::get(Lang::get('routes./widgets/biography'), array('before' => 'auth','uses' => "UsersController@index"));
Route::get(Lang::get('routes./widgets/biography/')."{param}", array('before' => 'auth','uses' => "UsersController@show"));
Route::post(Lang::get('routes./widgets/biography/addEdit/'), array('before' => 'auth','uses' => "UsersController@AddEditBio"));

//TESTIMONIALS
Route::post(Lang::get('routes./widgets/testimonials'), array('before' => 'auth','uses' => "TestimonialsController@index"));
Route::post(Lang::get('routes./widgets/testimonials/full'), array('before' => 'auth','uses' => "TestimonialsController@indexFull"));
Route::post(Lang::get('routes./widgets/testimonials/status'), array('before' => 'auth','uses' => "TestimonialsController@approveTestimonial"));
Route::get(Lang::get('routes./widgets/testimonials'), array('before' => 'auth','uses' => "TestimonialsController@index"));
Route::get(Lang::get('routes./widgets/testimonials/')."{param}", array('before' => 'auth','uses' => "TestimonialsController@show"));
Route::post(Lang::get('routes./widgets/testimonials/addEdit/'), array('before' => 'auth','uses' => "TestimonialsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/testimonials/')."{param}", array('before' => 'auth','uses' => "TestimonialsController@destroy"));


//CLIENTS FEED
Route::post(Lang::get('routes./widgets/clientsFeed'), array('before' => 'auth','uses' => "FeedsController@indexClients"));
Route::post(Lang::get('routes./widgets/clientsFeed/full'), array('before' => 'auth','uses' => "FeedsController@indexClientsFull"));
Route::get(Lang::get('routes./widgets/clientsFeed/Archive/')."{param}/{param1}", array('before' => 'auth','uses' => "FeedsController@archive"));
Route::get(Lang::get('routes./widgets/clientsFeed'), array('before' => 'auth','uses' => "ClientsController@indexClient"));
Route::get(Lang::get('routes./widgets/clientsFeed/')."{param}", array('before' => 'auth','uses' => "ClientsController@showClient"));
Route::post(Lang::get('routes./widgets/clientsFeed/addEdit/'), array('before' => 'auth','uses' => "ClientsController@AddEditClient"));
Route::delete(Lang::get('routes./widgets/clientsFeed/')."{param}", array('before' => 'auth','uses' => "ClientsController@destroy"));


//CLIENT FEED
Route::post(Lang::get('routes./widgets/clientFeed'), array('before' => 'auth','uses' => "FeedsController@indexClient"));
Route::post(Lang::get('routes./widgets/clientFeed/full'), array('before' => 'auth','uses' => "FeedsController@indexClientFull"));
Route::get(Lang::get('routes./widgets/clientFeed'), array('before' => 'auth','uses' => "ClientsController@indexClient"));
Route::get(Lang::get('routes./widgets/clientFeed/')."{param}", array('before' => 'auth','uses' => "ClientsController@showClient"));
Route::get(Lang::get('/Clients/list/emails'), array('before' => 'auth','uses' => "ClientsController@showClientList"));
Route::post(Lang::get('routes./widgets/clientFeed/addEdit/'), array('before' => 'auth','uses' => "ClientsController@AddEditClient"));
Route::delete(Lang::get('routes./widgets/clientFeed/')."{param}", array('before' => 'auth','uses' => "ClientsController@destroy"));


//WEIGHT
Route::post(Lang::get('routes./widgets/weight'), array('before' => 'auth','uses' => "WeightsController@index"));
Route::post(Lang::get('routes./widgets/weight/full'), array('before' => 'auth','uses' => "WeightsController@indexFull"));
Route::get(Lang::get('routes./widgets/weight'), array('before' => 'auth','uses' => "WeightsController@index"));
Route::get(Lang::get('routes./widgets/weight/')."{param}", array('before' => 'auth','uses' => "WeightsController@show"));
Route::post(Lang::get('routes./widgets/weight/addEdit/'), array('before' => 'auth','uses' => "WeightsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/weight/')."{param}", array('before' => 'auth','uses' => "WeightsController@destroy"));


//Measurements
Route::post(Lang::get('routes./widgets/measurements'), array('before' => 'auth','uses' => "MeasurementsController@index"));
Route::post(Lang::get('routes./widgets/measurements/full'), array('before' => 'auth','uses' => "MeasurementsController@indexFull"));
Route::get(Lang::get('routes./widgets/measurements'), array('before' => 'auth','uses' => "MeasurementsController@index"));
Route::get(Lang::get('routes./widgets/measurements/')."{param}", array('before' => 'auth','uses' => "MeasurementsController@show"));
Route::post(Lang::get('routes./widgets/measurements/addEdit/'), array('before' => 'auth','uses' => "MeasurementsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/measurements/')."{param}", array('before' => 'auth','uses' => "MeasurementsController@destroy"));


//PICTURES
Route::post(Lang::get('routes./widgets/pictures'), array('before' => 'auth','uses' => "PicturesController@index"));
Route::post(Lang::get('routes./widgets/pictures/full'), array('before' => 'auth','uses' => "PicturesController@indexFull"));
Route::get(Lang::get('routes./widgets/pictures'), array('before' => 'auth','uses' => "PicturesController@index"));
Route::get(Lang::get('routes./widgets/pictures/')."{param}", array('before' => 'auth','uses' => "PicturesController@show"));
Route::post(Lang::get('routes./widgets/pictures/addEdit/'), array('before' => 'auth','uses' => "PicturesController@AddEdit"));
Route::delete(Lang::get('routes./widgets/pictures/')."{param}", array('before' => 'auth','uses' => "PicturesController@destroy"));


//OBJECTIVES
Route::post(Lang::get('routes./widgets/objectives'), array('before' => 'auth','uses' => "ObjectivesController@index"));
Route::post(Lang::get('routes./widgets/objectives/full'), array('before' => 'auth','uses' => "ObjectivesController@indexFull"));
Route::get(Lang::get('routes./widgets/objectives'), array('before' => 'auth','uses' => "ObjectivesController@index"));
Route::get(Lang::get('routes./widgets/objectives/')."{param}", array('before' => 'auth','uses' => "ObjectivesController@show"));
Route::post(Lang::get('routes./widgets/objectives/addEdit/'), array('before' => 'auth','uses' => "ObjectivesController@AddEdit"));
Route::delete(Lang::get('routes./widgets/objectives/')."{param}", array('before' => 'auth','uses' => "ObjectivesController@destroy"));

//TAGS
Route::post(Lang::get('routes./widgets/tags/removeTag'), array('before' => 'auth','uses' => "TagsController@destroyTagWorkout"));
Route::post(Lang::get('routes./widgets/tags'), array('before' => 'auth','uses' => "TagsController@index"));
Route::post(Lang::get('routes./widgets/tagsWorkout'), array('before' => 'auth','uses' => "TagsController@indexWorkout"));
Route::post(Lang::get('routes./widgets/tags/full'), array('before' => 'auth','uses' => "TagsController@indexFull"));
Route::get(Lang::get('routes./widgets/tags'), array('before' => 'auth','uses' => "TagsController@index"));
Route::get(Lang::get('routes./widgets/tags/')."{param}", array('before' => 'auth','uses' => "TagsController@show"));
Route::post(Lang::get('routes./widgets/tags/addEdit/'), array('before' => 'auth','uses' => "TagsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/tags/')."{param}", array('before' => 'auth','uses' => "TagsController@destroy"));


//Friends
Route::post(Lang::get('routes./widgets/friends'), array('before' => 'auth','uses' => "FriendsController@index"));
Route::post(Lang::get('routes./widgets/friends/full'), array('before' => 'auth','uses' => "FriendsController@indexFull"));
Route::get(Lang::get('routes./widgets/friends'), array('before' => 'auth','uses' => "FriendsController@index"));
Route::get(Lang::get('routes./widgets/friends/suggest'), array('before' => 'auth','uses' => "FriendsController@indexSuggest"));

Route::post(Lang::get('routes./widgets/friends/addEdit/'), array('before' => 'auth','uses' => "FriendsController@addFriend"));
Route::delete(Lang::get('routes./widgets/friends/')."{param}", array('before' => 'auth','uses' => "FriendsController@destroy"));


//SESSIONS
Route::post(Lang::get('routes./widgets/sessions'), array('before' => 'auth','uses' => "SessionsController@index"));
Route::post(Lang::get('routes./widgets/sessions/full'), array('before' => 'auth','uses' => "SessionsController@indexFull"));
Route::get(Lang::get('routes./widgets/sessions'), array('before' => 'auth','uses' => "SessionsController@index"));
Route::get(Lang::get('routes./widgets/sessions/')."{param}", array('before' => 'auth','uses' => "SessionsController@show"));
Route::post(Lang::get('routes./widgets/sessions/addEdit/'), array('before' => 'auth','uses' => "SessionsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/sessions/')."{param}", array('before' => 'auth','uses' => "SessionsController@destroy"));

//EXERCISES
Route::post(Lang::get('routes./widgets/exercises'), array('before' => 'auth','uses' => "ExercisesController@index"));
Route::post(Lang::get('routes./widgets/exercises/full'), array('before' => 'auth','uses' => "ExercisesController@indexFull"));
Route::get(Lang::get('routes./widgets/exercises'), array('before' => 'auth','uses' => "ExercisesController@index"));
Route::post(Lang::get('routes./widgets/exercises/addEdit'), array('before' => 'auth','uses' => "ExercisesController@AddEdit"));
Route::delete(Lang::get('routes./widgets/exercises/')."{param}", array('before' => 'auth','uses' => "ExercisesController@destroy"));

//NOTIFICATIONS
Route::post(Lang::get('routes./widgets/notifications'), array('before' => 'auth','uses' => "NotificationsController@index"));
Route::post(Lang::get('routes./widgets/notificationsRead'), array('before' => 'auth','uses' => "NotificationsController@readNotifications"));
Route::post(Lang::get('routes./widgets/notifications/full'), array('before' => 'auth','uses' => "NotificationsController@indexFull"));
Route::get(Lang::get('routes./widgets/notifications'), array('before' => 'auth','uses' => "NotificationsController@index"));
Route::get(Lang::get('routes./widgets/notifications/')."{param}", array('before' => 'auth','uses' => "NotificationsController@show"));
Route::post(Lang::get('routes./widgets/notifications/addEdit/'), array('before' => 'auth','uses' => "NotificationsController@AddEdit"));
Route::delete(Lang::get('routes./widgets/notifications/')."{param}", array('before' => 'auth','uses' => "NotificationsController@destroy"));

//Messages
Route::post(Lang::get('routes./widgets/messages'), array('before' => 'auth','uses' => "UserMessagesController@index"));
Route::post(Lang::get('routes./widgets/messages/readUserMessages'), array('before' => 'auth','uses' => "UserMessagesController@readUserMessages"));
Route::post(Lang::get('routes./widgets/messages/full'), array('before' => 'auth','uses' => "UserMessagesController@indexFull"));
Route::get(Lang::get('routes./widgets/messages'), array('before' => 'auth','uses' => "UserMessagesController@index"));
Route::get(Lang::get('routes./widgets/messages/dialog/')."{param}", array('before' => 'auth','uses' => "UserMessagesController@dialog"));
Route::post(Lang::get('routes./widgets/messages/addEdit'), array('before' => 'auth','uses' => "UserMessagesController@AddEdit"));
Route::get(Lang::get('routes./widgets/messages/')."{param}", array('before' => 'auth','uses' => "UserMessagesController@show"));
Route::delete(Lang::get('routes./widgets/messages/')."{param}", array('before' => 'auth','uses' => "UserMessagesController@destroy"));

//|--------------------------------------------------------------------------
//| TRAINER
//|--------------------------------------------------------------------------


//REPORTS

Route::get(Lang::get('routes./Trainer/Reports/WorkoutsPerformanceClients',array(),'en'), array('before' => 'auth','uses' => "WorkoutsperformanceController@workoutsPerformanceClientsIndex"));
Route::get(Lang::get('routes./Trainer/Reports/WorkoutsPerformanceClients',array(),'fr'), array('before' => 'auth','uses' => "WorkoutsperformanceController@workoutsPerformanceClientsIndex"));



//OnBoarding
Route::post(Lang::get('routes./onboarding/message/')."{param1}", array('before' => 'auth','uses' => "OnBoardingController@messageChat"));

Route::get(Lang::get('routes./Trainer/onBoarding/skipDemo'), array('before' => 'auth','uses' => "OnBoardingController@skipDemo"));
Route::get(Lang::get('routes./Trainer/onBoarding/start'), array('before' => 'auth','uses' => "OnBoardingController@start"));
Route::get(Lang::get('routes./Trainer/onBoarding/stop'), array('before' => 'auth','uses' => "OnBoardingController@skipDemo"));
Route::get(Lang::get('routes./Trainer/onBoarding/step1'), array('before' => 'auth','uses' => "OnBoardingController@step1"));
Route::get(Lang::get('routes./Trainer/onBoarding/step2'), array('before' => 'auth','uses' => "OnBoardingController@step2"));
Route::get(Lang::get('routes./Trainer/onBoarding/step21'), array('before' => 'auth','uses' => "OnBoardingController@step21"));
Route::get(Lang::get('routes./Trainer/onBoarding/step22'), array('before' => 'auth','uses' => "OnBoardingController@step22"));
Route::get(Lang::get('routes./Trainer/onBoarding/step23'), array('before' => 'auth','uses' => "OnBoardingController@step23"));
Route::get(Lang::get('routes./Trainer/onBoarding/step3'), array('before' => 'auth','uses' => "OnBoardingController@step3"));
Route::get(Lang::get('routes./Trainer/onBoarding/step4'), array('before' => 'auth','uses' => "OnBoardingController@step4"));
Route::get(Lang::get('routes./Trainer/onBoarding/step41'), array('before' => 'auth','uses' => "OnBoardingController@step41"));
Route::get(Lang::get('routes./Trainer/onBoarding/step5'), array('before' => 'auth','uses' => "OnBoardingController@step5"));
Route::get(Lang::get('routes./Trainer/onBoarding/step6'), array('before' => 'auth','uses' => "OnBoardingController@step6"));
Route::get(Lang::get('routes./Trainer/onBoarding/step7'), array('before' => 'auth','uses' => "OnBoardingController@step7"));
Route::get(Lang::get('routes./Trainer/onBoarding/step8'), array('before' => 'auth','uses' => "OnBoardingController@step8"));


//WORKOUT
Route::get(Lang::get('routes./Trainer/CreateWorkout/')."{param}", array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutTrainer"));
Route::get(Lang::get('routes./Workouts/removeWorkout/')."{id}", array('before' => 'auth','uses' => "WorkoutsController@deleteWorkout"));
Route::get(Lang::get('routes./Trainer/CreateWorkout'), array('as'=>'trainerCreateWorkout','before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutTrainer"));
Route::post(Lang::get('routes./Trainer/autoSaveWorkout'), array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@autoSaveWorkout"));
Route::get(Lang::get('routes./Trainer/CreateWorkout2'), array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutTrainer"));
Route::post(Lang::get('routes./widgets/workouts'), array('before' => 'auth','uses' => "WorkoutsController@index"));
Route::post(Lang::get('routes./widgets/workouts_create'), array('before' => 'auth','uses' => "WorkoutsController@indexCreate"));
Route::post(Lang::get('routes./widgets/trendingWorkouts'), array('before' => 'auth','uses' => "WorkoutsController@indexTrendingWorkouts"));
Route::post(Lang::get('routes./widgets/workouts/full'), array('before' => 'auth','uses' => "WorkoutsController@indexFull"));
Route::get(Lang::get('routes./widgets/workouts'), array('before' => 'auth','uses' => "WorkoutsController@index"));
Route::get(Lang::get('routes./Trainer/Workouts')."/{userName}", array('as' => 'trainerWorkouts','before' => 'auth|userTypeChecker','uses' => "WorkoutsController@indexWorkoutsTrainer"));
Route::get(Lang::get('routes./Trainer/Workouts'), array('as' => 'trainerWorkouts2','before' => 'auth|userTypeChecker','uses' => "WorkoutsController@indexWorkoutsTrainer"));


Route::post(Lang::get('routes./Trainer/CreateWorkout'), array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutAddEditTrainer"));
Route::post(Lang::get('routes./Trainer/CreateWorkout/{param1}'), array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutAddEditTrainer"));

Route::get(Lang::get('routes./Trainer/CreateWorkout/Client/')."{param}", array('before' => 'auth|userTypeChecker','uses' => "WorkoutsController@createNewWorkoutTrainerToClient"));


//USERS

Route::get(Lang::get('routes./widgets/people/suggest'), array('before' => 'auth','uses' => "UsersController@indexSuggestPeople"));
Route::get(Lang::get('routes./widgets/people/suggestWithClient'), array('before' => 'auth','uses' => "UsersController@indexSuggestPeopleWithClients"));


//|--------------------------------------------------------------------------
//| Workout
//|--------------------------------------------------------------------------


Route::post(Lang::get('routes./Workout/Performance/discartOldPerformance'), array('uses' => "WorkoutsController@discartOldPerformance"));
Route::post(Lang::get('routes./Workout/Performance/saveProgressPerformance'), array('uses' => "WorkoutsController@saveProgressPerformance"));
Route::post(Lang::get('routes./Workout/Performance/Start'), array('uses' => "WorkoutsController@startWorkoutPerformance"));
Route::get(Lang::get('routes./Workout/Performance/showDetails')."/{param}", array('uses' => "WorkoutsperformanceController@workoutsPerformanceDetail"));
Route::post(Lang::get('routes./Workout/Performance'), array('uses' => "WorkoutsController@performWorkout"));
Route::get(Lang::get('routes./WorkoutPDF/')."{id}/{name}/{author}", array('as' => 'workout','uses' => "WorkoutsController@viewWorkoutPDF"));
Route::get(Lang::get('routes./WorkoutImage/')."{id}/{name}/{author}", array('as' => 'workout','uses' => "WorkoutsController@viewWorkoutImage"));
Route::get(Lang::get('routes./Workout/Edit/')."{id}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@editWorkout"));
Route::post(Lang::get('routes./Workout/Duplicate'), array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@duplicateWorkout"));
Route::post(Lang::get('routes./Workout/Edit/'), array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@saveEditWorkout"));
Route::post(Lang::get('routes./Workout/Duplicate/'), array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@duplicateWorkout"));
Route::post(Lang::get('routes./Workout/saveSingleSet'), array('before' => 'auth','uses' => "WorkoutsController@saveSingleSet"));
Route::post(Lang::get('routes./Workout/saveAllSets'), array('before' => 'auth','uses' => "WorkoutsController@saveAllSets"));
Route::post(Lang::get('routes./Workout/addSets'), array('before' => 'auth','uses' => "WorkoutsController@addSets"));
Route::post(Lang::get('routes./Workout/addSetsWithTable'), array('before' => 'auth','uses' => "WorkoutsController@addSetsReturnTable"));
Route::post(Lang::get('routes./Workout/saveAllAddNewSets'), array('before' => 'auth','uses' => "WorkoutsController@saveAllAddNewSets"));
Route::post(Lang::get('routes./Workout/exerciseCompleted'), array('before' => 'auth','uses' => "WorkoutsController@exerciseCompleted"));
Route::post(Lang::get('routes./Workout/workoutCompleted'), array('before' => 'auth','uses' => "WorkoutsController@workoutCompleted"));


Route::get(Lang::get('routes./Workout/ShareWorkout/')."{workout}", array('before' => 'auth','uses' => "WorkoutsController@shareWorkoutIndex"));



Route::get(Lang::get('routes./Workout/ShareWorkouts/'), array('before' => 'auth','uses' => "WorkoutsController@shareWorkoutIndex"));
Route::get(Lang::get('routes./Workout/PrintWorkout/')."{workout}", array('before' => 'auth','uses' => "WorkoutsController@PrintWorkoutPDF"));
Route::get(Lang::get('routes./Workout/PrintWorkouts/')."{workout}", array('before' => 'auth','uses' => "WorkoutsController@PrintWorkouts"));
Route::get(Lang::get('routes./Workout/PrintWorkoutInternal/')."{workout}", array('uses' => "WorkoutsController@PrintWorkout"));
Route::get(Lang::get('routes./Workout/PrintWorkoutInternal/')."{workout}"."/"."{locale}", array('uses' => "WorkoutsController@PrintWorkout"));
Route::post(Lang::get('routes./Workout/subscribe/toggle'), array('before' => 'auth','uses' => "WorkoutsController@subscribeTrainer"));

Route::post(Lang::get('routes./Workout/ShareByEmail'), array('before' => 'auth','uses' => "WorkoutsController@ShareByEmail"));
Route::post(Lang::get('routes./Workout/ShareByUser/'), array('before' => 'auth','uses' => "WorkoutsController@ShareByUser"));
Route::post(Lang::get('routes./Workout/ShareByLink/'), array('before' => 'auth','uses' => "WorkoutsController@ShareByLink"));
Route::get(Lang::get('routes./Workout/AddToMyWorkouts/')."{workout}", array('before' => 'auth','uses' => "WorkoutsController@AddToMyWorkouts"));
Route::get(Lang::get('routes./Workout/exercisePerformance/')."{workoutexercise}", array('before' => 'auth','uses' => "WorkoutsController@exercisePerformance"));
Route::post(Lang::get('routes./Workout/addCustomPicture/'), array('before' => 'auth','uses' => "WorkoutsController@addCustomPicture"));
Route::post(Lang::get('routes./Workout/unit/update'), array('before' => 'auth','uses' => "WorkoutsController@updateUnitExerciseGroup"));
Route::get(Lang::get('routes./Workout/')."{id}/{name}/{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@viewWorkout"));
Route::get(Lang::get('routes./Workout/')."{id}//{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@viewWorkoutNoName"));


Route::get("Trainee/".Lang::get('routes.Workout/')."{id}/{name}/{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@viewWorkoutTrainee"));
Route::get("Trainee/".Lang::get('routes.Workout/')."{id}//{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@viewWorkoutTrainee"));

Route::get(Lang::get('routes./editWorkout/')."{id}/{name}/{author}/{client}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@editWorkout"));
Route::get(Lang::get('routes./editWorkout/')."{id}/{name}/{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@editWorkout"));
Route::get(Lang::get('routes./editWorkout/')."{id}//{author}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@editWorkout"));
Route::get(Lang::get('routes./editWorkout/')."{id}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@editWorkout"));
Route::get(Lang::get('routes./Workouts/createUserDownload')."/{workouts}/{param1}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@createUserDownload"));
Route::get(Lang::get('routes./Workouts/createUserDownload')."/{workouts}/{param1}/{param2}", array('as' => 'workout','before' => 'auth','uses' => "WorkoutsController@createUserDownload"));
Route::get(Lang::get('routes./Workouts/addWorkoutToClient')."/{param1}/{param2}", array('before' => 'auth','uses' => "WorkoutsController@addToWorkoutClient"));


//THIS CANNOT BE TRANLSATED
Route::get("/WorkoutInternal/{id}/{locale}/{name}/{author}", array('as' => 'workout','uses' => "WorkoutsController@viewWorkoutInternal"));
Route::get("/WorkoutInternal/{id}/{locale}//{author}", array('as' => 'workout','uses' => "WorkoutsController@viewWorkoutInternal"));
Route::get("/WorkoutInternal/{id}/{locale}/{author}", array('as' => 'workout','uses' => "WorkoutsController@viewWorkoutInternal"));



//|--------------------------------------------------------------------------
//| SHARING A WORKOUT
//|--------------------------------------------------------------------------


//SHARING
Route::get(Lang::get('routes./Share/Workout/Accept/')."{link}", array('uses' => "WorkoutsController@acceptWorkoutBySharingLink"));

//BILLINGUAL ROUTES
Route::get(Lang::get('routes./Share/Workout/',array(),'fr')."{link}", array('uses' => "WorkoutsController@openWorkoutBySharingLink"));
Route::get(Lang::get('routes./Share/Workout/',array(),'en')."{link}", array('uses' => "WorkoutsController@openWorkoutBySharingLink"));


Route::post(Lang::get('routes./Share/Facebook'), array('uses' => "UsersController@shareOnFacebook"));



//INVITES


//|--------------------------------------------------------------------------
//| FRIENDS
//|--------------------------------------------------------------------------


//Friends
Route::post(Lang::get('routes./Friends/Add'), array('before' => 'auth','uses' => "FriendsController@addFriend"));
Route::post(Lang::get('routes./Friends/Search'), array('before' => 'auth','uses' => "FriendsController@searchFriend"));


Route::get(Lang::get('routes./Trainer/Friends'), array('as'=>"TrainerFriends",'before' => 'auth','uses' => "FriendsController@indexFriendsTrainer"));
Route::post(Lang::get('routes./Trainer/Friends'), array('before' => 'auth','uses' => "FriendsController@indexFullTrainer"));





//|--------------------------------------------------------------------------
//| EXERCISES
//|--------------------------------------------------------------------------

Route::get(Lang::get('routes./Trainer/Exercises'), array("as"=>'ExercisesHomeTrainer','before' => 'auth|userTypeChecker','uses' => "ExercisesController@indexExercisesTrainer"));
Route::post(Lang::get('routes./Trainer/Exercises'), array('before' => 'auth|userTypeChecker','uses' => "ExercisesController@indexFullTrainer"));


Route::post(Lang::get('routes./Exercises/switchPictures'), array('before' => 'auth','uses' => "ExercisesController@switchPictures"));

Route::post(Lang::get('routes./Exercises/ClearAttribute'), array('before' => 'auth','uses' => "ExercisesController@ClearAttribute"));

Route::post(Lang::get('routes./Exercises/Rotate/Left'), array('before' => 'auth','uses' => "ExercisesController@rotateLeft"));
Route::post(Lang::get('routes./Exercises/Rotate/Right'), array('before' => 'auth','uses' => "ExercisesController@rotateRight"));

Route::post(Lang::get('routes./Exercises/Rotate1/Left'), array('before' => 'auth','uses' => "ExercisesController@rotateLeft1"));
Route::post(Lang::get('routes./Exercises/Rotate1/Right'), array('before' => 'auth','uses' => "ExercisesController@rotateRight1"));

Route::post(Lang::get('routes./Exercises/Rotate2/Left'), array('before' => 'auth','uses' => "ExercisesController@rotateLeft2"));
Route::post(Lang::get('routes./Exercises/Rotate2/Right'), array('before' => 'auth','uses' => "ExercisesController@rotateRight2"));

Route::get(Lang::get('routes./Exercises/addExercise'), array('before' => 'auth','uses' => "ExercisesController@indexAdd"));
Route::get(Lang::get('routes./Exercises/addExerciseInWorkout'), array('before' => 'auth','uses' => "ExercisesController@indexAddInWorkout"));
Route::post(Lang::get('routes./Exercises/Search'), array('before' => 'auth','uses' => "ExercisesController@searchExercise"));
Route::post(Lang::get('routes./Exercises/AddExercise'), array('before' => 'auth','uses' => "ExercisesController@AddEdit"));
Route::post(Lang::get('routes./Exercises/AddExerciseInWorkout'), array('before' => 'auth','uses' => "ExercisesController@AddEditInWorkout"));
Route::get(Lang::get('routes./Exercise')."/{id}/{name}", array('before' => '','uses' => "ExercisesController@show"));
Route::get(Lang::get('routes./Exercise')."/{id}", array('before' => '','uses' => "ExercisesController@show"));
Route::get(Lang::get('routes./EditExercise')."/{id}", array('before' => '','uses' => "ExercisesController@editExercise"));

Route::post(Lang::get('routes./Exercises/AddToFavorite'), array('before' => '','uses' => "ExercisesController@addToFavorites"));









Route::get(Lang::get('routes.Events/eventMessageClient/')."{from}/{to}", array('uses' => "UserMessagesController@eventMessageClient"));
Route::get(Lang::get('routes.Events/test'), array('uses' => "UserMessagesController@eventTest"));



//Trainer MAIL
Route::get(Lang::get('routes.Trainer/Mail'), array('before' => 'auth|userTypeChecker','uses' => "UserMessagesController@indexMail"));
Route::get(Lang::get('routes.Trainer/ComposeMail/')."{user}", array('before' => 'auth','uses' => "UserMessagesController@composeMailToUser"));
Route::get(Lang::get('routes.Trainer/ComposeMail'), array('before' => 'auth','uses' => "UserMessagesController@composeMail"));




Route::post(Lang::get('routes./Search'), array('before' => 'auth','as'=>"",'uses' => "UsersController@globalSearch"));
Route::get(Lang::get('routes./Search'), array('before' => 'auth','as'=>"",'uses' => "UsersController@globalSearch"));





//|--------------------------------------------------------------------------
//| TRAINEE
//|--------------------------------------------------------------------------

Route::get(Lang::get('routes./Trainee/Settings'), array('before' => 'auth|userTypeChecker','as'=>"TraineeSettings",'uses' => "UsersController@indexSettings"));
Route::post(Lang::get('routes./Trainee/Settings'), array('before' => 'auth|userTypeChecker','uses' => "UsersController@settingsSave"));

Route::get(Lang::get('routes./Trainee/')."{username}".Lang::get('routes./Profile'), array('before|userTypeChecker' => 'auth','as'=>"Profile",'uses' => "UsersController@indexProfile"));


Route::get(Lang::get('routes./Trainee/SendFeedback'), array('before' => '','as'=>"",'uses' => "UsersController@sendFeedback"));
Route::get(Lang::get('routes./Trainee/ViewWorkout'), array('before' => '','as'=>"",'uses' => "UsersController@viewWorkoutTrainee"));
Route::get(Lang::get('routes./Trainee/ViewWorkout'), array('before' => '','as'=>"",'uses' => "UsersController@viewWorkoutTrainee"));
Route::get(Lang::get('routes./Trainee/Workouts'), array('before' => '','as'=>"traineeWorkouts",'uses' => "UsersController@viewWorkoutsTrainee"));



Route::get(Lang::get('routes./Trainee/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfile",'uses' => "UsersController@indexEditTrainee"));
Route::post(Lang::get('routes./Trainee/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfilePost",'uses' => "UsersController@TraineeSave"));
Route::post(Lang::get('routes./Profile/Rotate/Left'), array('before' => 'auth','uses' => "UsersController@rotateLeft"));
Route::post(Lang::get('routes./Profile/Rotate/Right'), array('before' => 'auth','uses' => "UsersController@rotateRight"));




//|--------------------------------------------------------------------------
//| TRAINER
//|--------------------------------------------------------------------------
Route::get(Lang::get('routes./Trainer/Settings'), array('before' => 'auth|userTypeChecker','as'=>"TrainerSettings",'uses' => "UsersController@indexSettingsTrainer"));
Route::get(Lang::get('routes./Trainer/Memberships'), array('before' => 'auth|userTypeChecker','as'=>"TrainerSettings",'uses' => "UsersController@indexMemberships"));
Route::post(Lang::get('routes./Trainer/EmployeeManagement/addEmployees'), array('before' => 'auth|userTypeChecker','as'=>"",'uses' => "GroupsController@addEmployees"));
Route::get(Lang::get('routes./Trainer/EmployeeManagement/resendInvite')."/{userid}", array('before' => 'auth|userTypeChecker','as'=>"",'uses' => "GroupsController@resendGroupInvitation"));
Route::get(Lang::get('routes./Trainer/EmployeeManagement/PersonifyBack'), array('before' => '','as'=>"",'uses' => "UsersController@personifyFromGroupBack"));
Route::get(Lang::get('routes./Trainer/EmployeeManagement/Personify')."/{param}", array('before' => 'auth|userTypeChecker','as'=>"",'uses' => "UsersController@personifyFromGroup"));
Route::get(Lang::get('routes./Trainer/EmployeeManagement/RemoveAccess')."/{param}", array('before' => 'auth|userTypeChecker','as'=>"",'uses' => "GroupsController@removeAccess"));
Route::post(Lang::get('routes./Trainer/EmployeeManagement/ChangeRole'), array('before' => 'auth|userTypeChecker','as'=>"",'uses' => "GroupsController@changeRole"));

Route::get(Lang::get('routes./MembershipManagement'), array('before' => 'auth','as'=>"TrainerSettings",'uses' => "MembershipsController@indexMembershipManagement"));
Route::get(Lang::get('routes./MembershipManagementOld'), array('before' => 'auth','as'=>"TrainerSettings",'uses' => "MembershipsController@indexMembershipManagementOld"));

Route::post(Lang::get('routes./Trainer/Settings'), array('before' => 'auth|userTypeChecker','uses' => "UsersController@settingsSaveTrainer"));
//Route::get(Lang::get('routes./Trainer/')."{username}".Lang::get('routes./Profile'), array('before' => 'auth|userTypeChecker','as'=>"ProfileTrainer",'uses' => "UsersController@indexProfileTrainer"));
Route::get(Lang::get('routes./Trainer/Profile'), array('before' => 'auth|userTypeChecker','as'=>"TrainerProfile",'uses' => "UsersController@indexProfileTrainer"));
Route::get(Lang::get('routes./Trainer/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfileTrainer",'uses' => "UsersController@indexEditTrainer"));
Route::post(Lang::get('routes./Trainer/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfilePostTrainer",'uses' => "UsersController@TrainerSave"));
Route::get(Lang::get('routes./Trainer/')."{userId}/{userName}", array('before' => 'auth|userTypeChecker','uses' => "UsersController@indexTrainer"));
Route::get(Lang::get('routes./Trainer/')."{username}", array('before' => 'auth|userTypeChecker','as'=>"Trainer",'uses' => "UsersController@trainerIndex"));
Route::get(Lang::get('routes./Trainer'), array('before' => 'auth|userTypeChecker','as'=>"TrainerBase",'uses' => "UsersController@trainerIndex"));



Route::get(Lang::get('routes./Trainee/Settings'), array('before' => 'auth|userTypeChecker','as'=>"TraineeSettings",'uses' => "UsersController@indexSettings"));
Route::post(Lang::get('routes./Trainee/Settings'), array('before' => 'auth|userTypeChecker','uses' => "UsersController@settingsSave"));
Route::get(Lang::get('routes./Trainee/')."{username}".Lang::get('routes./Profile'), array('before|userTypeChecker' => 'auth','as'=>"Profile",'uses' => "UsersController@indexProfile"));
Route::get(Lang::get('routes./Trainee/Profile'), array('before' => 'auth|userTypeChecker','as'=>"TraineeProfile",'uses' => "UsersController@indexProfile"));
Route::get(Lang::get('routes./Trainee/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfile",'uses' => "UsersController@indexEditTrainee"));
Route::post(Lang::get('routes./Trainee/EditProfile'), array('before' => 'auth|userTypeChecker','as'=>"EditProfilePost",'uses' => "UsersController@TraineeSave"));
Route::get(Lang::get('routes./Trainee/')."{userId}/{userName}", array('before' => 'auth|userTypeChecker','uses' => "UsersController@indexTrainee"));
Route::get(Lang::get('routes./Trainer/')."{userId}/{userName}", array('before' => 'auth|userTypeChecker','uses' => "UsersController@indexTrainer"));
Route::get(Lang::get('routes./Trainee/')."{username}", array('before' => 'auth|userTypeChecker','as'=>"Trainee",'uses' => "UsersController@index"));
Route::get(Lang::get('routes./Trainee'), array('before' => 'auth|userTypeChecker','as'=>"TraineeBase",'uses' => "UsersController@index"));



//|--------------------------------------------------------------------------
//| GYM MANAGEMENT
//|--------------------------------------------------------------------------
Route::get(Lang::get('routes./employeeManagement'), array("before"=>'',"as"=>"employeeManagement","uses"=>"GroupsController@showGroup"));




//|--------------------------------------------------------------------------
//| STORE
//|--------------------------------------------------------------------------
Route::get(Lang::get('routes./Store/Cart'), array('as'=>"cart",'uses' => "OrdersController@index"));
Route::get(Lang::get('routes./Store/removeFromCart'), array('uses' => "OrdersController@removeFromCart"));
Route::get(Lang::get('routes./Store/addToCart')."/{var1}/{var2}", array('uses' => "OrdersController@addToCart"));
Route::get(Lang::get('routes./Store/removeItem/')."{var1}", array('uses' => "OrdersController@removeFromCart"));
Route::get(Lang::get('routes./Store/Checkout'), array('as'=>"StoreCheckout",'uses' => "OrdersController@checkout"));
Route::post(Lang::get('routes./Store/ProcessPayment'), array('as'=>"checkout",'uses' => "OrdersController@processPayment"));

Route::get(Lang::get('routes./Store/CreateAccount'), array('as'=>"StoreCreateAccount",'uses' => "OrdersController@createAccount"));



//|--------------------------------------------------------------------------
//| LANGUAGES
//|--------------------------------------------------------------------------
Route::get(Lang::get('routes./lang/')."{param1}", array('uses' => "SystemController@changeLanguange"));

//|--------------------------------------------------------------------------
//| Feedback
//|--------------------------------------------------------------------------
Route::post(Lang::get('routes./Feedback'), array('uses' => "SystemController@sendFeedback"));




//|--------------------------------------------------------------------------
//| API V1.2
//|--------------------------------------------------------------------------


//API ========================================================================================================================
// ========================================================================================================================
// ========================================================================================================================

//API
Route::post('/Exercises/AddEdit', array('uses' => "ExercisesController@AddEdit"));
Route::get('/ExercisesImages/{param}', array('uses' => "ExercisesImagesController@index"));
Route::get('/Exercises/show/{search}', array('uses' => "ExercisesController@APIShow"));


Route::post('/API/Exercises/search', array('uses' => "ExercisesController@APIsearchExercise"));


//USERS
Route::post('/API/Users/Profile', array('uses' => "UsersController@APIEditProfile"));
Route::post('/API/Users/Login', array('uses' => "UsersController@APILogin"));
Route::post('/API/Users/App', array('uses' => "UsersController@APIAppSettings"));
Route::post('/API/Users/LoginAuto', array('uses' => "UsersController@APILoginAuto"));
Route::get('/API/logout', array('uses' => "UsersController@APILogout"));
Route::post('/API/logout', array('uses' => "UsersController@APILogout"));
Route::post('/API/Users/Register', array('uses' => "UsersController@APIRegistration"));

//Ojectives
Route::post('/API/Objectives/AddEdit', array('before' => 'auth','uses' => "ObjectivesController@APIAddEdit"));


//Pictures
Route::get('/API/Pictures', array('before' => 'auth','uses' => "PicturesController@APIIndex"));
Route::post('/API/Pictures', array('before' => 'auth','uses' => "PicturesController@APIIndex"));
Route::post('/API/Pictures/AddEdit', array('before' => 'auth','uses' => "PicturesController@APIAddEdit"));


//Weight
Route::post('/API/Weight/AddEdit', array('before' => 'auth','uses' => "WeightsController@APIAddEdit"));

//WORKOUTS
Route::post('/API/Workouts', array('uses' => "WorkoutsController@APIIndex"));
Route::post('/API/Workouts/saveSingleSet', array('uses' => "WorkoutsController@APIsaveSingleSet"));
Route::post('/API/Workouts/completeSet', array('uses' => "WorkoutsController@APIcompleteSet"));
Route::post('/API/Workouts/saveAllSets', array('uses' => "WorkoutsController@APIsaveAllSets"));

Route::post('/API/Workouts/APIexerciseCompleted', array('uses' => "WorkoutsController@APIexerciseCompleted"));
Route::post('/API/Workouts/workoutCompleted', array('uses' => "WorkoutsController@APIworkoutCompleted"));
Route::get('/API/Workouts', array('uses' => "WorkoutsController@APIIndex"));

// NIC CHANGES
Route::post('/API/WorkoutsBasic', array('uses' => "WorkoutsController@API_Workouts_Basic"));
Route::post('/API/WorkoutGroups', array('uses' => "WorkoutsController@API_Workout_Groups"));
Route::post('/API/ExerciseModel', array('uses' => "ExercisesController@API_Exercise_Model"));


//WORKOUTS
Route::get('/API/Workouts', array('before' => 'auth','uses' => "WorkoutsController@APIindex"));
Route::get('/API/Workouts/{param}', array('before' => 'auth','uses' => "WorkoutsController@show"));
Route::post('/API/Workouts/addEdit/', array('before' => 'auth','uses' => "WorkoutsController@APIAddEdit"));
Route::delete('/API/Workouts/{param}', array('before' => 'auth','uses' => "WorkoutsController@destroy"));


// NIC CHANGES
Route::post('/API/WorkoutsBasic', array('uses' => "WorkoutsController@API_Workouts_Basic"));
Route::post('/API/WorkoutGroups', array('uses' => "WorkoutsController@API_Workout_Groups"));
Route::post('/API/ExerciseModel', array('uses' => "ExercisesController@API_Exercise_Model"));


//USERS
Route::get('/API/Users', array('before' => 'auth','uses' => "UsersController@APIindex"));
Route::get('/API/Users/{param}', array('before' => 'auth','uses' => "UsersController@show"));
Route::post('/API/Users/addEdit/', array('before' => 'auth','uses' => "UsersController@APIAddEdit"));
Route::post('/API/Users/addEdit/', array('before' => 'auth','uses' => "UsersController@APILogin"));
Route::delete('/API/Users/{param}', array('before' => 'auth','uses' => "UsersController@destroy"));



//IOS 
Route::post('/API/IOS/CreateWorkout', array('before' => 'auth','uses' => "WorkoutsController@API_IOS_CreateWorkout"));

Route::post('/events/postEvent', array('before' => 'auth',function () { 
	return Event::fire('jsTriggeredEvent',array(Input::get("eventName"),Input::get("metas"))); 
}));

Route::get("translationtest",array(function () { return View::make("translation"); }));

//END ==================================================================================================================================





//CONTROL PANEL ========================================================================================================================

//USERS
//Route::post(Lang::get('routes./ControlPanel/Users'), array('uses' => "UsersController@controlPanelAPIList"));

Route::group(array( "before" => 'controlpanel|auth'),function(){

	Route::get('/ControlPanel/Users/loginUserAdmin/{id}', array('uses' => "UsersController@controlPanelLoginUserAdmin"));
	Route::get('/ControlPanel', array('uses' => "UsersController@_index"));


	//Users
	Route::get('ControlPanel/Users', array('before' => 'auth','uses' => "UsersController@_index"));
	Route::get('ControlPanel/Users/{user}', array('before' => 'auth','uses' => "UsersController@_show"));
	Route::delete('ControlPanel/Users/{user}', array('before' => 'auth','uses' => "UsersController@_destroy"));
	Route::post('ControlPanel/Users', array('before' => 'auth','uses' => "UsersController@_ApiList"));
	Route::post('ControlPanel/Users/AddEdit/', array('before' => 'auth','uses' => "UsersController@_AddEdit"));


	//Workouts
	Route::get('ControlPanel/Workouts', array('before' => 'auth','uses' => "WorkoutsController@_index"));
	Route::get('ControlPanel/Workouts/{user}', array('before' => 'auth','uses' => "WorkoutsController@_show"));
	Route::delete('ControlPanel/Workouts/{user}', array('before' => 'auth','uses' => "WorkoutsController@_destroy"));
	Route::post('ControlPanel/Workouts', array('before' => 'auth','uses' => "WorkoutsController@_ApiList"));
	Route::post('ControlPanel/Workouts/AddEdit/', array('before' => 'auth','uses' => "WorkoutsController@_AddEdit"));
	Route::get('ControlPanel/RestoreWorkout/{param}', array('before' => 'auth','uses' => "SystemController@restoreWorkout"));


	//Exercises
	Route::get('ControlPanel/Exercises', array('before' => 'auth','uses' => "ExercisesController@_index"));
	Route::get('ControlPanel/Exercises/{user}', array('before' => 'auth','uses' => "ExercisesController@_show"));
	Route::delete('ControlPanel/Exercises/{user}', array('before' => 'auth','uses' => "ExercisesController@_destroy"));
	Route::post('ControlPanel/Exercises', array('before' => 'auth','uses' => "ExercisesController@_ApiList"));
	Route::post('ControlPanel/Exercises/AddEdit/', array('before' => 'auth','uses' => "ExercisesController@_AddEdit"));
	Route::post('/Exercises/removeImage', array('before' => 'auth','uses' => "ExercisesController@removeImage"));


	//Equipments
	Route::get('ControlPanel/Equipments', array('before' => 'auth','uses' => "EquipmentsController@_index"));
	Route::get('ControlPanel/Equipments/{user}', array('before' => 'auth','uses' => "EquipmentsController@_show"));
	Route::delete('ControlPanel/Equipments/{user}', array('before' => 'auth','uses' => "EquipmentsController@_destroy"));
	Route::post('ControlPanel/Equipments', array('before' => 'auth','uses' => "EquipmentsController@_ApiList"));
	Route::post('ControlPanel/Equipments/AddEdit/', array('before' => 'auth','uses' => "EquipmentsController@_AddEdit"));

	//BodyGroups
	Route::get('ControlPanel/BodyGroups', array('before' => 'auth','uses' => "BodyGroupsController@_index"));
	Route::get('ControlPanel/BodyGroups/{user}', array('before' => 'auth','uses' => "BodyGroupsController@_show"));
	Route::delete('ControlPanel/BodyGroups/{user}', array('before' => 'auth','uses' => "BodyGroupsController@_destroy"));
	Route::post('ControlPanel/BodyGroups', array('before' => 'auth','uses' => "BodyGroupsController@_ApiList"));
	Route::post('ControlPanel/BodyGroups/AddEdit/', array('before' => 'auth','uses' => "BodyGroupsController@_AddEdit"));

	//Ratings
	Route::get('ControlPanel/Ratings', array('before' => 'auth','uses' => "RatingsController@_index"));
	Route::get('ControlPanel/Ratings/{user}', array('before' => 'auth','uses' => "RatingsController@_show"));
	Route::delete('ControlPanel/Ratings/{user}', array('before' => 'auth','uses' => "RatingsController@_destroy"));
	Route::post('ControlPanel/Ratings', array('before' => 'auth','uses' => "RatingsController@_ApiList"));
	Route::post('ControlPanel/Ratings/AddEdit/', array('before' => 'auth','uses' => "RatingsController@_AddEdit"));

	//ExercisesTypes
	Route::get('ControlPanel/ExercisesTypes', array('before' => 'auth','uses' => "ExercisestypesController@_index"));
	Route::get('ControlPanel/ExercisesTypes/{user}', array('before' => 'auth','uses' => "ExercisestypesController@_show"));
	Route::delete('ControlPanel/ExercisesTypes/{user}', array('before' => 'auth','uses' => "ExercisestypesController@_destroy"));
	Route::post('ControlPanel/ExercisesTypes', array('before' => 'auth','uses' => "ExercisestypesController@_ApiList"));
	Route::post('ControlPanel/ExercisesTypes/AddEdit/', array('before' => 'auth','uses' => "ExercisestypesController@_AddEdit"));

	//USER LOGOS
	Route::get('ControlPanel/UserLogos', array('before' => 'auth','uses' => "UserLogosController@_index"));
	Route::get('ControlPanel/UserLogos/{user}', array('before' => 'auth','uses' => "UserLogosController@_show"));
	Route::delete('ControlPanel/UserLogos/{user}', array('before' => 'auth','uses' => "UserLogosController@_destroy"));
	Route::post('ControlPanel/UserLogos', array('before' => 'auth','uses' => "UserLogosController@_ApiList"));
	Route::post('ControlPanel/UserLogos/AddEdit/', array('before' => 'auth','uses' => "UserLogosController@_AddEdit"));


	//Groups
	Route::get('ControlPanel/Groups', array('before' => 'auth','uses' => "GroupsController@_index"));
	Route::get('ControlPanel/Groups/{user}', array('before' => 'auth','uses' => "GroupsController@_show"));
	Route::delete('ControlPanel/Groups/{user}', array('before' => 'auth','uses' => "GroupsController@_destroy"));
	Route::post('ControlPanel/Groups', array('before' => 'auth','uses' => "GroupsController@_ApiList"));
	Route::post('ControlPanel/Groups/AddEdit/', array('before' => 'auth','uses' => "GroupsController@_AddEdit"));

	//User Groups
	Route::get('ControlPanel/UserGroups', array('before' => 'auth','uses' => "UserGroupsController@_index"));
	Route::get('ControlPanel/UserGroups/{user}', array('before' => 'auth','uses' => "UserGroupsController@_show"));
	Route::delete('ControlPanel/UserGroups/{user}', array('before' => 'auth','uses' => "UserGroupsController@_destroy"));
	Route::post('ControlPanel/UserGroups', array('before' => 'auth','uses' => "UserGroupsController@_ApiList"));
	Route::post('ControlPanel/UserGroups/AddEdit/', array('before' => 'auth','uses' => "UserGroupsController@_AddEdit"));


	//Memberships
	Route::get('ControlPanel/Memberships', array('before' => 'auth','uses' => "MembershipsController@_indexUsers"));
	Route::get('ControlPanel/Memberships/{user}', array('before' => 'auth','uses' => "MembershipsController@_showUsers"));
	Route::delete('ControlPanel/Memberships/{user}', array('before' => 'auth','uses' => "MembershipsController@_destroyUsers"));
	Route::post('ControlPanel/Memberships', array('before' => 'auth','uses' => "MembershipsController@_ApiListUsers"));
	Route::post('ControlPanel/Memberships/AddEdit/', array('before' => 'auth','uses' => "MembershipsController@_AddEditUsers"));

	//Memberships
	Route::get('ControlPanel/MembershipsTypes', array('before' => 'auth','uses' => "MembershipsController@_index"));
	Route::get('ControlPanel/MembershipsTypes/{user}', array('before' => 'auth','uses' => "MembershipsController@_show"));
	Route::delete('ControlPanel/MembershipsTypes/{user}', array('before' => 'auth','uses' => "MembershipsController@_destroy"));
	Route::post('ControlPanel/MembershipsTypes', array('before' => 'auth','uses' => "MembershipsController@_ApiList"));
	Route::post('ControlPanel/MembershipsTypes/AddEdit/', array('before' => 'auth','uses' => "MembershipsController@_AddEdit"));


	Route::get('ControlPanel/MaintenanceScripts', array('before' => 'auth','uses' => "SystemController@_indexScripts"));




	Route::get('ControlPanel/StripeSync', array('as'=>"",'uses' => "SystemController@syncWithStripeAndCheckMemberships"));
	Route::post('ControlPanel/removeUserFromDatabase', array('as'=>"",'uses' => "SystemController@removeUserFromDatabase"));
	Route::post('ControlPanel/workoutsToRestore', array('as'=>"",'uses' => "SystemController@workoutsToRestore"));

	Route::get('ControlPanel/managerExercises/{search}', array('uses' => "ExercisesController@managerExercises"));
	Route::post('ControlPanel/managerExercises/{search}', array('uses' => "ExercisesController@managerExercises"));
	Route::post('ControlPanel/exercises', array('uses' => "ExercisesController@controlPanelExercises"));
	Route::get('ControlPanel/errors', array('as'=>"ControlPanelErrors",'uses' => "ControlPanelController@indexErrors"));

	Route::post('ControlPanel/fixExercisesTranslations', array('as'=>"",'uses' => "SystemController@fixExercisesTranslations"));
	Route::post('ControlPanel/fixUsedExercises', array('as'=>"",'uses' => "SystemController@fixUsedExercises"));

	Route::get('ControlPanel/migrateWorkouts', array('as'=>"",'uses' => "SystemController@migrateWorkouts"));
	Route::get('ControlPanel/migrateWorkouts/{param1}', array('as'=>"",'uses' => "SystemController@migrateWorkouts"));
	Route::get('ControlPanel/errors/reset', array('uses' => "ControlPanelController@indexErrorsReset"));

	Route::get('ControlPanel/migrateworkoutFromUserToUser/{param1}/{param2}', array('as'=>"",'uses' => "SystemController@migrateWorkout"));

	

});


	//CRON JOBS
	Route::get('ControlPanel/dailyActivity', array('uses' => "SystemController@dailyActivity"));
	Route::get('ControlPanel/Email/Feeds', array('uses' => "FeedsController@ControlPanelFeeds"));
	Route::get('ControlPanel/Clients/sendTrainerClientWorkoutRevision', array('uses' => "ClientsController@sendTrainerClientWorkoutRevision"));




//|--------------------------------------------------------------------------
//| STATIC SITE
//|--------------------------------------------------------------------------
//TRAINEE

Route::get(Lang::get('routes./TraineeSignUp/',array(),"en")."{key}", array('as'=>"TraineeSignUp",'uses' => "UsersController@TraineeInvite"));
Route::get(Lang::get('routes./TraineeSignUp',array(),"fr"), array('as'=>"TraineeSignUpNoKey",'uses' => "UsersController@TraineeInvite"));
Route::get(Lang::get('routes./TraineeSignUp/',array(),"en")."{key}", array('as'=>"TraineeSignUp",'uses' => "UsersController@TraineeInvite"));
Route::get(Lang::get('routes./TraineeSignUp',array(),"fr"), array('as'=>"TraineeSignUpNoKey",'uses' => "UsersController@TraineeInvite"));


Route::post(Lang::get('routes./Trainee/SignUp'), array('as'=>"TraineeSignUpPost",'uses' => "UsersController@TraineeSignUp"));

//Alain Added this route below. 
Route::get('trainee/signup', function(){ return View::make('trainee/signUp');});

//TRAINER
Route::get(Lang::get('routes./TrainerSignUp/Workout/{key}'), array('uses' => "UsersController@TrainerInviteWithWorkout"));
Route::get(Lang::get('routes./TrainerSignUp/{key}'), array('as'=>"",'uses' => "UsersController@TrainerInvite"));
Route::get(Lang::get('routes./trainerGetStartedPaid'), array('as'=>"TrainerSignUp",'uses' => "UsersController@trainerGetStartedPaid"));
Route::get(Lang::get('routes./TrainerSignUp'), array('as'=>"TrainerSignUp",'uses' => "UsersController@trainerGetStarted"));
Route::post(Lang::get('routes./Trainer/SignUp'), array('as'=>"TrainerSignUpPost",'uses' => "UsersController@TrainerFreeTrialSignUp"));

Route::get(Lang::get('routes./Gym'), array("before"=>'checkIfLoggedInAndRedirect', 'as'=>"TrainerSignUpPost",'uses' => "UsersController@gym"));
Route::get(Lang::get('routes./GymSignUp'), array('as'=>"TrainerSignUpPost",'uses' => "UsersController@gymSignUp"));


Route::post(Lang::get('routes./SignUp'), array('uses' => "UsersController@demoSignUp"));


Route::get(Lang::get('routes./FreeTrialSignin'), array("before"=>'',"as"=>"freeTrialSignin",function () { return View::make('freeTrialSignin'); }));
Route::get(Lang::get('routes./UpgradePlan'), array('as'=>"cartUpgradePlan",'uses' => "OrdersController@upgradePlan"));
Route::get(Lang::get('routes./WorkoutBuilderPrice'), array("before"=>'',"as"=>"Price",function () { return View::make('price'); }));

Route::get('/thanksss', array("before"=>'',"as"=>"Price",function () { return View::make('Store.thankYou'); }));
Route::get('/MobileGetStarted', array("before"=>'',"as"=>"MobileGetStarted",function () { return View::make('MobileGetStarted'); }));
Route::get(Lang::get('routes./TermsAndConditions'), array("before"=>'',"as"=>"TermsAndConditions",function () { return View::make('TermsAndConditions'); }));



Route::get('/', array("before"=>'checkIfLoggedInAndRedirect',"as"=>"home",function () { return View::make(Helper::translateOverride('index')); }));




// //DEFAULT ROUTE
// Route::get(Lang::get('routes.{page?}', function($page="index"){ 
// 	if(View::exists("$page")) { 
// 		return View::make("$page"); 
// 	} else { 
// 		//return View::make('index');  
// 	}});
// //DEFAULT ROUTE
// Route::post(Lang::get('routes.{page?}', function($page="index"){ 
// 	if(View::exists("$page")) { 
// 		return View::make("$page"); 
// 	} else { 
// 		//return View::make('index');  
// 	}});
