<?php

use App\Http\Controllers\auth\SocialOAuthController;
use App\Http\Controllers\web\AppointmentsController;
use App\Http\Controllers\web\AvailabilitiesController;
use App\Http\Controllers\web\BodyGroupsController;
use App\Http\Controllers\web\CalendarController;
use App\Http\Controllers\web\ClientsController;
use App\Http\Controllers\web\ControlPanelController;
use App\Http\Controllers\web\EquipmentsController;
use App\Http\Controllers\web\ExercisesController;
use App\Http\Controllers\web\ExercisestypesController;
use App\Http\Controllers\web\FeedsController;
use App\Http\Controllers\web\FriendsController;
use App\Http\Controllers\web\GroupsController;
use App\Http\Controllers\web\MeasurementsController;
use App\Http\Controllers\web\MembershipsController;
use App\Http\Controllers\web\NotificationsController;
use App\Http\Controllers\web\ObjectivesController;
use App\Http\Controllers\web\OnBoardingController;
use App\Http\Controllers\web\OrdersController;
use App\Http\Controllers\web\PicturesController;
use App\Http\Controllers\web\RatingsController;
use App\Http\Controllers\web\RemindersController;
use App\Http\Controllers\web\SessionsController;
use App\Http\Controllers\web\SystemController;
use App\Http\Controllers\web\TagsController;
use App\Http\Controllers\web\TasksController;
use App\Http\Controllers\web\TestimonialsController;
use App\Http\Controllers\web\UserGroupsController;
use App\Http\Controllers\web\UserLogosController;
use App\Http\Controllers\web\UserMessagesController;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\web\WeightsController;
use App\Http\Controllers\web\WorkoutsController;
use App\Http\Controllers\web\WorkoutsPerformanceController;
use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/phpini', function (){ echo phpinfo(); });

// Pay Page
Route::controller(OrdersController::class)->group(function (){
    Route::get('/payment/{package}', 'indexPaypage');
    Route::post('/payment/{package}', 'processPaymentNoLogin');
    Route::get('/payment', 'indexPaypage');
    Route::post('/payment', 'processPaymentNoLogin');
    Route::get('/thankyou', 'thankyou')->name('thankyouPayment');
});

Route::get('/user/confirmation/{param1}', [UsersController::class, 'confirmEmail']);



// WEB ROUTES
Route::get('/', function () { return view(Helper::translateOverride('index')); });

//|--------------------------------------------------------------------------
//| GENERAL ROUTES
//|--------------------------------------------------------------------------

// LOGIN VIEW
Route::get('login', fn () => view(Helper::translateOverride('login')))->name('login')->middleware('guest');
Route::get('logout', [UsersController::class, 'logout']);
Route::delete('delete-account/{user}', [UsersController::class, 'destroy']);
Route::post('login', [UsersController::class, 'login']);
Route::get('login-with-email', [UsersController::class, 'loginWithEmail'])->name('login-with-email');
Route::post('registerNewsletter', [UsersController::class, 'registerNewsletter']);

Route::get('password/reset', [RemindersController::class, 'getRemind'])->name('password.remind');
Route::post('password/reset', [RemindersController::class, 'postRemind'])->name('password.request');
Route::get('password/reset/{token}', [RemindersController::class, 'getReset'])->name('password.reset');
Route::post('password/reset/{token}', [RemindersController::class, 'postReset'])->name('password.update');

// LOGIN WITH FACEBOOK
Route::get('login/facebook', [UsersController::class, 'loginFacebook']);
Route::get('login/trainee/facebook/{param1}', [UsersController::class, 'loginTraineeFacebook']);
Route::get('login/trainee/facebook', [UsersController::class, 'loginTraineeFacebook']);

// CLIENTS
Route::middleware('auth')->controller(ClientsController::class)->group(function () {
    Route::get('widgets/clients', 'index');
    Route::post('widgets/clients', 'index');
    Route::get('widgets/clients/{param}', 'show');
    Route::post('widgets/clients/full', 'indexFull');
    Route::post('widgets/clients/addEdit/', 'AddEdit');
    Route::delete('widgets/clients/{param}', 'destroy');
    Route::post('Trainer/addClient', 'addClient')->name('ProfileTrainer');
    Route::post('Trainer/addClientWithId', 'addClientWithId')->name('ProfileTrainer');
    Route::get('Client/{id}', 'clientProfile')->name('Profile');
    Route::get('Client/{id}/{username}', 'clientProfile')->name('Profile');
    Route::get('Trainer/Clients', 'showClients')->name('TrainerClients');
    Route::post('Clients/AddClient', 'addClientTrainer');
    Route::post('Clients/ModifyClient', 'modifyClient');
    Route::post('Clients/subscribe/toggle', 'subscribeClient');
});

// No Authentication Required
Route::get('Clients/Invitation/{invite}', [ClientsController::class, 'confirmClientByInvitation'])->name('ProfileTrainer');

// REPORTS
Route::middleware('auth')->group(function () {
    Route::post('reports/workoutsPerformance', [WorkoutsPerformanceController::class, 'workoutsPerformance']);
    Route::get('reports/workoutsPerformanceDetail', [WorkoutsPerformanceController::class, 'workoutsPerformanceDetail']);
});

// WIDGETS: WORKOUTS
Route::middleware('auth')->controller(WorkoutsController::class)->group(function () {
    Route::post('widgets/workouts/archive/{param}','archiveWorkout');
    Route::post('widgets/workouts/unarchive/{param}','unarchiveWorkout');
    Route::get('widgets/workouts/{param}','show');
    Route::post('widgets/workouts/addEdit/','AddEdit');
    Route::delete('widgets/workouts/{param}','destroy');
    Route::post('widgets/workoutsTrainer','indexWorkoutTrainer');
    Route::post('widgets/workoutsClient','indexWorkoutsClient');
    Route::post('widgets/workoutsLibrary','indexWorkoutsLibrary');
    Route::post('widgets/workoutsTrainer/full','indexWorkoutTrainerFull');
    Route::get('widgets/workoutsTrainee/{param}','show');
    Route::post('widgets/workoutsTrainee/addEdit/','AddEdit');
    Route::delete('widgets/workoutsTrainee/{param}','destroy');
    Route::post('widgets/workoutsTrainee','indexWorkoutTrainee');
    Route::post('widgets/workoutsTrainee/full','indexWorkoutTraineeFull'); // FUNCTION DOES NOT EXISTS
});

