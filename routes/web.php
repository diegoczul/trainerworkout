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

//Route::middleware(['guest'])->group(function () {

    // Pay Page
    Route::get(__('routes./payment/{package}'), [OrdersController::class, 'indexPaypage']);
    Route::post(__('routes./payment/{package}'), [OrdersController::class, 'processPaymentNoLogin']);
    Route::get(__('routes./payment'), [OrdersController::class, 'indexPaypage']);
    Route::post(__('routes./payment'), [OrdersController::class, 'processPaymentNoLogin']);
    Route::get(__('routes./thankyou'), [OrdersController::class, 'thankyou'])->name('thankyouPayment');

    Route::get('/user/confirmation/{param1}', [UsersController::class, 'confirmEmail'])->name('confirmEmail');

    Route::get('/phpini', function (){ echo phpinfo(); });
    Route::get('/logs/{folder_name}/{dd}/{mm}/{yyyy}', function ($folder_name, $dd, $mm, $yyyy){
        if (file_exists(storage_path("logs/$folder_name/laravel-$yyyy-$mm-$dd.log"))) {
            $file = file_get_contents(storage_path("logs/$folder_name/laravel-$yyyy-$mm-$dd.log"));
            echo "<pre>$file</pre>";
            die;
        }else{
            echo("FILE NOT FOUND");
        }
    });

    // WEB ROUTES
    Route::get('/', function () {
        return view(Helper::translateOverride('index'));
    });

    //|--------------------------------------------------------------------------
    //| GENERAL ROUTES
    //|--------------------------------------------------------------------------

    // LOGIN VIEW
    Route::get(__('routes./login'), fn () => view(Helper::translateOverride('login')))->name('login')->middleware('guest');
    Route::get(__('routes./logout'), [UsersController::class, 'logout']);
    Route::delete(__('routes./delete-account').'/{user}', [UsersController::class, 'destroy']);
    Route::post(__('routes./login'), [UsersController::class, 'login']);
    Route::get('login-with-email', [UsersController::class, 'loginWithEmail'])->name('login-with-email');
    Route::post(__('routes./registerNewsletter'), [UsersController::class, 'registerNewsletter']);
    Route::get(__('routes./login/facebook'), [UsersController::class, 'loginFacebook']);
    Route::get(__('routes./login/trainee/facebook').'/{param1}', [UsersController::class, 'loginTraineeFacebook']);
    Route::get(__('routes./login/trainee/facebook'), [UsersController::class, 'loginTraineeFacebook']);
    Route::get(__('routes./password/reset'), [RemindersController::class, 'getRemind'])->name('password.remind');
    Route::post(__('routes./password/reset'), [RemindersController::class, 'postRemind'])->name('password.request');
    Route::get(__('routes./password/reset').'/{token}', [RemindersController::class, 'getReset'])->name('password.reset');
    Route::post(__('routes.password/reset').'/{token}', [RemindersController::class, 'postReset'])->name('password.update');

    // CLIENTS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/clients'), [ClientsController::class, 'index']);
        Route::post(__('routes./widgets/clients/full'), [ClientsController::class, 'indexFull']);
        Route::get(__('routes./widgets/clients'), [ClientsController::class, 'index']);
        Route::get(__('routes./widgets/clients/').'{param}', [ClientsController::class, 'show']);
        Route::post(__('routes./widgets/clients/addEdit/'), [ClientsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/clients/').'{param}', [ClientsController::class, 'destroy']);
        Route::post(__('routes./Trainer/addClient'), [ClientsController::class, 'addClient'])->name('ProfileTrainer');
        Route::post(__('routes./Trainer/addClientWithId'), [ClientsController::class, 'addClientWithId']);
        Route::get(__('routes./Client/').'{id}/{username}', [ClientsController::class, 'clientProfile']);
        Route::get(__('routes./Client/').'{id}', [ClientsController::class, 'clientProfile']);
        Route::get(__('routes./Trainer/Clients'), [ClientsController::class, 'showClients'])->name('TrainerClients');
        Route::post(__('routes./Clients/AddClient'), [ClientsController::class, 'addClientTrainer']);
        Route::post(__('routes./Clients/ModifyClient'), [ClientsController::class, 'modifyClient']);
        Route::post(__('routes./Clients/subscribe/toggle'), [ClientsController::class, 'subscribeClient']);
    });

    // No Authentication Required
    Route::get(__('routes./Clients/Invitation/').'{invite}', [ClientsController::class, 'confirmClientByInvitation']);

// REPORTS
    Route::middleware('auth')->group(function () {
        Route::post('/reports/workoutsPerformance', [WorkoutsPerformanceController::class, 'workoutsPerformance']);
        Route::get('/reports/workoutsPerformanceDetail', [WorkoutsPerformanceController::class, 'workoutsPerformanceDetail']);
    });

