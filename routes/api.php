<?php

use App\Http\Controllers\web\ExercisesController;
use App\Http\Controllers\web\ExercisesImagesController;
use App\Http\Controllers\web\ObjectivesController;
use App\Http\Controllers\web\PicturesController;
use App\Http\Controllers\web\UsersController;
use App\Http\Controllers\web\WeightsController;
use App\Http\Controllers\web\WorkoutsController;
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
});

Route::middleware('auth:api')->group(function (){
    // USERS
    Route::controller(UsersController::class)->group(function (){
        Route::post('Users/Profile','APIEditProfile');
        Route::post('Users/App','APIAppSettings');
        Route::match(['get', 'post'], 'logout','APILogout');
    });

    //API
    Route::post('Exercises/AddEdit', [ExercisesController::class, 'AddEdit']);
    Route::get('ExercisesImages/{param}', [ExercisesImagesController::class, 'index']);
    Route::get('Exercises/show/{search}', [ExercisesController::class, 'APIShow']);
    Route::post('Exercises/search', [ExercisesController::class, 'APIsearchExercise']);

    // Objectives
    Route::post('Objectives/AddEdit', [ObjectivesController::class, 'APIAddEdit'])->middleware('auth');

    // Pictures
    Route::match(['get', 'post'], 'Pictures', [PicturesController::class, 'APIIndex'])->middleware('auth');
    Route::post('Pictures/AddEdit', [PicturesController::class, 'APIAddEdit'])->middleware('auth');

    // Weight
    Route::post('Weight/AddEdit', [WeightsController::class, 'APIAddEdit'])->middleware('auth');

    // WORKOUTS
    Route::post('Workouts', [WorkoutsController::class, 'APIIndex']);
    Route::post('Workouts/saveSingleSet', [WorkoutsController::class, 'APIsaveSingleSet']);
    Route::post('Workouts/completeSet', [WorkoutsController::class, 'APIcompleteSet']);
    Route::post('Workouts/saveAllSets', [WorkoutsController::class, 'APIsaveAllSets']);
    Route::post('Workouts/APIexerciseCompleted', [WorkoutsController::class, 'APIexerciseCompleted']);
    Route::post('Workouts/workoutCompleted', [WorkoutsController::class, 'APIworkoutCompleted']);
    Route::get('Workouts', [WorkoutsController::class, 'APIIndex']);

    // NIC CHANGES
    Route::post('WorkoutsBasic', [WorkoutsController::class, 'API_Workouts_Basic']);
    Route::post('WorkoutGroups', [WorkoutsController::class, 'API_Workout_Groups']);
    Route::post('ExerciseModel', [ExercisesController::class, 'API_Exercise_Model']);

    // WORKOUTS with auth
    Route::get('Workouts', [WorkoutsController::class, 'APIIndex'])->middleware('auth');
    Route::get('Workouts/{param}', [WorkoutsController::class, 'show'])->middleware('auth');
    Route::post('Workouts/addEdit/', [WorkoutsController::class, 'APIAddEdit'])->middleware('auth');
    Route::delete('Workouts/{param}', [WorkoutsController::class, 'destroy'])->middleware('auth');

    // USERS with auth
    Route::get('Users', [UsersController::class, 'APIindex'])->middleware('auth');
    Route::get('Users/{param}', [UsersController::class, 'show'])->middleware('auth');
    Route::post('Users/addEdit/', [UsersController::class, 'APIAddEdit'])->middleware('auth');
    Route::delete('Users/{param}', [UsersController::class, 'destroy'])->middleware('auth');

    // IOS
    Route::post('IOS/CreateWorkout', [WorkoutsController::class, 'API_IOS_CreateWorkout'])->middleware('auth');

});