// WORKOUT MARKET
Route::middleware('auth')->controller(WorkoutsController::class)->group(function () {
    Route::get('WorkoutMarket','indexMarket');
    Route::get('widgets/workoutMarket/{param}','showWorkoutMarket');
    Route::post('widgets/workoutMarket','indexWorkoutMarket');
    Route::post('widgets/workoutMarket/full','indexWorkoutMarketFull');
    Route::post('Workouts/Search','searchWorkout');
    Route::get('Workouts/Client/{param1}/{param2}','clientWorkouts');
    Route::get('Workouts/Client/{param1}','clientWorkouts');
    Route::get('Client/editWorkout/{param1}/{param2}','assignWorkoutToClientEdit');
    Route::get('Client/AssignWorkout/{param1}/{param2}','assignWorkoutToClient');
});

// WORKOUT PREVIEW
Route::middleware('auth')->controller(WorkoutsController::class)->group(function () {
    Route::get('Workout/Preview/{workoutid}/{workoutName}/{workoutAuthor}','previewWorkout');
    Route::get('Workout/Preview/{workoutid}/{workoutName}','previewWorkout');
    Route::get('Workout/Preview/{workoutid}','previewWorkout');
});

// WORD WIDGETS
Route::middleware('auth')->controller(UsersController::class)->group(function () {
    Route::post('widgets/videoWord', 'indexVideoWord');
    Route::post('widgets/videoWord/full', 'indexVideoWordFull');
    Route::get('widgets/videoWord', 'index');
    Route::get('widgets/videoWord/{param}', 'show');
    Route::post('widgets/videoWord/addEdit', 'AddEditVideoWord');
});

// REMINDERS AND TASKS
Route::middleware('auth')->controller(TasksController::class)->group(function () {
    Route::post('widgets/tasks','index');
    Route::post('widgets/tasks/completeTask','completeTask');
    Route::get('widgets/tasks','index');
    Route::get('widgets/tasks/{param}','show');
    Route::post('widgets/tasks/addEdit','AddEdit');
    Route::delete('widgets/tasks/{param}','destroy');
});

// WORKOUT SALES
Route::post('widgets/workoutSales/', [WorkoutsController::class, 'workoutSales']);

// APPOINTMENTS
Route::middleware('auth')->controller(AppointmentsController::class)->group(function () {
    Route::post('widgets/appointments','index');
    Route::post('widgets/appointments/full','indexFull');
    Route::get('widgets/appointments','index');
    Route::get('widgets/appointments/{param}','show');
    Route::post('widgets/appointments/addEdit/','AddEdit');
    Route::delete('widgets/appointments/destroy','destroy');
});

// AVAILABILITIES
Route::middleware('auth')->controller(AvailabilitiesController::class)->group(function () {
    Route::get('widgets/availabilities/addEntry/{start}/{end}','addEntry');
    Route::post('widgets/availabilities/addEdit/','AddEdit');
    Route::post('widgets/availabilities/getCalendar','getCalendar');
    Route::post('widgets/availabilities/updateEvent','updateEvent');
});

// CALENDAR
Route::middleware('auth')->controller(CalendarController::class)->group(function () {
    Route::post('widgets/calendar','index');
    Route::post('widgets/calendar/full','indexFull');
    Route::get('widgets/calendar','index');
    Route::get('widgets/calendar/{param}','show');
    Route::post('widgets/calendar/addEdit/','AddEdit');
});

// ACTIVITY CALENDAR
Route::middleware('auth')->group(function () {
    Route::post('/widgets/calendarActivity', [CalendarController::class, 'index']);
    Route::post('/widgets/calendarActivity/full', [CalendarController::class, 'indexFull']);
    Route::get('/widgets/calendarActivity', [CalendarController::class, 'index']);
    Route::get('/widgets/calendarActivity/{param}', [CalendarController::class, 'show']);
    Route::post('/widgets/calendarActivity/addEdit/', [CalendarController::class, 'AddEdit']);
});

// BIOGRAPHY
Route::middleware('auth')->group(function () {
    Route::post('widgets/biography', [UsersController::class, 'indexBio']);
    Route::post('widgets/biography/full', [UsersController::class, 'indexBioFull']);
    Route::get('widgets/biography', [UsersController::class, 'index']);
    Route::get('widgets/biography/{param}', [UsersController::class, 'show']);
    Route::post('widgets/biography/addEdit/', [UsersController::class, 'AddEditBio']);
});

// TESTIMONIALS
Route::middleware('auth')->group(function () {
    Route::post('widgets/testimonials', [TestimonialsController::class, 'index']);
    Route::post('widgets/testimonials/full', [TestimonialsController::class, 'indexFull']);
    Route::post('widgets/testimonials/status', [TestimonialsController::class, 'approveTestimonial']);
    Route::get('widgets/testimonials', [TestimonialsController::class, 'index']);
    Route::get('widgets/testimonials/{param}', [TestimonialsController::class, 'show']);
    Route::post('widgets/testimonials/addEdit/', [TestimonialsController::class, 'AddEdit']);
    Route::delete('widgets/testimonials/{param}', [TestimonialsController::class, 'destroy']);
});

// CLIENTS FEED
Route::middleware('auth')->group(function () {
    Route::post('widgets/clientsFeed', [FeedsController::class, 'indexClients']);
    Route::post('widgets/clientsFeed/full', [FeedsController::class, 'indexClientsFull']);
    Route::get('widgets/clientsFeed/Archive/{param}/{param1}', [FeedsController::class, 'archive']);
    Route::get('widgets/clientsFeed', [ClientsController::class, 'indexClient']);
    Route::get('widgets/clientsFeed/{param}', [ClientsController::class, 'showClient']);
    Route::post('widgets/clientsFeed/addEdit/', [ClientsController::class, 'AddEditClient']);
    Route::delete('widgets/clientsFeed/{param}', [ClientsController::class, 'destroy']);
});