// WIDGETS: WORKOUTS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/workouts/archive/').'{param}', [WorkoutsController::class, 'archiveWorkout']);
        Route::post(__('routes./widgets/workouts/unarchive/').'{param}', [WorkoutsController::class, 'unarchiveWorkout']);
        Route::get(__('routes./widgets/workouts/').'{param}', [WorkoutsController::class, 'show']);
        Route::post(__('routes./widgets/workouts/addEdit/'), [WorkoutsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/workouts/').'{param}', [WorkoutsController::class, 'destroy']);
        Route::post(__('routes./widgets/workoutsTrainer'), [WorkoutsController::class, 'indexWorkoutTrainer']);
        Route::post(__('routes./widgets/workoutsClient'), [WorkoutsController::class, 'indexWorkoutsClient']);
        Route::post(__('routes./widgets/workoutsLibrary'), [WorkoutsController::class, 'indexWorkoutsLibrary']);
        Route::post(__('routes./widgets/workoutsTrainer/full'), [WorkoutsController::class, 'indexWorkoutTrainerFull']);
        Route::get(__('routes./widgets/workoutsTrainee').'/{param}', [WorkoutsController::class, 'show']);
        Route::post(__('routes./widgets/workoutsTrainee/addEdit/'), [WorkoutsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/workoutsTrainee').'/{param}', [WorkoutsController::class, 'destroy']);
        Route::post(__('routes./widgets/workoutsTrainee'), [WorkoutsController::class, 'indexWorkoutTrainee']);
        Route::post(__('routes./widgets/workoutsTrainee/full'), [WorkoutsController::class, 'indexWorkoutTraineeFull']);
    });

// WORKOUT MARKET
    Route::middleware('auth')->group(function () {
        Route::get(__('routes./WorkoutMarket'), [WorkoutsController::class, 'indexMarket']);
        Route::get(__('routes./widgets/workoutMarket/'.'{param}'), [WorkoutsController::class, 'showWorkoutMarket']);
        Route::post(__('routes./widgets/workoutMarket'), [WorkoutsController::class, 'indexWorkoutMarket']);
        Route::post(__('routes./widgets/workoutMarket/full'), [WorkoutsController::class, 'indexWorkoutMarketFull']);
        Route::post(__('routes./Workouts/Search'), [WorkoutsController::class, 'searchWorkout']);
        Route::get(__('routes./Workouts/Client/').'{param1}/{param2}', [WorkoutsController::class, 'clientWorkouts']);
        Route::get(__('routes./Workouts/Client/').'{param1}', [WorkoutsController::class, 'clientWorkouts']);
        Route::get('/Client/editWorkout/{param1}/{param2}', [WorkoutsController::class, 'assignWorkoutToClientEdit']);
        Route::get('/Client/AssignWorkout/{param1}/{param2}', [WorkoutsController::class, 'assignWorkoutToClient']);
    });

// WORKOUT PREVIEW
    Route::get(__('routes./Workout/Preview/').'{workoutid}/{workoutName}/{workoutAuthor}', [WorkoutsController::class, 'previewWorkout']);
    Route::get(__('routes./Workout/Preview/').'{workoutid}/{workoutName}', [WorkoutsController::class, 'previewWorkout']);
    Route::get(__('routes./Workout/Preview/').'{workoutid}', [WorkoutsController::class, 'previewWorkout']);

// WORD WIDGETS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes/widgets/videoWord'), [UsersController::class, 'indexVideoWord']);
        Route::post(__('routes./widgets/videoWord/full'), [UsersController::class, 'indexVideoWordFull']);
        Route::get(__('routes./widgets/videoWord'), [UsersController::class, 'index']);
        Route::get(__('routes./widgets/videoWord/{param}'), [UsersController::class, 'show']);
        Route::post(__('routes./widgets/videoWord/addEdit'), [UsersController::class, 'AddEditVideoWord']);
    });

// REMINDERS AND TASKS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/tasks'), [TasksController::class, 'index']);
        Route::post(__('routes./widgets/tasks/completeTask'), [TasksController::class, 'completeTask']);
        Route::get(__('routes./widgets/tasks'), [TasksController::class, 'index']);
        Route::get(__('routes./widgets/tasks/{param}'), [TasksController::class, 'show']);
        Route::post(__('routes./widgets/tasks/addEdit'), [TasksController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/tasks/{param}'), [TasksController::class, 'destroy']);
    });

// WORKOUT SALES
    Route::post(__('routes./widgets/workoutSales/'), [WorkoutsController::class, 'workoutSales']);

// APPOINTMENTS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/appointments'), [AppointmentsController::class, 'index']);
        Route::post(__('routes./widgets/appointments/full'), [AppointmentsController::class, 'indexFull']);
        Route::get(__('routes./widgets/appointments'), [AppointmentsController::class, 'index']);
        Route::get(__('routes./widgets/appointments/')."{param}", [AppointmentsController::class, 'show']);
        Route::post(__('routes./widgets/appointments/addEdit/'), [AppointmentsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/appointments/destroy'), [AppointmentsController::class, 'destroy']);
    });

// AVAILABILITIES
    Route::middleware('auth')->group(function () {
        Route::get(__('routes./widgets/availabilities/addEntry/')."{start}/{end}", [AvailabilitiesController::class, 'addEntry']);
        Route::post(__('routes./widgets/availabilities/addEdit/'), [AvailabilitiesController::class, 'AddEdit']);
        Route::post(__('routes./widgets/availabilities/getCalendar'), [AvailabilitiesController::class, 'getCalendar']);
        Route::post(__('routes./widgets/availabilities/updateEvent'), [AvailabilitiesController::class, 'updateEvent']);
    });

// CALENDAR
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/calendar'), [CalendarController::class, 'index']);
        Route::post(__('routes./widgets/calendar/full'), [CalendarController::class, 'indexFull']);
        Route::get(__('routes./widgets/calendar'), [CalendarController::class, 'index']);
        Route::get(__('routes./widgets/calendar/')."{param}", [CalendarController::class, 'show']);
        Route::post(__('routes./widgets/calendar/addEdit/'), [CalendarController::class, 'AddEdit']);
    });

