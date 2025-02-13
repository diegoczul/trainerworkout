<?php

use App\Http\Controllers\Web\ExercisesController;
use App\Http\Controllers\Web\ExercisesImagesController;
use App\Http\Controllers\Web\ObjectivesController;
use App\Http\Controllers\Web\PicturesController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\WeightsController;
use App\Http\Controllers\Web\WorkoutsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
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

//API
Route::post('/Exercises/AddEdit', [ExercisesController::class, 'AddEdit']);
Route::get('/ExercisesImages/{param}', [ExercisesImagesController::class, 'index']);
Route::get('/Exercises/show/{search}', [ExercisesController::class, 'APIShow']);
Route::post('/API/Exercises/search', [ExercisesController::class, 'APIsearchExercise']);

// USERS
Route::post('/API/Users/Profile', [UsersController::class, 'APIEditProfile']);
Route::post('/API/Users/Login', [UsersController::class, 'APILogin']);
Route::post('/API/Users/App', [UsersController::class, 'APIAppSettings']);
Route::post('/API/Users/LoginAuto', [UsersController::class, 'APILoginAuto']);
Route::match(['get', 'post'], '/API/logout', [UsersController::class, 'APILogout']);
Route::post('/API/Users/Register', [UsersController::class, 'APIRegistration']);

// Objectives
Route::post('/API/Objectives/AddEdit', [ObjectivesController::class, 'APIAddEdit'])->middleware('auth');

// Pictures
Route::match(['get', 'post'], '/API/Pictures', [PicturesController::class, 'APIIndex'])->middleware('auth');
Route::post('/API/Pictures/AddEdit', [PicturesController::class, 'APIAddEdit'])->middleware('auth');

// Weight
Route::post('/API/Weight/AddEdit', [WeightsController::class, 'APIAddEdit'])->middleware('auth');

// WORKOUTS
Route::post('/API/Workouts', [WorkoutsController::class, 'APIIndex']);
Route::post('/API/Workouts/saveSingleSet', [WorkoutsController::class, 'APIsaveSingleSet']);
Route::post('/API/Workouts/completeSet', [WorkoutsController::class, 'APIcompleteSet']);
Route::post('/API/Workouts/saveAllSets', [WorkoutsController::class, 'APIsaveAllSets']);
Route::post('/API/Workouts/APIexerciseCompleted', [WorkoutsController::class, 'APIexerciseCompleted']);
Route::post('/API/Workouts/workoutCompleted', [WorkoutsController::class, 'APIworkoutCompleted']);
Route::get('/API/Workouts', [WorkoutsController::class, 'APIIndex']);

// NIC CHANGES
Route::post('/API/WorkoutsBasic', [WorkoutsController::class, 'API_Workouts_Basic']);
Route::post('/API/WorkoutGroups', [WorkoutsController::class, 'API_Workout_Groups']);
Route::post('/API/ExerciseModel', [ExercisesController::class, 'API_Exercise_Model']);

// WORKOUTS with auth
Route::get('/API/Workouts', [WorkoutsController::class, 'APIIndex'])->middleware('auth');
Route::get('/API/Workouts/{param}', [WorkoutsController::class, 'show'])->middleware('auth');
Route::post('/API/Workouts/addEdit/', [WorkoutsController::class, 'APIAddEdit'])->middleware('auth');
Route::delete('/API/Workouts/{param}', [WorkoutsController::class, 'destroy'])->middleware('auth');

// USERS with auth
Route::get('/API/Users', [UsersController::class, 'APIindex'])->middleware('auth');
Route::get('/API/Users/{param}', [UsersController::class, 'show'])->middleware('auth');
Route::post('/API/Users/addEdit/', [UsersController::class, 'APIAddEdit'])->middleware('auth');
Route::delete('/API/Users/{param}', [UsersController::class, 'destroy'])->middleware('auth');

// IOS
Route::post('/API/IOS/CreateWorkout', [WorkoutsController::class, 'API_IOS_CreateWorkout'])->middleware('auth');