// CLIENT FEED
Route::middleware('auth')->group(function () {
    Route::post('widgets/clientFeed', [FeedsController::class, 'indexClient']);
    Route::post('widgets/clientFeed/full', [FeedsController::class, 'indexClientFull']);
    Route::get('widgets/clientFeed', [ClientsController::class, 'indexClient']);
    Route::get('widgets/clientFeed/{param}', [ClientsController::class, 'showClient']);
    Route::get('list/emails', [ClientsController::class, 'showClientList']);
    Route::post('widgets/clientFeed/addEdit/', [ClientsController::class, 'AddEditClient']);
    Route::delete('widgets/clientFeed/{param}', [ClientsController::class, 'destroy']);
});

// WEIGHT
Route::middleware('auth')->group(function () {
    Route::post('widgets/weight', [WeightsController::class, 'index']);
    Route::post('widgets/weight/full', [WeightsController::class, 'indexFull']);
    Route::get('widgets/weight', [WeightsController::class, 'index']);
    Route::get('widgets/weight/{param}', [WeightsController::class, 'show']);
    Route::post('widgets/weight/addEdit/', [WeightsController::class, 'AddEdit']);
    Route::delete('widgets/weight/{param}', [WeightsController::class, 'destroy']);
});

// MEASUREMENTS
Route::middleware('auth')->group(function () {
    Route::post('widgets/measurements', [MeasurementsController::class, 'index']);
    Route::post('widgets/measurements/full', [MeasurementsController::class, 'indexFull']);
    Route::get('widgets/measurements', [MeasurementsController::class, 'index']);
    Route::get('widgets/measurements/{param}', [MeasurementsController::class, 'show']);
    Route::post('widgets/measurements/addEdit/', [MeasurementsController::class, 'AddEdit']);
    Route::delete('widgets/measurements/{param}', [MeasurementsController::class, 'destroy']);
});

// PICTURES
Route::middleware('auth')->group(function () {
    Route::post('widgets/pictures', [PicturesController::class, 'index']);
    Route::post('widgets/pictures/full', [PicturesController::class, 'indexFull']);
    Route::get('widgets/pictures', [PicturesController::class, 'index']);
    Route::get('widgets/pictures/{param}', [PicturesController::class, 'show']);
    Route::post('widgets/pictures/addEdit', [PicturesController::class, 'AddEdit']);
    Route::delete('widgets/pictures/{param}', [PicturesController::class, 'destroy']);
});

// OBJECTIVES
Route::middleware('auth')->group(function () {
    Route::post('widgets/objectives', [ObjectivesController::class, 'index']);
    Route::post('widgets/objectives/full', [ObjectivesController::class, 'indexFull']);
    Route::get('widgets/objectives', [ObjectivesController::class, 'index']);
    Route::get('widgets/objectives/{param}', [ObjectivesController::class, 'show']);
    Route::post('widgets/objectives/addEdit/', [ObjectivesController::class, 'AddEdit']);
    Route::delete('widgets/objectives/{param}', [ObjectivesController::class, 'destroy']);
});

// TAGS
Route::middleware('auth')->group(function () {
    Route::post('widgets/tags/removeTag', [TagsController::class, 'destroyTagWorkout']);
    Route::post('widgets/tags', [TagsController::class, 'index']);
    Route::post('widgets/tagsWorkout', [TagsController::class, 'indexWorkout']);
    Route::post('widgets/tags/full', [TagsController::class, 'indexFull']);
    Route::get('widgets/tags', [TagsController::class, 'index']);
    Route::get('widgets/tags/{param}', [TagsController::class, 'show']);
    Route::post('widgets/tags/addEdit/', [TagsController::class, 'AddEdit']);
    Route::delete('widgets/tags/{param}', [TagsController::class, 'destroy']);
});

// FRIENDS
Route::middleware('auth')->group(function () {
    Route::post('widgets/friends', [FriendsController::class, 'index']);
    Route::post('widgets/friends/full', [FriendsController::class, 'indexFull']);
    Route::get('widgets/friends', [FriendsController::class, 'index']);
    Route::get('widgets/friends/suggest', [FriendsController::class, 'indexSuggest']);
    Route::post('widgets/friends/addEdit/', [FriendsController::class, 'addFriend']);
    Route::delete('widgets/friends/{param}', [FriendsController::class, 'destroy']);
});

// SESSIONS
Route::middleware('auth')->group(function () {
    Route::post('widgets/sessions', [SessionsController::class, 'index']);
    Route::post('widgets/sessions/full', [SessionsController::class, 'indexFull']);
    Route::get('widgets/sessions', [SessionsController::class, 'index']);
    Route::get('widgets/sessions/{param}', [SessionsController::class, 'show']);
    Route::post('widgets/sessions/addEdit/', [SessionsController::class, 'AddEdit']);
    Route::delete('widgets/sessions/{param}', [SessionsController::class, 'destroy']);
});

// EXERCISES
Route::middleware('auth')->group(function () {
    Route::post('widgets/exercises', [ExercisesController::class, 'index']);
    Route::post('widgets/exercises/full', [ExercisesController::class, 'indexFull']);
    Route::get('widgets/exercises', [ExercisesController::class, 'index']);
    Route::post('widgets/exercises/addEdit', [ExercisesController::class, 'AddEdit']);
    Route::delete('widgets/exercises/{param}', [ExercisesController::class, 'destroy']);
});

// NOTIFICATIONS
Route::middleware('auth')->group(function () {
    Route::post('widgets/notifications', [NotificationsController::class, 'index']);
    Route::post('widgets/notificationsRead', [NotificationsController::class, 'readNotifications']);
    Route::post('widgets/notifications/full', [NotificationsController::class, 'indexFull']);
    Route::get('widgets/notifications', [NotificationsController::class, 'index']);
    Route::get('widgets/notifications/{param}', [NotificationsController::class, 'show']);
    Route::post('widgets/notifications/addEdit', [NotificationsController::class, 'AddEdit']);
    Route::delete('widgets/notifications/{param}', [NotificationsController::class, 'destroy']);
});