// ACTIVITY CALENDAR
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/calendarActivity'), [CalendarController::class, 'index']);
        Route::post(__('routes./widgets/calendarActivity/full'), [CalendarController::class, 'indexFull']);
        Route::get(__('routes./widgets/calendarActivity'), [CalendarController::class, 'index']);
        Route::get(__('routes./widgets/calendarActivity/')."{param}", [CalendarController::class, 'show']);
        Route::post(__('routes./widgets/calendarActivity/addEdit/'), [CalendarController::class, 'AddEdit']);
    });

// BIOGRAPHY
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/biography'), [UsersController::class, 'indexBio']);
        Route::post(__('routes./widgets/biography/full'), [UsersController::class, 'indexBioFull']);
        Route::get(__('routes./widgets/biography'), [UsersController::class, 'index']);
        Route::get(__('routes./widgets/biography/')."{param}", [UsersController::class, 'show']);
        Route::post(__('routes./widgets/biography/addEdit/'), [UsersController::class, 'AddEditBio']);
    });

// TESTIMONIALS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/testimonials'), [TestimonialsController::class, 'index']);
        Route::post(__('routes./widgets/testimonials/full'), [TestimonialsController::class, 'indexFull']);
        Route::post(__('routes./widgets/testimonials/status'), [TestimonialsController::class, 'approveTestimonial']);
        Route::get(__('routes./widgets/testimonials'), [TestimonialsController::class, 'index']);
        Route::get(__('routes./widgets/testimonials/')."{param}", [TestimonialsController::class, 'show']);
        Route::post(__('routes./widgets/testimonials/addEdit/'), [TestimonialsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/testimonials/')."{param}", [TestimonialsController::class, 'destroy']);
    });

// CLIENTS FEED
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/clientsFeed'), [FeedsController::class, 'indexClients']);
        Route::post(__('routes./widgets/clientsFeed/full'), [FeedsController::class, 'indexClientsFull']);
        Route::get(__('routes./widgets/clientsFeed/Archive/')."{param}/{param1}", [FeedsController::class, 'archive']);
        Route::get(__('routes./widgets/clientsFeed'), [ClientsController::class, 'indexClient']);
        Route::get(__('routes./widgets/clientsFeed/')."{param}", [ClientsController::class, 'showClient']);
        Route::post(__('routes./widgets/clientsFeed/addEdit/'), [ClientsController::class, 'AddEditClient']);
        Route::delete(__('routes./widgets/clientsFeed/')."{param}", [ClientsController::class, 'destroy']);
    });

// CLIENT FEED
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/clientFeed'), [FeedsController::class, 'indexClient']);
        Route::post(__('routes./widgets/clientFeed/full'), [FeedsController::class, 'indexClientFull']);
        Route::get(__('routes./widgets/clientFeed'), [ClientsController::class, 'indexClient']);
        Route::get(__('routes./widgets/clientFeed/')."{param}", [ClientsController::class, 'showClient']);
        Route::get(__('/Clients/list/emails'), [ClientsController::class, 'showClientList']);
        Route::post(__('routes./widgets/clientFeed/addEdit/'), [ClientsController::class, 'AddEditClient']);
        Route::delete(__('routes./widgets/clientFeed/')."{param}", [ClientsController::class, 'destroy']);
    });

// WEIGHT
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/weight'), [WeightsController::class, 'index']);
        Route::post(__('routes./widgets/weight/full'), [WeightsController::class, 'indexFull']);
        Route::get(__('routes./widgets/weight'), [WeightsController::class, 'index']);
        Route::get(__('routes./widgets/weight/')."{param}", [WeightsController::class, 'show']);
        Route::post(__('routes./widgets/weight/addEdit/'), [WeightsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/weight/')."{param}", [WeightsController::class, 'destroy']);
    });

// MEASUREMENTS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/measurements'), [MeasurementsController::class, 'index']);
        Route::post(__('routes./widgets/measurements/full'), [MeasurementsController::class, 'indexFull']);
        Route::get(__('routes./widgets/measurements'), [MeasurementsController::class, 'index']);
        Route::get(__('routes./widgets/measurements/')."{param}", [MeasurementsController::class, 'show']);
        Route::post(__('routes./widgets/measurements/addEdit/'), [MeasurementsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/measurements/')."{param}", [MeasurementsController::class, 'destroy']);
    });

// PICTURES
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/pictures'), [PicturesController::class, 'index']);
        Route::post(__('routes./widgets/pictures/full'), [PicturesController::class, 'indexFull']);
        Route::get(__('routes./widgets/pictures'), [PicturesController::class, 'index']);
        Route::get(__('routes./widgets/pictures/')."{param}", [PicturesController::class, 'show']);
        Route::post(__('routes./widgets/pictures/addEdit/'), [PicturesController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/pictures/')."{param}", [PicturesController::class, 'destroy']);
    });

// OBJECTIVES
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/objectives'), [ObjectivesController::class, 'index']);
        Route::post(__('routes./widgets/objectives/full'), [ObjectivesController::class, 'indexFull']);
        Route::get(__('routes./widgets/objectives'), [ObjectivesController::class, 'index']);
        Route::get(__('routes./widgets/objectives/')."{param}", [ObjectivesController::class, 'show']);
        Route::post(__('routes./widgets/objectives/addEdit/'), [ObjectivesController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/objectives/')."{param}", [ObjectivesController::class, 'destroy']);
    });

// TAGS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/tags/removeTag'), [TagsController::class, 'destroyTagWorkout']);
        Route::post(__('routes./widgets/tags'), [TagsController::class, 'index']);
        Route::post(__('routes./widgets/tagsWorkout'), [TagsController::class, 'indexWorkout']);
        Route::post(__('routes./widgets/tags/full'), [TagsController::class, 'indexFull']);
        Route::get(__('routes./widgets/tags'), [TagsController::class, 'index']);
        Route::get(__('routes./widgets/tags/')."{param}", [TagsController::class, 'show']);
        Route::post(__('routes./widgets/tags/addEdit/'), [TagsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/tags/')."{param}", [TagsController::class, 'destroy']);
    });

