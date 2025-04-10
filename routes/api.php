<?php

use App\Http\Controllers\web\ClientsController;
use App\Http\Controllers\web\ExercisesController;
use App\Http\Controllers\web\ObjectivesController;
use App\Http\Controllers\web\PicturesController;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\web\WeightsController;
use App\Http\Controllers\web\WorkoutsController;
use App\Http\Controllers\web\WorkoutsPerformanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//|--------------------------------------------------------------------------
//| API V1.2
//|--------------------------------------------------------------------------

// USERS
Route::controller(UsersController::class)->group(function (){
    Route::post('Users/Login', 'APILogin');
    Route::post('Users/Register', 'APIRegistration');
    Route::post('Users/LoginAuto', 'APILoginAuto');
    Route::post('Users/ForgetPassword', 'APIForgetPassword');
});

Route::post('WorkoutsBasic', [WorkoutsController::class, 'API_Workouts_Basic']);
Route::post('WorkoutGroups', [WorkoutsController::class, 'API_Workout_Groups']);

Route::middleware('auth:api')->group(function (){
    // USERS
    Route::controller(UsersController::class)->group(function (){
        Route::post('Users/Profile','APIEditProfile');
        Route::post('Users/App','APIAppSettings');
        Route::delete('Users/delete-account', 'APIDeleteAccount');
        Route::match(['get', 'post'], 'logout','APILogout');

        Route::post('Exercises/AddEdit','AddEdit');
        Route::get('ExercisesImages/{param}','index');
        Route::get('Exercises/show/{search}','APIShow');
        Route::post('Exercises/search','APIsearchExercise');

        Route::get('Users','APIindex')->middleware('auth');
        Route::get('Users/{param}','show')->middleware('auth');
        Route::post('Users/addEdit/','APIAddEdit')->middleware('auth');
        Route::delete('Users/{param}','destroy')->middleware('auth');
    });

    // Objectives
    Route::controller(ObjectivesController::class)->group(function (){
        Route::post('Objectives/AddEdit','APIAddEdit')->middleware('auth');
    });

    // Pictures
    Route::controller(PicturesController::class)->group(function (){
        Route::match(['get', 'post'], 'Pictures', 'APIIndex')->middleware('auth');
        Route::post('Pictures/AddEdit', 'APIAddEdit')->middleware('auth');
    });

    // Weight
    Route::controller(WeightsController::class)->group(function (){
        Route::post('Weight/AddEdit','APIAddEdit')->middleware('auth');
    });

    // WORKOUTS
    Route::controller(WorkoutsController::class)->group(function (){
        Route::get('Workouts', 'APIIndex');
        Route::post('Workouts', 'APIIndex');
        Route::get('Workouts/{param}','show');
        Route::delete('Workouts/{param}','APIWorkoutDelete');
        Route::post('Workouts/saveSingleSet', 'APIsaveSingleSet');
        Route::post('Workouts/completeSet', 'APIcompleteSet');
        Route::post('Workouts/saveAllSets', 'APIsaveAllSets');
        Route::post('Workouts/APIexerciseCompleted', 'APIExerciseCompleted');
        Route::post('Workouts/workoutCompleted', 'APIworkoutCompleted');
        Route::post('IOS/CreateWorkout','API_IOS_CreateWorkout')->middleware('auth');
        Route::post('IOS/EditWorkout/{workout_id}','API_IOS_EditWorkout')->middleware('auth');
        Route::post('Workouts/share-workout','API_ShareWorkout')->middleware('auth');
        Route::post('Workouts/download-workout','API_DownloadWorkout')->middleware('auth');
    });

    // NIC CHANGES
    Route::controller(ExercisesController::class)->group(function (){
        Route::post('ExerciseModel','API_Exercise_Model');
    });

    // PERFORMED WORKOUT
    Route::controller(WorkoutsPerformanceController::class)->group(function (){
        Route::post('ClientReport/list-workout-performance', 'API_List_WorkoutsPerformance');
    });

    // CLIENT
    Route::controller(ClientsController::class)->group(function (){
        Route::post('list-client','API_ListClients');
        Route::post('list-client-workouts','API_clientWorkouts');
    });
});