// MESSAGES
Route::middleware('auth')->group(function () {
    Route::post('widgets/messages', [UserMessagesController::class, 'index']);
    Route::post('widgets/messages/readUserMessages', [UserMessagesController::class, 'readUserMessages']);
    Route::post('widgets/messages/full', [UserMessagesController::class, 'indexFull']);
    Route::get('widgets/messages', [UserMessagesController::class, 'index']);
    Route::get('widgets/messages/dialog/{param}', [UserMessagesController::class, 'dialog']);
    Route::post('widgets/messages/addEdit', [UserMessagesController::class, 'AddEdit']);
    Route::get('widgets/messages/{param}', [UserMessagesController::class, 'show']);
    Route::delete('widgets/messages/{param}', [UserMessagesController::class, 'destroy']);
});

// TRAINER REPORTS
Route::middleware('auth')->group(function () {
    Route::get('/Trainer/Reports/WorkoutsPerformanceClients', [WorkoutsPerformanceController::class, 'workoutsPerformanceClientsIndex']);
});

// ONBOARDING
Route::middleware('auth')->group(function () {
    Route::post('onboarding/message/{param1}', [OnBoardingController::class, 'messageChat']);
    Route::get('Trainer/onBoarding/skipDemo', [OnBoardingController::class, 'skipDemo']);
    Route::get('Trainer/onBoarding/start', [OnBoardingController::class, 'start']);
    Route::get('Trainer/onBoarding/stop', [OnBoardingController::class, 'skipDemo']);
    Route::get('Trainer/onBoarding/step1', [OnBoardingController::class, 'step1']);
    Route::get('Trainer/onBoarding/step2', [OnBoardingController::class, 'step2']);
    Route::get('Trainer/onBoarding/step21', [OnBoardingController::class, 'step21']);
    Route::get('Trainer/onBoarding/step22', [OnBoardingController::class, 'step22']);
    Route::get('Trainer/onBoarding/step23', [OnBoardingController::class, 'step23']);
    Route::get('Trainer/onBoarding/step3', [OnBoardingController::class, 'step3']);
    Route::get('Trainer/onBoarding/step4', [OnBoardingController::class, 'step4']);
    Route::get('Trainer/onBoarding/step41', [OnBoardingController::class, 'step41']);
    Route::get('Trainer/onBoarding/step5', [OnBoardingController::class, 'step5']);
    Route::get('Trainer/onBoarding/step6', [OnBoardingController::class, 'step6']);
    Route::get('Trainer/onBoarding/step7', [OnBoardingController::class, 'step7']);
    Route::get('Trainer/onBoarding/step8', [OnBoardingController::class, 'step8']);
});

// WORKOUT
Route::middleware(['auth', 'userTypeChecker'])->group(function () {
    Route::get('Trainer/CreateWorkout/{param}', [WorkoutsController::class, 'createNewWorkoutTrainer']);
    Route::get('Trainer/CreateWorkout', [WorkoutsController::class, 'createNewWorkoutTrainer'])->name('trainerCreateWorkout');
    Route::post('Trainer/autoSaveWorkout', [WorkoutsController::class, 'autoSaveWorkout']);
    Route::get('Trainer/CreateWorkout2', [WorkoutsController::class, 'createNewWorkoutTrainer']);
    Route::get('Trainer/Workouts/{userName?}', [WorkoutsController::class, 'indexWorkoutsTrainer'])->name('trainerWorkouts');
    Route::get('Trainer/Workouts', [WorkoutsController::class, 'indexWorkoutsTrainer'])->name('trainerWorkouts2');
    Route::post('Trainer/CreateWorkout', [WorkoutsController::class, 'createNewWorkoutAddEditTrainer']);
    Route::post('Trainer/CreateWorkout/{param1}', [WorkoutsController::class, 'createNewWorkoutAddEditTrainer']);
    Route::get('Trainer/CreateWorkout/Client/{param}', [WorkoutsController::class, 'createNewWorkoutTrainerToClient']);
});

Route::middleware('auth')->group(function () {
    Route::get('Workouts/removeWorkout/{id}', [WorkoutsController::class, 'deleteWorkout']);
    Route::post('widgets/workouts', [WorkoutsController::class, 'index']);
    Route::post('widgets/workouts_create', [WorkoutsController::class, 'indexCreate']);
    Route::post('widgets/trendingWorkouts', [WorkoutsController::class, 'indexTrendingWorkouts']);
    Route::post('widgets/workouts/full', [WorkoutsController::class, 'indexFull']);
    Route::get('widgets/workouts', [WorkoutsController::class, 'index']);
});

// USERS
Route::middleware('auth')->group(function () {
    Route::get('widgets/people/suggest', [UsersController::class, 'indexSuggestPeople']);
    Route::get('widgets/people/suggestWithClient', [UsersController::class, 'indexSuggestPeopleWithClients']);
});


// Workout
Route::post('Workout/Performance/discartOldPerformance', [WorkoutsController::class, 'discartOldPerformance']);
Route::post('Workout/Performance/saveProgressPerformance', [WorkoutsController::class, 'saveProgressPerformance']);
Route::post('Workout/Performance/Start', [WorkoutsController::class, 'startWorkoutPerformance']);
Route::get('Workout/Performance/showDetails/{param}', [WorkoutsPerformanceController::class, 'workoutsPerformanceDetail']);
Route::post('Workout/Performance', [WorkoutsController::class, 'performWorkout']);
Route::get('WorkoutPDF/{id}/{name}/{author}', [WorkoutsController::class, 'viewWorkoutPDF'])->name('workout');
Route::get('WorkoutImage/{id}/{name}/{author}', [WorkoutsController::class, 'viewWorkoutImage'])->name('workout');
Route::get('Workout/Edit/{id}', [WorkoutsController::class, 'editWorkout'])->middleware('auth')->name('workout');
Route::post('Workout/Duplicate', [WorkoutsController::class, 'duplicateWorkout'])->middleware('auth')->name('workout');
Route::post('Workout/Edit/', [WorkoutsController::class, 'saveEditWorkout'])->middleware('auth')->name('workout');
Route::post('Workout/Duplicate/', [WorkoutsController::class, 'duplicateWorkout'])->middleware('auth')->name('workout');
Route::post('Workout/saveSingleSet', [WorkoutsController::class, 'saveSingleSet'])->middleware('auth');
Route::post('Workout/saveAllSets', [WorkoutsController::class, 'saveAllSets'])->middleware('auth');
Route::post('Workout/addSets', [WorkoutsController::class, 'addSets'])->middleware('auth');
Route::post('Workout/addSetsWithTable', [WorkoutsController::class, 'addSetsReturnTable'])->middleware('auth');