// FRIENDS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/friends'), [FriendsController::class, 'index']);
        Route::post(__('routes./widgets/friends/full'), [FriendsController::class, 'indexFull']);
        Route::get(__('routes./widgets/friends'), [FriendsController::class, 'index']);
        Route::get(__('routes./widgets/friends/suggest'), [FriendsController::class, 'indexSuggest']);
        Route::post(__('routes./widgets/friends/addEdit/'), [FriendsController::class, 'addFriend']);
        Route::delete(__('routes./widgets/friends/')."{param}", [FriendsController::class, 'destroy']);
    });

// SESSIONS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/sessions'), [SessionsController::class, 'index']);
        Route::post(__('routes./widgets/sessions/full'), [SessionsController::class, 'indexFull']);
        Route::get(__('routes./widgets/sessions'), [SessionsController::class, 'index']);
        Route::get(__('routes./widgets/sessions/')."{param}", [SessionsController::class, 'show']);
        Route::post(__('routes./widgets/sessions/addEdit/'), [SessionsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/sessions/')."{param}", [SessionsController::class, 'destroy']);
    });

// EXERCISES
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/exercises'), [ExercisesController::class, 'index']);
        Route::post(__('routes./widgets/exercises/full'), [ExercisesController::class, 'indexFull']);
        Route::get(__('routes./widgets/exercises'), [ExercisesController::class, 'index']);
        Route::post(__('routes./widgets/exercises/addEdit'), [ExercisesController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/exercises/').'{param}', [ExercisesController::class, 'destroy']);
    });

// NOTIFICATIONS
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/notifications'), [NotificationsController::class, 'index']);
        Route::post(__('routes./widgets/notificationsRead'), [NotificationsController::class, 'readNotifications']);
        Route::post(__('routes./widgets/notifications/full'), [NotificationsController::class, 'indexFull']);
        Route::get(__('routes./widgets/notifications'), [NotificationsController::class, 'index']);
        Route::get(__('routes./widgets/notifications/{param}'), [NotificationsController::class, 'show']);
        Route::post(__('routes./widgets/notifications/addEdit'), [NotificationsController::class, 'AddEdit']);
        Route::delete(__('routes./widgets/notifications/{param}'), [NotificationsController::class, 'destroy']);
    });

// MESSAGES
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./widgets/messages'), [UserMessagesController::class, 'index']);
        Route::post(__('routes./widgets/messages/readUserMessages'), [UserMessagesController::class, 'readUserMessages']);
        Route::post(__('routes./widgets/messages/full'), [UserMessagesController::class, 'indexFull']);
        Route::get(__('routes./widgets/messages'), [UserMessagesController::class, 'index']);
        Route::get(__('routes./widgets/messages/dialog/{param}'), [UserMessagesController::class, 'dialog']);
        Route::post(__('routes./widgets/messages/addEdit'), [UserMessagesController::class, 'AddEdit']);
        Route::get(__('routes./widgets/messages/{param}'), [UserMessagesController::class, 'show']);
        Route::delete(__('routes./widgets/messages/{param}'), [UserMessagesController::class, 'destroy']);
    });

// TRAINER REPORTS
    Route::middleware('auth')->group(function () {
        Route::get(__('routes./Trainer/Reports/WorkoutsPerformanceClients'), [WorkoutsPerformanceController::class, 'workoutsPerformanceClientsIndex'])->name('WorkoutsPerformanceClients');
//        Route::get(__('routes./Trainer/Reports/WorkoutsPerformanceClients', [], 'fr'), [WorkoutsPerformanceController::class, 'workoutsPerformanceClientsIndex']);
    });

// ONBOARDING
    Route::middleware('auth')->group(function () {
        Route::post(__('routes./onboarding/message/').'{param1}', [OnBoardingController::class, 'messageChat']);
        Route::get(__('routes./Trainer/onBoarding/skipDemo'), [OnBoardingController::class, 'skipDemo']);
        Route::get(__('routes./Trainer/onBoarding/start'), [OnBoardingController::class, 'start']);
        Route::get(__('routes./Trainer/onBoarding/stop'), [OnBoardingController::class, 'skipDemo']);
        Route::get(__('routes./Trainer/onBoarding/step1'), [OnBoardingController::class, 'step1']);
        Route::get(__('routes./Trainer/onBoarding/step2'), [OnBoardingController::class, 'step2']);
        Route::get(__('routes./Trainer/onBoarding/step21'), [OnBoardingController::class, 'step21']);
        Route::get(__('routes./Trainer/onBoarding/step22'), [OnBoardingController::class, 'step22']);
        Route::get(__('routes./Trainer/onBoarding/step23'), [OnBoardingController::class, 'step23']);
        Route::get(__('routes./Trainer/onBoarding/step3'), [OnBoardingController::class, 'step3']);
        Route::get(__('routes./Trainer/onBoarding/step4'), [OnBoardingController::class, 'step4']);
        Route::get(__('routes./Trainer/onBoarding/step41'), [OnBoardingController::class, 'step41']);
        Route::get(__('routes./Trainer/onBoarding/step5'), [OnBoardingController::class, 'step5']);
        Route::get(__('routes./Trainer/onBoarding/step6'), [OnBoardingController::class, 'step6']);
        Route::get(__('routes./Trainer/onBoarding/step7'), [OnBoardingController::class, 'step7']);
        Route::get(__('routes./Trainer/onBoarding/step8'), [OnBoardingController::class, 'step8']);
    });

// WORKOUT
    Route::middleware(['auth', 'userTypeChecker'])->group(function () {
        Route::get(__('routes./Trainer/CreateWorkout').'/{param}', [WorkoutsController::class, 'createNewWorkoutTrainer']);
        Route::get(__('routes./Trainer/CreateWorkout'), [WorkoutsController::class, 'createNewWorkoutTrainer'])->name('trainerCreateWorkout');
        Route::post(__('routes./Trainer/autoSaveWorkout'), [WorkoutsController::class, 'autoSaveWorkout']);
        Route::get(__('routes./Trainer/CreateWorkout2'), [WorkoutsController::class, 'createNewWorkoutTrainer']);
        Route::get(__('routes./Trainer/Workouts').'/{userName?}', [WorkoutsController::class, 'indexWorkoutsTrainer'])->name('trainerWorkouts');
        Route::get(__('routes./Trainer/Workouts'), [WorkoutsController::class, 'indexWorkoutsTrainer'])->name('trainerWorkouts2');
        Route::post(__('routes./Trainer/CreateWorkout'), [WorkoutsController::class, 'createNewWorkoutAddEditTrainer']);
        Route::post(__('routes./Trainer/CreateWorkout/').'{param1}', [WorkoutsController::class, 'createNewWorkoutAddEditTrainer']);
        Route::get(__('routes./Trainer/CreateWorkout/Client/').'{param}', [WorkoutsController::class, 'createNewWorkoutTrainerToClient']);
    });

    Route::middleware('auth')->group(function () {
        Route::get(__('routes./Workouts/removeWorkout/').'{id}', [WorkoutsController::class, 'deleteWorkout']);
        Route::post(__('routes./widgets/workouts'), [WorkoutsController::class, 'index']);
        Route::post(__('routes./widgets/workouts_create'), [WorkoutsController::class, 'indexCreate']);
        Route::post(__('routes./widgets/trendingWorkouts'), [WorkoutsController::class, 'indexTrendingWorkouts']);
        Route::post(__('routes./widgets/workouts/full'), [WorkoutsController::class, 'indexFull']);
        Route::get(__('routes./widgets/workouts'), [WorkoutsController::class, 'index']);
    });

// USERS
    Route::middleware('auth')->group(function () {
        Route::get(__('routes./widgets/people/suggest'), [UsersController::class, 'indexSuggestPeople']);
        Route::get(__('routes./widgets/people/suggestWithClient'), [UsersController::class, 'indexSuggestPeopleWithClients']);
    });