Route::post('Workout/saveAllAddNewSets', [WorkoutsController::class, 'saveAllAddNewSets'])->middleware('auth');
Route::post('Workout/exerciseCompleted', [WorkoutsController::class, 'exerciseCompleted'])->middleware('auth');
Route::post('Workout/workoutCompleted', [WorkoutsController::class, 'workoutCompleted'])->middleware('auth');
Route::get('Workout/ShareWorkout/{workout}', [WorkoutsController::class, 'shareWorkoutIndex'])->middleware('auth');
Route::get('Workout/ShareWorkouts/', [WorkoutsController::class, 'shareWorkoutIndex'])->middleware('auth');
Route::get('Workout/PrintWorkout/{workout}', [WorkoutsController::class, 'PrintWorkoutPDF'])->middleware('auth');
Route::get('Workout/PrintWorkouts/{workout}', [WorkoutsController::class, 'PrintWorkouts'])->middleware('auth');
Route::get('Workout/PrintWorkoutInternal/{workout}', [WorkoutsController::class, 'PrintWorkout']);
Route::get('Workout/PrintWorkoutInternal/{workout}/{locale}', [WorkoutsController::class, 'PrintWorkout']);
Route::post('Workout/subscribe/toggle', [WorkoutsController::class, 'subscribeTrainer'])->middleware('auth');
Route::post('Workout/ShareByEmail', [WorkoutsController::class, 'ShareByEmail'])->middleware('auth');
Route::post('Workout/ShareByUser/', [WorkoutsController::class, 'ShareByUser'])->middleware('auth');
Route::post('Workout/ShareByLink/', [WorkoutsController::class, 'ShareByLink'])->middleware('auth');
Route::get('Workout/AddToMyWorkouts/{workout}', [WorkoutsController::class, 'AddToMyWorkouts'])->middleware('auth');
Route::get('Workout/exercisePerformance/{workoutexercise}', [WorkoutsController::class, 'exercisePerformance'])->middleware('auth');
Route::post('Workout/addCustomPicture/', [WorkoutsController::class, 'addCustomPicture'])->middleware('auth');
Route::post('Workout/unit/update', [WorkoutsController::class, 'updateUnitExerciseGroup'])->middleware('auth');


Route::get('Workout/{id}/{name}/{author}', [WorkoutsController::class, 'viewWorkout'])->middleware('auth')->name('workout');
Route::get('Workout/{id}//{author}', [WorkoutsController::class, 'viewWorkoutNoName'])->middleware('auth')->name('workout');
Route::get('Trainee/Workout/{id}/{name}/{author}', [WorkoutsController::class, 'viewWorkoutTrainee'])->middleware('auth')->name('workout');
Route::get('Trainee/Workout{id}//{author}', [WorkoutsController::class, 'viewWorkoutTrainee'])->middleware('auth')->name('workout');
Route::get('editWorkout/{id}/{name}/{author}/{client}', [WorkoutsController::class, 'editWorkout'])->middleware('auth')->name('workout');
Route::get('editWorkout/{id}/{name}/{author}', [WorkoutsController::class, 'editWorkout'])->middleware('auth')->name('workout');
Route::get('editWorkout/{id}//{author}', [WorkoutsController::class, 'editWorkout'])->middleware('auth')->name('workout');
Route::get('editWorkout/{id}', [WorkoutsController::class, 'editWorkout'])->middleware('auth')->name('workout');
Route::get('Workouts/createUserDownload/{workouts}/{param1}', [WorkoutsController::class, 'createUserDownload'])->middleware('auth')->name('workout');
Route::get('Workouts/createUserDownload/{workouts}/{param1}/{param2}', [WorkoutsController::class, 'createUserDownload'])->middleware('auth')->name('workout');
Route::get('Workouts/addWorkoutToClient/{param1}/{param2}', [WorkoutsController::class, 'addToWorkoutClient'])->middleware('auth');

// THIS CANNOT BE TRANSLATED
Route::get('/WorkoutInternal/{id}/{locale}/{name}/{author}', [WorkoutsController::class, 'viewWorkoutInternal'])->name('workout');
Route::get('/WorkoutInternal/{id}/{locale}//{author}', [WorkoutsController::class, 'viewWorkoutInternal'])->name('workout');
Route::get('/WorkoutInternal/{id}/{locale}/{author}', [WorkoutsController::class, 'viewWorkoutInternal'])->name('workout');

// SHARING A WORKOUT
Route::get('Share/Workout/Accept/{link}', [WorkoutsController::class, 'acceptWorkoutBySharingLink']);
Route::get('Share/Workout/{link}', [WorkoutsController::class, 'openWorkoutBySharingLink']);
Route::post('Share/Facebook', [UsersController::class, 'shareOnFacebook']);

// FRIENDS
Route::post('Friends/Add', [FriendsController::class, 'addFriend'])->middleware('auth');
Route::post('Friends/Search', [FriendsController::class, 'searchFriend'])->middleware('auth');
Route::get('Trainer/Friends', [FriendsController::class, 'indexFriendsTrainer'])->middleware('auth')->name('TrainerFriends');
Route::post('Trainer/Friends', [FriendsController::class, 'indexFullTrainer'])->middleware('auth');

// Exercises
Route::get('Trainer/Exercises', [ExercisesController::class, 'indexExercisesTrainer'])->middleware(['auth','userTypeChecker'])->name('ExercisesHomeTrainer');
Route::post('Trainer/Exercises', [ExercisesController::class, 'indexFullTrainer'])->middleware(['auth','userTypeChecker']);

Route::post('Exercises/switchPictures', [ExercisesController::class, 'switchPictures'])->middleware(['auth']);
Route::post('Exercises/ClearAttribute', [ExercisesController::class, 'ClearAttribute'])->middleware(['auth']);

Route::post('Exercises/Rotate/Left', [ExercisesController::class, 'rotateLeft'])->middleware(['auth']);
Route::post('Exercises/Rotate/Right', [ExercisesController::class, 'rotateRight'])->middleware(['auth']);