// Workout
    Route::post(__('routes./Workout/Performance/discartOldPerformance'), [WorkoutsController::class, 'discartOldPerformance']);
    Route::post(__('routes./Workout/Performance/saveProgressPerformance'), [WorkoutsController::class, 'saveProgressPerformance']);
    Route::post(__('routes./Workout/Performance/Start'), [WorkoutsController::class, 'startWorkoutPerformance']);
    Route::get(__('routes./Workout/Performance/showDetails') . "/{param}", [WorkoutsPerformanceController::class, 'workoutsPerformanceDetail']);
    Route::post(__('routes./Workout/Performance'), [WorkoutsController::class, 'performWorkout']);
    Route::get(__('routes./WorkoutPDF/') . "{id}/{name}/{author}", [WorkoutsController::class, 'viewWorkoutPDF']);
    Route::get(__('routes./WorkoutImage/') . "{id}/{name}/{author}", [WorkoutsController::class, 'viewWorkoutImage']);
    Route::get(__('routes./Workout/Edit/') . "{id}", [WorkoutsController::class, 'editWorkout'])->middleware('auth');
    Route::post(__('routes./Workout/Duplicate'), [WorkoutsController::class, 'duplicateWorkout'])->middleware('auth');
    Route::post(__('routes./Workout/Edit/'), [WorkoutsController::class, 'saveEditWorkout'])->middleware('auth');
    Route::post(__('routes./Workout/Duplicate/'), [WorkoutsController::class, 'duplicateWorkout'])->middleware('auth');
    Route::post(__('routes./Workout/saveSingleSet'), [WorkoutsController::class, 'saveSingleSet'])->middleware('auth');
    Route::post(__('routes./Workout/saveAllSets'), [WorkoutsController::class, 'saveAllSets'])->middleware('auth');
    Route::post(__('routes./Workout/addSets'), [WorkoutsController::class, 'addSets'])->middleware('auth');
    Route::post(__('routes./Workout/addSetsWithTable'), [WorkoutsController::class, 'addSetsReturnTable'])->middleware('auth');
    Route::post(__('routes./Workout/saveAllAddNewSets'), [WorkoutsController::class, 'saveAllAddNewSets'])->middleware('auth');
    Route::post(__('routes./Workout/exerciseCompleted'), [WorkoutsController::class, 'exerciseCompleted'])->middleware('auth');
    Route::post(__('routes./Workout/workoutCompleted'), [WorkoutsController::class, 'workoutCompleted'])->middleware('auth');
    Route::get(__('routes./Workout/ShareWorkout/') . "{workout}", [WorkoutsController::class, 'shareWorkoutIndex'])->middleware('auth');
    Route::get(__('routes./Workout/ShareWorkouts/'), [WorkoutsController::class, 'shareWorkoutIndex'])->middleware('auth');
    Route::get(__('routes./Workout/PrintWorkout/') . "{workout}", [WorkoutsController::class, 'PrintWorkoutPDF'])->middleware('auth');
    Route::get(__('routes./Workout/PrintWorkout/') . "android/{workout}", [WorkoutsController::class, 'PrintWorkoutAndroidPDF'])->middleware('auth');
    Route::get(__('routes./Workout/PrintWorkouts/') . "{workout}", [WorkoutsController::class, 'PrintWorkouts'])->middleware('auth');
    Route::get(__('routes./Workout/PrintWorkoutInternal/') . "{workout}", [WorkoutsController::class, 'PrintWorkout']);
    Route::get(__('routes./Workout/PrintWorkoutInternal/') . "{workout}/{locale}", [WorkoutsController::class, 'PrintWorkout']);
    Route::post(__('routes./Workout/subscribe/toggle'), [WorkoutsController::class, 'subscribeTrainer'])->middleware('auth');
    Route::post(__('routes./Workout/ShareByEmail'), [WorkoutsController::class, 'ShareByEmail'])->middleware('auth');
    Route::post(__('routes./Workout/ShareByUser/'), [WorkoutsController::class, 'ShareByUser'])->middleware('auth');
    Route::post(__('routes./Workout/ShareByLink/'), [WorkoutsController::class, 'ShareByLink'])->middleware('auth');
    Route::get(__('routes./Workout/AddToMyWorkouts/') . "{workout}", [WorkoutsController::class, 'AddToMyWorkouts'])->middleware('auth');
    Route::get(__('routes./Workout/exercisePerformance/') . "{workoutexercise}", [WorkoutsController::class, 'exercisePerformance'])->middleware('auth');
    Route::post(__('routes./Workout/addCustomPicture/'), [WorkoutsController::class, 'addCustomPicture'])->middleware('auth');
    Route::post(__('routes./Workout/unit/update'), [WorkoutsController::class, 'updateUnitExerciseGroup'])->middleware('auth');
    Route::get(__('routes./Workout/') . "{id}/{name}/{author}", [WorkoutsController::class, 'viewWorkout'])->middleware('auth');
    Route::get(__('routes./Workout/') . "{id}/{author}", [WorkoutsController::class, 'viewWorkoutNoName'])->middleware('auth');
    Route::get(__('routes./Workout/') . "{id}//{author}", [WorkoutsController::class, 'viewWorkoutNoName'])->middleware('auth');
    Route::get("Trainee/" . __('routes.Workout/') . "{id}/{name}/{author}", [WorkoutsController::class, 'viewWorkoutTrainee'])->middleware('auth');
    Route::get("Trainee/" . __('routes.Workout/') . "{id}//{author}", [WorkoutsController::class, 'viewWorkoutTrainee'])->middleware('auth');
    Route::get(__('routes./editWorkout/') . "{id}/{name}/{author}/{client}", [WorkoutsController::class, 'editWorkout'])->middleware('auth');
    Route::get(__('routes./editWorkout/') . "{id}/{name}/{author}", [WorkoutsController::class, 'editWorkout'])->middleware('auth');
    Route::get(__('routes./editWorkout/') . "{id}//{author}", [WorkoutsController::class, 'editWorkout'])->middleware('auth');
    Route::get(__('routes./editWorkout/') . "{id}", [WorkoutsController::class, 'editWorkout'])->middleware('auth');
    Route::get(__('routes./Workouts/createUserDownload') . "/{workouts}/{param1}", [WorkoutsController::class, 'createUserDownload'])->middleware('auth');
    Route::get(__('routes./Workouts/createUserDownload') . "/{workouts}/{param1}/{param2}", [WorkoutsController::class, 'createUserDownload'])->middleware('auth');
    Route::get(__('routes./Workouts/addWorkoutToClient') . "/{param1}/{param2}", [WorkoutsController::class, 'addToWorkoutClient'])->middleware('auth');

// THIS CANNOT BE TRANSLATED
    Route::get("/WorkoutInternal/{id}/{locale}/{name}/{author}", [WorkoutsController::class, 'viewWorkoutInternal']);
    Route::get("/WorkoutInternal/{id}/{locale}//{author}", [WorkoutsController::class, 'viewWorkoutInternal']);
    Route::get("/WorkoutInternal/{id}/{locale}/{author}", [WorkoutsController::class, 'viewWorkoutInternal']);

// SHARING A WORKOUT
    Route::get(__('routes./Share/Workout/Accept/') . "{link}", [WorkoutsController::class, 'acceptWorkoutBySharingLink']);
    Route::get(__('routes./Share/Workout/') . "{link}", [WorkoutsController::class, 'openWorkoutBySharingLink'])->name('shareWorkout');
    Route::post(__('routes./Share/Facebook'), [UsersController::class, 'shareOnFacebook']);