Route::post('Exercises/Rotate1/Left', [ExercisesController::class, 'rotateLeft1'])->middleware(['auth']);
Route::post('Exercises/Rotate1/Right', [ExercisesController::class, 'rotateRight1'])->middleware(['auth']);

Route::post('Exercises/Rotate2/Left', [ExercisesController::class, 'rotateLeft2'])->middleware(['auth']);
Route::post('Exercises/Rotate2/Right', [ExercisesController::class, 'rotateRight2'])->middleware(['auth']);

Route::get('Exercises/addExercise', [ExercisesController::class, 'indexAdd'])->middleware(['auth']);
Route::get('Exercises/addExerciseInWorkout', [ExercisesController::class, 'indexAddInWorkout'])->middleware(['auth']);
Route::post('Exercises/Search', [ExercisesController::class, 'searchExercise'])->middleware(['auth']);
Route::post('Exercises/AddExercise', [ExercisesController::class, 'AddEdit'])->middleware(['auth']);
Route::post('Exercises/AddExerciseInWorkout', [ExercisesController::class, 'AddEditInWorkout'])->middleware(['auth']);
Route::get('Exercise/{id}/{name}', [ExercisesController::class, 'show'])->middleware(['auth']);
Route::get('Exercise/{id}', [ExercisesController::class, 'show'])->middleware(['auth']);
Route::get('EditExercise/{id}', [ExercisesController::class, 'editExercise'])->middleware(['auth']);
Route::post('Exercises/AddToFavorite', [ExercisesController::class, 'addToFavorites'])->middleware(['auth']);


// User Messages
Route::get('Events/eventMessageClient/{from}/{to}', [UserMessagesController::class, 'eventMessageClient']);
Route::get('Events/test', [UserMessagesController::class, 'eventTest']);

// Trainer Mail
Route::middleware(['auth', 'userTypeChecker'])->group(function () {
    Route::get('Trainer/Mail', [UserMessagesController::class, 'indexMail']);
    Route::get('Trainer/ComposeMail/{user}', [UserMessagesController::class, 'composeMailToUser']);
    Route::get('Trainer/ComposeMail', [UserMessagesController::class, 'composeMail']);
});

// Global Search
Route::middleware(['auth'])->group(function () {
    Route::match(['get', 'post'], __('routes./Search'), [UsersController::class, 'globalSearch']);
});

// Trainee
Route::middleware('auth')->group(function () {
    Route::get('Trainee/SendFeedback', [UsersController::class, 'sendFeedback']);
    Route::get('Trainee/ViewWorkout', [UsersController::class, 'viewWorkoutTrainee']);
    Route::get('Trainee/Workouts', [UsersController::class, 'viewWorkoutsTrainee'])->name('traineeWorkouts');
});

Route::middleware(['auth', 'userTypeChecker'])->group(function () {
    Route::get('Trainee/Settings', [UsersController::class, 'indexSettings'])->name('TraineeSettings');
    Route::post('Trainee/Settings', [UsersController::class, 'settingsSave']);
    Route::get('Trainee/{username}/Profile', [UsersController::class, 'indexProfile'])->name('Profile');
    Route::get('Trainee/Profile', [UsersController::class, 'indexProfile'])->name('TraineeProfile');
    Route::get('Trainee/EditProfile', [UsersController::class, 'indexEditTrainee'])->name('EditProfile');
    Route::post('Trainee/EditProfile', [UsersController::class, 'TraineeSave'])->name('EditProfilePost');
});

// Profile Image Rotation
Route::middleware(['auth'])->group(function () {
    Route::post('Profile/Rotate/Left', [UsersController::class, 'rotateLeft']);
    Route::post('Profile/Rotate/Right', [UsersController::class, 'rotateRight']);
});

Route::get('MembershipManagement', [MembershipsController::class, 'indexMembershipManagement'])->middleware('auth');
Route::get('MembershipManagementOld', [MembershipsController::class, 'indexMembershipManagementOld'])->middleware('auth');

// Trainer
Route::middleware(['auth', 'userTypeChecker'])->group(function () {
    Route::get('Trainer/Settings', [UsersController::class, 'indexSettingsTrainer'])->name('TrainerSettings');
    Route::get('Trainer/Memberships', [UsersController::class, 'indexMemberships']);
    Route::post('Trainer/EmployeeManagement/addEmployees', [GroupsController::class, 'addEmployees']);
    Route::get('Trainer/EmployeeManagement/resendInvite/{userid}', [GroupsController::class, 'resendGroupInvitation']);
    Route::get('Trainer/EmployeeManagement/PersonifyBack', [UsersController::class, 'personifyFromGroupBack']);
    Route::get('Trainer/EmployeeManagement/Personify/{param}', [UsersController::class, 'personifyFromGroup']);
    Route::get('Trainer/EmployeeManagement/RemoveAccess/{param}', [GroupsController::class, 'removeAccess']);
    Route::post('Trainer/EmployeeManagement/ChangeRole', [GroupsController::class, 'changeRole']);
    Route::get('Trainer/Profile', [UsersController::class, 'indexProfileTrainer'])->name('TrainerProfile');
    Route::get('Trainer/EditProfile', [UsersController::class, 'indexEditTrainer'])->name('EditProfileTrainer');
    Route::post('Trainer/EditProfile', [UsersController::class, 'TrainerSave'])->name('EditProfilePostTrainer');
    Route::get('Trainer/{userId}/{userName}', [UsersController::class, 'indexTrainer']);
    Route::get('Trainer/{username}', [UsersController::class, 'trainerIndex'])->name('Trainer');
    Route::get('Trainer', [UsersController::class, 'trainerIndex'])->name('TrainerBase');
    Route::get('Trainee/{username}', [UsersController::class, 'index'])->name('Trainee');
    Route::get('Trainee', [UsersController::class, 'index'])->name('TraineeBase');
});

//|--------------------------------------------------------------------------
//| GYM MANAGEMENT
//|--------------------------------------------------------------------------
Route::get('employeeManagement', [GroupsController::class, 'showGroup'])->name('employeeManagement');