// FRIENDS
    Route::post(__('routes./Friends/Add'), [FriendsController::class, 'addFriend'])->middleware('auth');
    Route::post(__('routes./Friends/Search'), [FriendsController::class, 'searchFriend'])->middleware('auth');
    Route::get(__('routes./Trainer/Friends'), [FriendsController::class, 'indexFriendsTrainer'])->middleware('auth')->name('TrainerFriends');
    Route::post(__('routes./Trainer/Friends'), [FriendsController::class, 'indexFullTrainer'])->middleware('auth');

    // Exercises
    Route::get(__('routes./Trainer/Exercises'), [ExercisesController::class, 'indexExercisesTrainer'])->middleware(['auth','userTypeChecker'])->name('ExercisesHomeTrainer');
    Route::post(__('routes./Trainer/Exercises'), [ExercisesController::class, 'indexFullTrainer'])->middleware(['auth','userTypeChecker']);

    Route::post(__('routes./Exercises/switchPictures'), [ExercisesController::class, 'switchPictures'])->middleware(['auth']);
    Route::post(__('routes./Exercises/ClearAttribute'), [ExercisesController::class, 'ClearAttribute'])->middleware(['auth']);

    Route::post(__('routes./Exercises/Rotate/Left'), [ExercisesController::class, 'rotateLeft'])->middleware(['auth']);
    Route::post(__('routes./Exercises/Rotate/Right'), [ExercisesController::class, 'rotateRight'])->middleware(['auth']);

    Route::post(__('routes./Exercises/Rotate1/Left'), [ExercisesController::class, 'rotateLeft1'])->middleware(['auth']);
    Route::post(__('routes./Exercises/Rotate1/Right'), [ExercisesController::class, 'rotateRight1'])->middleware(['auth']);

    Route::post(__('routes./Exercises/Rotate2/Left'), [ExercisesController::class, 'rotateLeft2'])->middleware(['auth']);
    Route::post(__('routes./Exercises/Rotate2/Right'), [ExercisesController::class, 'rotateRight2'])->middleware(['auth']);

    Route::get(__('routes./Exercises/addExercise'), [ExercisesController::class, 'indexAdd'])->middleware(['auth']);
    Route::get(__('routes./Exercises/addExerciseInWorkout'), [ExercisesController::class, 'indexAddInWorkout'])->middleware(['auth']);
    Route::post(__('routes./Exercises/Search'), [ExercisesController::class, 'searchExercise'])->middleware(['auth']);
    Route::post(__('routes./Exercises/AddExercise'), [ExercisesController::class, 'AddEdit'])->middleware(['auth']);
    Route::post(__('routes./Exercises/AddExerciseInWorkout'), [ExercisesController::class, 'AddEditInWorkout'])->middleware(['auth']);
    Route::get(__('routes./Exercise') . '/{id}/{name}', [ExercisesController::class, 'show'])->middleware(['auth']);
    Route::get(__('routes./Exercise') . '/{id}', [ExercisesController::class, 'show'])->middleware(['auth']);
    Route::get(__('routes./EditExercise') . '/{id}', [ExercisesController::class, 'editExercise'])->middleware(['auth']);
    Route::post(__('routes./Exercises/AddToFavorite'), [ExercisesController::class, 'addToFavorites'])->middleware(['auth']);


// User Messages
    Route::get(__('routes.Events/eventMessageClient/') . '{from}/{to}', [UserMessagesController::class, 'eventMessageClient']);
    Route::get(__('routes.Events/test'), [UserMessagesController::class, 'eventTest']);

// Trainer Mail
    Route::middleware(['auth', 'userTypeChecker'])->group(function () {
        Route::get(__('routes.Trainer/Mail'), [UserMessagesController::class, 'indexMail']);
        Route::get(__('routes.Trainer/ComposeMail/') . '{user}', [UserMessagesController::class, 'composeMailToUser']);
        Route::get(__('routes.Trainer/ComposeMail'), [UserMessagesController::class, 'composeMail']);
    });