//|--------------------------------------------------------------------------
//| STORE
//|--------------------------------------------------------------------------
Route::controller(OrdersController::class)->group(function () {
    Route::get('Store/Cart', 'index')->name('cart');
    Route::get('Store/removeFromCart', 'removeFromCart');
    Route::get('Store/addToCart/{var1}/{var2}', 'addToCart');
    Route::get('Store/removeItem/{var1}', 'removeFromCart');
    Route::get('Store/Checkout', 'checkout')->name('StoreCheckout');
    Route::post('Store/ProcessPayment', 'processPayment')->name('checkout');
    Route::get('Store/CreateAccount', 'createAccount')->name('StoreCreateAccount');
});

//|--------------------------------------------------------------------------
//| LANGUAGES
//|--------------------------------------------------------------------------
Route::get('lang/{param1}', [SystemController::class, 'changeLanguange']);

//|--------------------------------------------------------------------------
//| FEEDBACK
//|--------------------------------------------------------------------------
Route::post('Feedback', [SystemController::class, 'sendFeedback']);

//CONTROL PANEL ========================================================================================================================
Route::middleware(['controlpanel', 'auth'])->group(function () {

    Route::get('/ControlPanel/Users/loginUserAdmin/{id}', [UsersController::class, 'controlPanelLoginUserAdmin']);
    Route::get('/ControlPanel', [UsersController::class, '_index']);

    // Users
    Route::get('ControlPanel/Users', [UsersController::class, '_index']);
    Route::get('ControlPanel/Users/{user}', [UsersController::class, '_show']);
    Route::delete('ControlPanel/Users/{user}', [UsersController::class, '_destroy']);
    Route::post('ControlPanel/Users', [UsersController::class, '_ApiList']);
    Route::post('ControlPanel/Users/AddEdit/', [UsersController::class, '_AddEdit']);

    // Workouts
    Route::get('ControlPanel/Workouts', [WorkoutsController::class, '_index']);
    Route::get('ControlPanel/Workouts/{user}', [WorkoutsController::class, '_show']);
    Route::delete('ControlPanel/Workouts/{user}', [WorkoutsController::class, '_destroy']);
    Route::post('ControlPanel/Workouts', [WorkoutsController::class, '_ApiList']);
    Route::post('ControlPanel/Workouts/AddEdit/', [WorkoutsController::class, '_AddEdit']);
    Route::get('ControlPanel/RestoreWorkout/{param}', [SystemController::class, 'restoreWorkout']);

    // Exercises
    Route::get('ControlPanel/Exercises', [ExercisesController::class, '_index']);
    Route::get('ControlPanel/Exercises/{user}', [ExercisesController::class, '_show']);
    Route::delete('ControlPanel/Exercises/{user}', [ExercisesController::class, '_destroy']);
    Route::post('ControlPanel/Exercises', [ExercisesController::class, '_ApiList']);
    Route::post('ControlPanel/Exercises/AddEdit/', [ExercisesController::class, '_AddEdit']);
    Route::post('/Exercises/removeImage', [ExercisesController::class, 'removeImage']);

    // Equipments
    Route::get('ControlPanel/Equipments', [EquipmentsController::class, '_index']);
    Route::get('ControlPanel/Equipments/{user}', [EquipmentsController::class, '_show']);
    Route::delete('ControlPanel/Equipments/{user}', [EquipmentsController::class, '_destroy']);
    Route::post('ControlPanel/Equipments', [EquipmentsController::class, '_ApiList']);
    Route::post('ControlPanel/Equipments/AddEdit/', [EquipmentsController::class, '_AddEdit']);

    // BodyGroups
    Route::get('ControlPanel/BodyGroups', [BodyGroupsController::class, '_index']);
    Route::get('ControlPanel/BodyGroups/{user}', [BodyGroupsController::class, '_show']);
    Route::delete('ControlPanel/BodyGroups/{user}', [BodyGroupsController::class, '_destroy']);
    Route::post('ControlPanel/BodyGroups', [BodyGroupsController::class, '_ApiList']);
    Route::post('ControlPanel/BodyGroups/AddEdit/', [BodyGroupsController::class, '_AddEdit']);

    // Ratings
    Route::get('ControlPanel/Ratings', [RatingsController::class, '_index']);
    Route::get('ControlPanel/Ratings/{user}', [RatingsController::class, '_show']);
    Route::delete('ControlPanel/Ratings/{user}', [RatingsController::class, '_destroy']);
    Route::post('ControlPanel/Ratings', [RatingsController::class, '_ApiList']);
    Route::post('ControlPanel/Ratings/AddEdit/', [RatingsController::class, '_AddEdit']);

    // ExercisesTypes
    Route::get('ControlPanel/ExercisesTypes', [ExercisestypesController::class, '_index']);
    Route::get('ControlPanel/ExercisesTypes/{user}', [ExercisestypesController::class, '_show']);
    Route::delete('ControlPanel/ExercisesTypes/{user}', [ExercisestypesController::class, '_destroy']);
    Route::post('ControlPanel/ExercisesTypes', [ExercisestypesController::class, '_ApiList']);
    Route::post('ControlPanel/ExercisesTypes/AddEdit/', [ExercisestypesController::class, '_AddEdit']);

    // User Logos
    Route::get('ControlPanel/UserLogos', [UserLogosController::class, '_index']);
    Route::get('ControlPanel/UserLogos/{user}', [UserLogosController::class, '_show']);
    Route::delete('ControlPanel/UserLogos/{user}', [UserLogosController::class, '_destroy']);
    Route::post('ControlPanel/UserLogos', [UserLogosController::class, '_ApiList']);
    Route::post('ControlPanel/UserLogos/AddEdit/', [UserLogosController::class, '_AddEdit']);
    Route::post('UserLogos/Rotate/Right', [UserLogosController::class, 'rotateRight']);
    Route::post('UserLogos/Rotate/Left', [UserLogosController::class, 'rotateLeft']);

    // Groups
    Route::get('ControlPanel/Groups', [GroupsController::class, '_index']);
    Route::get('ControlPanel/Groups/{user}', [GroupsController::class, '_show']);
    Route::delete('ControlPanel/Groups/{user}', [GroupsController::class, '_destroy']);
    Route::post('ControlPanel/Groups', [GroupsController::class, '_ApiList']);
    Route::post('ControlPanel/Groups/AddEdit/', [GroupsController::class, '_AddEdit']);

    // User Groups
    Route::get('ControlPanel/UserGroups', [UserGroupsController::class, '_index']);
    Route::get('ControlPanel/UserGroups/{user}', [UserGroupsController::class, '_show']);
    Route::delete('ControlPanel/UserGroups/{user}', [UserGroupsController::class, '_destroy']);
    Route::post('ControlPanel/UserGroups', [UserGroupsController::class, '_ApiList']);
    Route::post('ControlPanel/UserGroups/AddEdit/', [UserGroupsController::class, '_AddEdit']);

    // Memberships
    Route::get('ControlPanel/Memberships', [MembershipsController::class, '_indexUsers']);
    Route::get('ControlPanel/Memberships/{user}', [MembershipsController::class, '_showUsers']);
    Route::delete('ControlPanel/Memberships/{user}', [MembershipsController::class, '_destroyUsers']);
    Route::post('ControlPanel/Memberships', [MembershipsController::class, '_ApiListUsers']);
    Route::post('ControlPanel/Memberships/AddEdit/', [MembershipsController::class, '_AddEditUsers']);

    // Membership Types
    Route::get('ControlPanel/MembershipsTypes', [MembershipsController::class, '_index']);
    Route::get('ControlPanel/MembershipsTypes/{user}', [MembershipsController::class, '_show']);
    Route::delete('ControlPanel/MembershipsTypes/{user}', [MembershipsController::class, '_destroy']);
    Route::post('ControlPanel/MembershipsTypes', [MembershipsController::class, '_ApiList']);
    Route::post('ControlPanel/MembershipsTypes/AddEdit/', [MembershipsController::class, '_AddEdit']);

    Route::get('ControlPanel/MaintenanceScripts', [SystemController::class, '_indexScripts']);

    Route::get('ControlPanel/StripeSync', [SystemController::class, 'syncWithStripeAndCheckMemberships']);
    Route::post('ControlPanel/removeUserFromDatabase', [SystemController::class, 'removeUserFromDatabase']);
    Route::post('ControlPanel/workoutsToRestore', [SystemController::class, 'workoutsToRestore']);

    Route::get('ControlPanel/managerExercises/{search}', [ExercisesController::class, 'managerExercises']);
    Route::post('ControlPanel/managerExercises/{search}', [ExercisesController::class, 'managerExercises']);
    Route::post('ControlPanel/exercises', [ExercisesController::class, 'controlPanelExercises']);
    Route::get('ControlPanel/errors', [ControlPanelController::class, 'indexErrors'])->name('ControlPanelErrors');

    Route::post('ControlPanel/fixExercisesTranslations', [SystemController::class, 'fixExercisesTranslations']);
    Route::post('ControlPanel/fixUsedExercises', [SystemController::class, 'fixUsedExercises']);

    Route::get('ControlPanel/migrateWorkouts', [SystemController::class, 'migrateWorkouts']);
    Route::get('ControlPanel/migrateWorkouts/{param1}', [SystemController::class, 'migrateWorkouts']);
    Route::get('ControlPanel/errors/reset', [ControlPanelController::class, 'indexErrorsReset']);

    Route::get('ControlPanel/migrateworkoutFromUserToUser/{param1}/{param2}', [SystemController::class, 'migrateWorkout']);
});

// CRON JOBS
Route::get('ControlPanel/dailyActivity', [SystemController::class, 'dailyActivity']);
Route::get('ControlPanel/Email/Feeds', [FeedsController::class, 'ControlPanelFeeds']);
Route::get('ControlPanel/Clients/sendTrainerClientWorkoutRevision', [ClientsController::class, 'sendTrainerClientWorkoutRevision']);

// STATIC SITE
// TRAINEE
Route::get('TraineeSignUp/{key}', [UsersController::class, 'TraineeInvite'])->name('TraineeSignUp');
Route::post('Trainee/SignUp', [UsersController::class, 'TraineeSignUp'])->name('TraineeSignUpPost');

Route::get('trainee/signup', function () {
    return view('trainee.signUp');
});

// TRAINER
Route::get('TrainerSignUp/Workout/{key}', [UsersController::class, 'TrainerInviteWithWorkout']);
Route::get('TrainerSignUp/{key}', [UsersController::class, 'TrainerInvite']);
Route::get('trainerGetStartedPaid', [UsersController::class, 'trainerGetStartedPaid'])->name('TrainerSignUp');
Route::get('TrainerSignUp', [UsersController::class, 'trainerGetStarted'])->name('TrainerSignUp');
Route::post('Trainer/SignUp', [UsersController::class, 'TrainerFreeTrialSignUp'])->name('TrainerSignUpPost');

Route::get('Gym', [UsersController::class, 'gym'])->middleware('guest')->name('TrainerSignUpPost');
Route::get('GymSignUp', [UsersController::class, 'gymSignUp'])->name('TrainerSignUpPost');
Route::post('SignUp', [UsersController::class, 'demoSignUp']);
Route::get('UpgradePlan', [OrdersController::class, 'upgradePlan'])->name('cartUpgradePlan');
Route::get('/thanksss', function () { return view('Store.thankYou'); })->name('Price');



Route::get('TermsAndConditions', function () {
    return view('TermsAndConditions');
})->name('TermsAndConditions');

Route::get('PrivacyPolicy', function () {
    return view('PrivacyPolicy');
})->name('PrivacyPolicy');

Route::get('/', function () {
    return view(Helper::translateOverride('index'));
})->middleware('guest')->name('home');

// Event handling
Route::post('/events/postEvent', function (Request $request) {
    return Event::dispatch('jsTriggeredEvent', [$request->get('eventName'), $request->get('metas')]);
})->middleware('auth');


Route::controller(SocialOAuthController::class)->group(function(){
    Route::get('auth/google/', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback/', 'handleGoogleCallback')->name('auth.google-callback');
});



// ROUTES WITH ERRORS
Route::get('translationtest', function () { return view('translation'); }); // VIEW NOT FOUND
Route::get('FreeTrialSignin', function () { return view('freeTrialSignin'); })->name('freeTrialSignin'); // VIEW NOT FOUND
Route::get('WorkoutBuilderPrice', function () { return view('price'); })->name('Price'); // VIEW NOT FOUND
Route::get('/MobileGetStarted', function () { return view('MobileGetStarted'); })->name('MobileGetStarted'); // VIEW NOT FOUND