// Global Search
    Route::middleware(['auth'])->group(function () {
        Route::match(['get', 'post'], __('routes./Search'), [UsersController::class, 'globalSearch']);
    });

    // Trainee
    Route::middleware('auth')->group(function () {
        Route::get(__('routes./Trainee/SendFeedback'), [UsersController::class, 'sendFeedback']);
        Route::get(__('routes./Trainee/ViewWorkout'), [UsersController::class, 'viewWorkoutTrainee']);
        Route::get(__('routes./Trainee/Workouts'), [UsersController::class, 'viewWorkoutsTrainee'])->name('traineeWorkouts');
    });

    Route::middleware(['auth', 'userTypeChecker'])->group(function () {
        Route::get(__('routes./Trainee/Settings'), [UsersController::class, 'indexSettings'])->name('TraineeSettings');
        Route::post(__('routes./Trainee/Settings'), [UsersController::class, 'settingsSave']);
        Route::get(__('routes./Trainee/Profile'), [UsersController::class, 'indexProfile'])->name('TraineeProfile');
        Route::get(__('routes./Trainee/EditProfile'), [UsersController::class, 'indexEditTrainee'])->name('EditProfile');
        Route::post(__('routes./Trainee/EditProfile'), [UsersController::class, 'TraineeSave'])->name('EditProfilePost');
    });

    // Profile Image Rotation
    Route::middleware(['auth'])->group(function () {
        Route::post(__('routes./Profile/Rotate/Left'), [UsersController::class, 'rotateLeft']);
        Route::post(__('routes./Profile/Rotate/Right'), [UsersController::class, 'rotateRight']);
    });

    Route::get(Lang::get(__('routes./MembershipManagement')), [MembershipsController::class, 'indexMembershipManagement'])->middleware('auth');
    Route::get(Lang::get(__('routes./MembershipManagementOld')), [MembershipsController::class, 'indexMembershipManagementOld'])->middleware('auth');

    // Trainer
    Route::middleware(['auth', 'userTypeChecker'])->group(function () {
        Route::get(__('routes./Trainer/Settings'), [UsersController::class, 'indexSettingsTrainer'])->name('TrainerSettings');
        Route::get(__('routes./Trainer/Memberships'), [UsersController::class, 'indexMemberships']);
        Route::post(__('routes./Trainer/EmployeeManagement/addEmployees'), [GroupsController::class, 'addEmployees']);
        Route::get(__('routes./Trainer/EmployeeManagement/resendInvite') . '/{userid}', [GroupsController::class, 'resendGroupInvitation']);
        Route::get(__('routes./Trainer/EmployeeManagement/PersonifyBack'), [UsersController::class, 'personifyFromGroupBack']);
        Route::get(__('routes./Trainer/EmployeeManagement/Personify') . '/{param}', [UsersController::class, 'personifyFromGroup']);
        Route::get(__('routes./Trainer/EmployeeManagement/RemoveAccess') . '/{param}', [GroupsController::class, 'removeAccess']);
        Route::post(__('routes./Trainer/EmployeeManagement/ChangeRole'), [GroupsController::class, 'changeRole']);
        Route::get(__('routes./Trainer/Profile'), [UsersController::class, 'indexProfileTrainer'])->name('TrainerProfile');
        Route::get(__('routes./Trainer/EditProfile'), [UsersController::class, 'indexEditTrainer'])->name('EditProfileTrainer');
        Route::post(__('routes./Trainer/EditProfile'), [UsersController::class, 'TrainerSave'])->name('EditProfilePostTrainer');
        Route::get(__('routes./Trainer') . '/{userId}/{userName}', [UsersController::class, 'indexTrainer']);
        Route::get(__('routes./Trainer') . '/{username}', [UsersController::class, 'trainerIndex'])->name('Trainer');
        Route::get(__('routes./Trainer'), [UsersController::class, 'trainerIndex'])->name('TrainerBase');
        Route::get(__('routes./Trainee').'/{userName}', [UsersController::class, 'index'])->name('Trainee');
        Route::get(__('routes./Trainee'), [UsersController::class, 'index'])->name('TraineeBase');
    });

    //|--------------------------------------------------------------------------
    //| GYM MANAGEMENT
    //|--------------------------------------------------------------------------
    Route::get(__('routes./employeeManagement'), [GroupsController::class, 'showGroup'])->name('employeeManagement');

    //|--------------------------------------------------------------------------
    //| STORE
    //|--------------------------------------------------------------------------
    Route::controller(OrdersController::class)->group(function () {
        Route::get(__('routes./Store/Cart'), 'index')->name('cart');
        Route::get(__('routes./Store/removeFromCart'), 'removeFromCart');
        Route::get(__('routes./Store/addToCart')."/{var1}/{var2}", 'addToCart');
        Route::get(__('routes./Store/removeItem')."{var1}", 'removeFromCart');
        Route::get(__('routes./Store/Checkout'), 'checkout')->name('StoreCheckout');
        Route::post(__('routes./Store/ProcessPayment'), 'processPayment')->name('checkout');
        Route::get(__('routes./Store/CreateAccount'), 'createAccount')->name('StoreCreateAccount');
    });

    //|--------------------------------------------------------------------------
    //| LANGUAGES
    //|--------------------------------------------------------------------------
    Route::get(__('routes./lang/').'{param1}', [SystemController::class, 'changeLanguange']);

    //|--------------------------------------------------------------------------
    //| FEEDBACK
    //|--------------------------------------------------------------------------
    Route::post(__('routes./Feedback'), [SystemController::class, 'sendFeedback']);

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
    Route::get('/TraineeSignUp/{key}', [UsersController::class, 'TraineeInvite'])->name('TraineeSignUp');
//    Route::get(__('routes./TraineeSignUp', [], "fr"), [UsersController::class, 'TraineeInvite'])->name('TraineeSignUpNoKey');
    Route::post(__('routes./Trainee/SignUp'), [UsersController::class, 'TraineeSignUp'])->name('TraineeSignUpPost');

    Route::get('trainee/signup', function () {
        return view('trainee.signUp');
    });

    // TRAINER
    Route::get(__('routes./TraineeSignUp/Workout/').'{workout_id}', [UsersController::class, 'TraineeInviteWithWorkout'])->name('trainee-invite-with-workout')->middleware('guest');
    Route::get(__('routes./TrainerSignUp/').'{key}', [UsersController::class, 'TrainerInvite'])->middleware('guest');
    Route::get(__('routes./trainerGetStartedPaid'), [UsersController::class, 'trainerGetStartedPaid']);
    Route::get(__('routes./TrainerSignUp'), [UsersController::class, 'trainerGetStarted'])->name('TrainerSignUp')->middleware('guest');
    Route::post(__('routes./Trainer/SignUp'), [UsersController::class, 'TrainerFreeTrialSignUp']);

    Route::get(__('routes./Gym'), [UsersController::class, 'gym'])->middleware('guest');
    Route::get(__('routes./GymSignUp'), [UsersController::class, 'gymSignUp']);

    Route::post(__('routes./SignUp'), [UsersController::class, 'demoSignUp']);

    Route::get(__('routes./FreeTrialSignin'), function () {
        return view('freeTrialSignin');
    })->name('freeTrialSignin');

    Route::get(__('routes./UpgradePlan'), [OrdersController::class, 'upgradePlan'])->name('cartUpgradePlan');

    Route::get(__('routes./WorkoutBuilderPrice'), function () {
        return view('price');
    });

    Route::get('/thanksss', function () {
        return view('Store.thankYou');
    });

    Route::get('/MobileGetStarted', function () {
        return view('MobileGetStarted');
    })->name('MobileGetStarted');

    Route::get(__('routes./TermsAndConditions'), function () {
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

    Route::get('translationtest', function () {
        return view('translation');
    });

    Route::controller(SocialOAuthController::class)->group(function(){
//        Route::get('auth/facebook', 'redirectToFacebook')->name('auth.facebook');
//        Route::get('auth/facebook/callback', 'handleFacebookCallback');

        Route::get('auth/google/{role}', 'redirectToGoogle')->name('auth.google');
        Route::get('auth/google/callback/{role}', 'handleGoogleCallback')->name('auth.google-callback');

    });
//});
