<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use App\Http\Libraries\Messages;
use App\Models\Exercises;
use App\Models\Feeds;
use App\Models\Groups;
use App\Models\Invites;
use App\Models\Objectives;
use App\Models\Tasks;
use App\Models\TemplateSets;
use App\Models\UserLogos;
use App\Models\Users;
use App\Models\Permissions;
use App\Models\Memberships;
use App\Models\MembershipsUsers;
use App\Models\Clients;
use App\Models\Friends;
use App\Models\Weights;
use App\Models\Workouts;
use App\Models\WorkoutsExercises;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Event;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;
use UsersSettings;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends BaseController
{
    public function index()
    {
        $user = Auth::user();
        return view('trainer.index')->with('user', $user);
    }

    public function gym()
    {
        return view('gym');
    }

    public function gymSignUp()
    {
        return view('gymSignUp');
    }

    public function trainerIndex()
    {
        $user = Auth::user();
        return view('trainer.index')->with('user', $user);
    }

    public function trainerGetStarted()
    {
        $user = Auth::user();
        return view('TrainerSignUp')->with('user', $user);
    }

    public function trainerGetStartedPaid()
    {
        $user = Auth::user();
        return view('TrainerSignUp')->with(['paid' => 'yes', 'user' => $user]);
    }

    public function indexSettings()
    {
        $user = Auth::user();
        $userId = $user->id;
        $permissions = null;
        if ($userId) {
            $permissions = Helper::checkPremissions(Auth::id(), $userId);
            if ($permissions['view']) {
                $userId = Request::get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        $userPermissions = [];
        $staticPermissions = Permissions::where('userId', $user->id)->get();
        foreach ($staticPermissions as $staticPermission) {
            $userPermissions[$staticPermission->widget] = $staticPermission->access;
        }

        if ($user) {
            return view('trainee.settings')->with('permissions', $permissions)->with('userPermissions', $userPermissions)->with('user', $user);
        } else {
            return Redirect::route('Trainee', [Helper::formatURLString($user->firstName . $user->lastName)])->withErrors(Lang::get('messages.UserNotFound'));
        }
    }

    public function indexMemberships()
    {
        $user = Auth::user();
        $memberships = Memberships::all();
        $membershipsUser = MembershipsUsers::where('userId', $user->id)->first();
        return view('trainer.memberships')->with('memberships', $memberships)->with('membershipsSelected', $membershipsUser)->with('user', $user);
    }

    public function rotateRight()
    {
        $obj = Auth::user();
        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(-90)->save();
            $thumb = Image::make($obj->thumb);
            $thumb->rotate(-90)->save();

            return response()->json(['message' => Lang::get('messages.ImageRotated')]);
        }
    }

    public function rotateLeft()
    {
        $obj = Auth::user();
        if ($obj && file_exists($obj->image)) {
            $image = Image::make($obj->image);
            $image->rotate(90)->save();
            $thumb = Image::make($obj->thumb);
            $thumb->rotate(90)->save();

            return response()->json(['message' => Lang::get('messages.ImageRotated')]);
        }
    }

    public function indexSettingsTrainer()
    {
        $user = Auth::user();
        $userId = $user->id;
        $permissions = null;
        if ($userId) {
            $permissions = Helper::checkPremissions(Auth::id(), $userId);
            if ($permissions['view']) {
                $userId = Request::get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        $userPermissions = [];
        $staticPermissions = Permissions::where('userId', $user->id)->get();
        $staticPermissionsSettings = UsersSettings::where('userId', $user->id)->get();

        foreach ($staticPermissions as $staticPermission) {
            $userPermissions[$staticPermission->widget] = $staticPermission->access;
        }

        foreach ($staticPermissionsSettings as $staticPermission) {
            $userPermissions[$staticPermission->name] = $staticPermission->value;
        }

        if ($user) {
            return view('trainer.settings')->with('permissions', $permissions)->with('userPermissions', $userPermissions)->with('user', $user);
        } else {
            return Redirect::route('Trainee', ['userName' => Helper::formatURLString($user->firstName . $user->lastName)])->withErrors(Lang::get('messages.UserNotFound'));
        }
    }

    public function confirmEmail($token)
    {
        $user = Users::where('token', $token)->first();
        if ($user) {
            $user->activated = now();
            $user->save();
            Auth::loginUsingId($user->id);
            Event::dispatch('confirmEmail', [Auth::user()]);
            return Redirect::route(strtolower(Auth::user()->userType) . 'Workouts')->with('message', Lang::get('messages.EmailConfirmed'));
        } else {
            return Redirect::route(strtolower(Auth::user()->userType) . 'Workouts')->withErrors(Lang::get('messages.EmailNotConfirmed'));
        }
    }

    public function indexSuggestPeople()
    {
        $userId = Auth::user()->id;
        $search = Request::get('term');

        return response()->json(Users::where(function ($query) use ($search) {
            $query->orWhere('firstName', 'like', "%$search%")
                ->orWhere('lastName', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })->whereNotIn('id', array_merge([0], Clients::where('trainerId', $userId)->pluck('userId')->toArray()))->get());
    }

    public function indexSuggestPeopleWithClients()
    {
        $userId = Auth::user()->id;
        $search = Request::get('term');

        $list = Users::whereIn('id', array_merge([0], Friends::select('users.id')
            ->where(function ($query) use ($userId) {
                $query->orWhere('userId', $userId)
                    ->orWhere('followingId', $userId);
            })
            ->leftJoin('users', 'users.id', '=', 'followingId')
            ->where(function ($query) use ($search) {
                $query->orWhere('firstName', 'like', "%$search%")
                    ->orWhere('lastName', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->pluck('users.id')->toArray()))
            ->orWhereIn('id', array_merge([0], Clients::select('users.id')
                ->leftJoin('users', 'users.id', '=', 'userId')
                ->where(function ($query) use ($search) {
                    $query->orWhere('firstName', 'like', "%$search%")
                        ->orWhere('lastName', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->where('trainerId', $userId)
                ->pluck('users.id')->toArray()))
            ->get();

        return response()->json($list);
    }

    public function registerNewsletter(Request $request)
    {
        $validation = Validator::make($request->all(), ["email" => "required|email"]);

        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            MailchimpWrapper::lists()->subscribe(config("constants.mailChimpNewsletter"), [
                'email' => request("email"),
                'email_address' => request("status"),
                'email' => "subscribed"
            ]);
            return $this->responseJson(__('messages.newsletter'));
        }
    }

    public function settingsSave()
    {
        $user = Auth::user();
        $staticPermissions = [
            "w_objectives", "w_pictures", "w_measurements", "w_workouts", "w_information", "w_userMessages", "email_notifications", "w_publicProfile", "newsletter", "email_notifications_trainer", "email_notifications_workout", "email_notifications_client", "email_notifications_people", "email_notifications_trainer", "setting_workout_reminder", "setting_workout_reminder_number", "setting_weight_reminder_number", "setting_measurements_reminder_number", "setting_pictures_reminder_number", "setting_inactive_reminder_number"
        ];

        foreach ($staticPermissions as $key) {
            if (!str_contains($key, 'setting')) {
                $permissionFetched = Permissions::where("widget", $key)->where("userId", $user->id)->first();
                if ($permissionFetched) {
                    $perm = Permissions::find($permissionFetched->id);
                    $perm->access = request($key);
                    $perm->save();
                } else {
                    Permissions::create(["userId" => $user->id, "widget" => $key, "access" => request($key)]);
                }
            } else {
                $permissionFetched = UsersSettings::where("name", $key)->where("userId", $user->id)->first();
                if ($permissionFetched) {
                    $perm = UsersSettings::find($permissionFetched->id);
                    $perm->value = request($key);
                    $perm->save();
                } else {
                    UsersSettings::create(["userId" => $user->id, "name" => $key, "value" => request($key)]);
                }
            }
        }

        return $this->responseJson(__('messages.PermissionsSaved'));
    }

    public function settingsSaveTrainer()
    {
        $user = Auth::user();
        $staticPermissions = [
            "w_objectives", "w_pictures", "w_measurements", "w_workouts", "w_information", "w_userMessages", "email_notifications", "w_publicProfile", "newsletter", "email_notifications_trainer", "email_notifications_workout", "email_notifications_client", "email_notifications_people", "email_notifications_trainer", "setting_workout_reminder", "setting_workout_reminder_number", "setting_weight_reminder_number", "setting_measurements_reminder_number", "setting_pictures_reminder_number", "setting_inactive_reminder_number"
        ];

        foreach ($staticPermissions as $key) {
            if (!str_contains($key, 'setting')) {
                $permissionFetched = Permissions::where("widget", $key)->where("userId", $user->id)->first();
                if ($permissionFetched) {
                    $perm = Permissions::find($permissionFetched->id);
                    $perm->access = request($key);
                    $perm->save();
                } else {
                    Permissions::create(["userId" => $user->id, "widget" => $key, "access" => request($key)]);
                }
            } else {
                $permissionFetched = UsersSettings::where("name", $key)->where("userId", $user->id)->first();
                if ($permissionFetched) {
                    $perm = UsersSettings::find($permissionFetched->id);
                    $perm->value = request($key);
                    $perm->save();
                } else {
                    UsersSettings::create(["userId" => $user->id, "name" => $key, "value" => request($key)]);
                }
            }
        }

        event('updateFeedSettings', [Auth::user()]);
        return $this->responseJson(__('messages.PermissionsSaved'));
    }

    public function indexProfile()
    {
        return view('trainee.profile')->with("user", Auth::user());
    }

    public function sendFeedback()
    {
        return view(strtolower(Auth::user()->userType) . '.sendFeedback')->with("user", Auth::user());
    }

    public function viewWorkoutTrainee()
    {
        return view('trainee.viewWorkout')->with("user", Auth::user());
    }

    public function viewWorkoutsTrainee()
    {
        return view('trainee.workouts')->with("user", Auth::user());
    }

    public function indexVideoWord()
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if (request()->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, request("userId"));
            if ($permissions["view"]) {
                $userId = request("userId");
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        return view('widgets.full.videoWord')->with("permissions", $permissions)->with("user", $user);
    }

    public function indexBioFull()
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        $permissions = null;

        if (request()->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, request("userId"));
            if ($permissions["view"]) {
                $userId = request("userId");
                $user = Users::find($userId);
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        return view('widgets.full.biography')->with("permissions", $permissions)->with("user", $user);
    }

    public function indexProfileTrainer()
    {
        $logo = Users::find(Auth::user()->id)->activeLogo;
        return view('trainer.profile')->with("user", Auth::user())->with("logo", $logo);
    }

    public function indexTrainee($userId, $userName)
    {
        $user = Users::find($userId);
        $permissions = null;

        if (!empty($userId)) {
            if (Clients::where('trainerId', Auth::user()->id)->where('userId', $userId)->count() == 0) {
                if (Helper::checkPermissionString($userId, 'w_publicProfile') === 'no') {
                    return Redirect::route('Trainee', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                        ->withErrors(Lang::get('messages.PrivateAccount'));
                } elseif (Helper::checkPermissionString($userId, 'w_publicProfile') === 'friends' && Friends::where('followingId', $viewer)->where('userId', $toView)->count() == 0) {
                    return Redirect::route('Trainee', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                        ->withErrors(Lang::get('messages.PrivateAccount'));
                }
            }
            $permissions = Helper::checkPermission(Auth::user()->id, $userId, 'w_information');
        } else {
            $permissions = Helper::checkPermission(Auth::user()->id, null);
        }

        return $user
            ? View::make('trainee.trainee', ['permissions' => $permissions, 'user' => $user])
            : Redirect::route('Trainee', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->withErrors(Lang::get('messages.UserNotFound'));
    }

    public function indexTrainer($userId, $userName)
    {
        $user = Users::find($userId);
        $permissions = null;

        if (!empty($userId)) {
            if (Clients::where('trainerId', Auth::user()->id)->where('userId', $userId)->count() == 0) {
                if (Helper::checkPermissionString($userId, 'w_publicProfile') === 'no') {
                    return Redirect::route(Auth::user()->userType, ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                        ->withErrors(Lang::get('messages.PrivateAccount'));
                } elseif (Helper::checkPermissionString($userId, 'w_publicProfile') === 'friends' && Friends::where('followingId', $viewer)->where('userId', $toView)->count() == 0) {
                    return Redirect::route(Auth::user()->userType, ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                        ->withErrors(Lang::get('messages.PrivateAccount'));
                }
            }
            $permissions = Helper::checkPermission(Auth::user()->id, $userId, 'w_information');
        } else {
            $permissions = Helper::checkPermission(Auth::user()->id, null);
        }

        return $user
            ? View::make(strtolower(trim(Auth::user()->userType)) . '.trainer', ['permissions' => $permissions, 'user' => $user])
            : Redirect::route('Trainee', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->withErrors(Lang::get('messages.UserNotFound'));
    }

    public function globalSearch()
    {
        $user = Auth::user();
        $view = Auth::user()->userType === 'Trainer' ? 'trainer.search' : 'trainee.search';

        return View::make($view, ['search' => request('search'), 'user' => $user]);
    }

    public function indexEditTrainee()
    {
        return View::make('trainee.editProfile', ['user' => Auth::user()]);
    }

    public function indexEditTrainer()
    {
        $user = Auth::user();
        $permissions = Helper::checkPremissions(Auth::user()->id, Auth::user()->id);
        $logo = Users::find(Auth::user()->id)->activeLogo;

        return View::make('trainer.profile', ['user' => $user, 'logo' => $logo, 'permissions' => $permissions]);
    }

    public function indexDeleteAccount($id)
    {
        $user = Users::find($id);
        $user->delete();

        return $this->responseJson(Messages::showControlPanel("UserDeleted"));
    }

    public function ApiList()
    {
        return $this::responseJson(Users::getList());
    }

    public function AddEdit()
    {
        return request()->filled('hiddenUserId') ? $this->update(request('hiddenUserId')) : $this->create();
    }

    public function AddEditBio()
    {
        $rules = [
            'biography' => 'max:5000',
            'certifications' => 'max:5000',
            'past_experience' => 'max:5000',
        ];

        $validation = Validator::make(request()->all(), $rules);

        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $user = Auth::user();
        $user->fill(request()->only('biography', 'certifications', 'past_experience'));
        $user->save();

        return $this::responseJson(Lang::get('messages.BioSaved'));
    }

    public function AddEditVideoWord()
    {
        $rules = [
            'word' => 'max:5000',
            'videoLink' => 'url',
        ];

        $validation = Validator::make(request()->all(), $rules);

        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        $user = Auth::user();
        $user->word = request('word');
        $user->videoLink = request('video');
        $user->videoKey = Helper::extractYoutubeTag(request('video'));
        $user->save();

        return $this::responseJson(Lang::get('messages.WordSaved'));
    }

    public function TraineeSignUp()
    {
        $user = null;

        if (request()->has('invite')) {
            $invite = Invites::where('key', request('invite'))->first();
            if ($invite) {
                if (!empty($invite->fakeId)) {
                    $user = Users::find($invite->fakeId);
                    $user->fill(request()->only('timezone'));
                    $password = request('password') ?: 'TrainerWorkout';
                    $user->password = Hash::make($password);
                    $user->fill([
                        'firstName' => ucfirst(request('firstName')),
                        'lastName' => ucfirst(request('lastName')),
                        'email' => strtolower(request('email')),
                        'phone' => Helper::formatPhone(request('phoneNumber')),
                        'userType' => 'Trainee',
                    ]);
                    $user->save();
                    $invite->completeInvite();
                    Auth::loginUsingId($user->id);
                    Event::dispatch('signUp', [$user]);
                } else {
                    $validation = Users::validate(request()->all());
                    if ($validation->fails()) {
                        return Redirect::back()->withInput()->withErrors($validation->messages());
                    }
                    $user = new Users(request()->only('firstName', 'lastName', 'email'));
                    $user->phone = Helper::formatPhone(request('phoneNumber'));
                    $user->password = Hash::make(request('password'));
                    $user->userType = 'Trainee';
                    $user->save();
                    Auth::loginUsingId($user->id);
                    Event::dispatch('signUp', [$user]);
                    $invite->completeInvite($user);
                }
            }
        } else {
            $validation = Users::validate(request()->all(), ['termsAndConditions' => 'required']);
            if ($validation->fails()) {
                return Redirect::back()->withInput()->withErrors($validation->messages());
            }

            $user = new Users(request()->only('firstName', 'lastName', 'email'));
            $user->phone = Helper::formatPhone(request('phoneNumber'));
            $user->password = Hash::make(request('password'));
            $user->userType = 'Trainee';
            $user->save();
            Auth::loginUsingId($user->id);
        }

        if (request()->filled('workout')) {
            $workout = Workouts::find(request('workout'));
            $workoutNew = $workout->replicate(['shares', 'views', 'timesPerformed', 'availability']);
            $workoutNew->userId = Auth::user()->id;
            $workoutNew->availability = 'private';
            $workoutNew->save();

            $workoutsExercises = WorkoutsExercises::where('workoutId', $workout->id)->get();
            foreach ($workoutsExercises as $workoutExercise) {
                $workoutExerciseNew = $workoutExercise->replicate();
                $workoutExerciseNew->workoutId = $workoutNew->id;
                $workoutExerciseNew->save();

                $templateSets = TemplateSets::where('workoutsExercisesId', $workoutExercise->id)->get();
                foreach ($templateSets as $templateSet) {
                    $templateSetNew = $templateSet->replicate();
                    $templateSetNew->workoutId = $workoutNew->id;
                    $templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
                    $templateSetNew->save();
                }
            }
            $workoutNew->createSets();
        }

        Invites::where('email', $user->email)->where('completed', 0)->update(['completed' => 1]);

        Feeds::insertFeed('SignUp', Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);

        try {
            MailchimpWrapper::lists()->subscribe(config('constants.mailChimpTrainees'), [
                'email' => request('email'),
                'status' => 'subscribed',
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }

        if (!Auth::user()->membership) {
            Auth::user()->updateToMembership(config('constants.freeTrialMembershipId'));
        }

        return Redirect::route('traineeWorkouts')->with('message', Lang::get('messages.Welcome'))->with('newUser', true);
    }
    public function TrainerFreeTrialSignUp(Request $request)
    {
        $request->validate([
            'termsAndConditions' => 'required',
        ]);

        if (!$request->has('termsAndConditions')) {
            return redirect()->route('home')->withInput()->withErrors(__('messages.termsAndConditions'));
        }

        $user = new Users;
        $user->firstName = ucfirst($request->get('firstName'));
        $user->lastName = ucfirst($request->get('lastName'));
        $user->email = strtolower(trim($request->get('email')));

        if ($request->filled('timezone')) {
            $user->timezone = $request->get('timezone');
        }

        $user->phone = Helper::formatPhone(strtolower($request->get('phoneNumber')));
        $user->password = Hash::make($request->get('password'));
        $user->userType = "Trainer";
        $user->lastLogin = date("Y-m-d");
        $user->save();

        $user->sendActivationEmail();
        Auth::loginUsingId($user->id);
        Event::dispatch('signUp', [$user]);

        try {
            if (!Config::get('app.debug')) {
                MailchimpWrapper::lists()->subscribe(Config::get('constants.mailChimpTrainers'), ['email' => $user->email]);
            }
        } catch (Exception $e) {
            Log::error("MAILCHIMP Error");
            Log::error($e);
            return null;
        }

        if (Session::has('utm')) {
            $user->marketing = Session::get('utm');
            $user->save();
            Session::forget('utm');
        }

        $user->freebesTrainer();

        if ($request->get('paid') == 'yes') {
            return redirect("/Store/addToCart/63/Membership");
        }

        if (Session::has('redirect') && Session::get('redirect') != '') {
            if (!Auth::user()->membership) {
                Auth::user()->updateToMembership(Config::get('constants.freeTrialMembershipId'));
            }
            return redirect()->route(Session::get('redirect'));
        } else {
            if (!Auth::user()->membership) {
                Auth::user()->updateToMembership(Config::get('constants.freeTrialMembershipId'));
            }
            return redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->with('message', __('messages.Welcome'));
        }
    }

    public function TrainerSignUp(Request $request)
    {
        $request->validate([
            'termsAndConditions' => 'required',
        ]);

        if (!$request->has('termsAndConditions')) {
            return redirect()->back()->withInput()->withErrors(__('messages.termsAndConditions'));
        }

        $user = new Users;
        $user->firstName = ucfirst($request->get('firstName'));
        $user->lastName = ucfirst($request->get('lastName'));
        $user->email = strtolower($request->get('email'));

        if ($request->filled('timezone')) {
            $user->timezone = $request->get('timezone');
        }

        $user->password = Hash::make($request->get('password'));
        $user->userType = "Trainer";
        $user->city = ucfirst($request->get('city'));
        $user->province = ucfirst($request->get('province'));
        $user->country = ucfirst($request->get('country'));
        $user->biography = ucfirst($request->get('biography'));
        $user->certifications = ucfirst($request->get('certifications'));
        $user->specialities = ucfirst($request->get('specialities'));
        $user->past_experience = ucfirst($request->get('past_experience'));
        $user->lastLogin = date("Y-m-d");
        $user->save();

        Auth::loginUsingId($user->id);
        Event::dispatch('signUp', [$user]);

        if (Session::has('utm')) {
            $user->marketing = Session::get('utm');
            $user->save();
            Session::forget('utm');
        }

        $user->freebesTrainer();

        if ($request->has('invite')) {
            $invite = Invites::where('key', $request->get('invite'))->first();
            if ($invite) {
                $invite->completeInvite($user);
            }
        }

        if ($request->filled('workout')) {
            $workout = Workouts::find($request->get('workout'));
            $workoutNew = $workout->replicate();
            $workoutNew->userId = Auth::user()->id;
            $workoutNew->availability = 'private';
            $workoutNew->save();

            $WorkoutsExercises = WorkoutsExercises::where('workoutId', $workout->id)->get();

            foreach ($WorkoutsExercises as $workoutExercise) {
                $workoutExerciseNew = $workoutExercise->replicate();
                $workoutExerciseNew->workoutId = $workoutNew->id;
                $workoutExerciseNew->save();

                $templateSets = TemplateSets::where('workoutsExercisesId', $workoutExercise->id)->get();
                foreach ($templateSets as $templateSet) {
                    $templateSetNew = $templateSet->replicate();
                    $templateSetNew->workoutId = $workoutNew->id;
                    $templateSetNew->workoutsExercisesId = $workoutExerciseNew->id;
                    $templateSetNew->save();
                }
            }

            $workoutNew->createSets();
        }

        Feeds::insertFeed("SignUp", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);

        try {
            MailchimpWrapper::lists()->subscribe(Config::get('constants.mailChimpTrainers'), [
                'email' => $request->get('email'),
                'status' => 'subscribed',
            ]);
        } catch (Exception $e) {
            Log::error($e);
        }

        if (!Auth::user()->membership) {
            Auth::user()->updateToMembership(Config::get('constants.freeTrialMembershipId'));
        }

        if ($request->get('paid') == 'yes') {
            return redirect("/Store/addToCart/64/Membership");
        } else {
            return redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->with('message', __('messages.Welcome'))
                ->with('newUser', true);
        }
    }

    public function TraineeInvite($key = "")
    {
        $invite = Invites::where('key', $key)->first();
        if ($invite) {
            $invite->viewed = 1;
            $invite->save();
            return View::make(Helper::translateOverride('TraineeSignUp'))->with(["key" => $key, "invite" => $invite]);
        }

        return View::make(Helper::translateOverride('TraineeSignUp'))->with("key", $key);
    }

    public function TraineeInviteWithWorkout($workout)
    {
        return View::make('TraineeSignUp')->with("workout", $workout);
    }

    public function create(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $user = new Users;
        $user->firstName = ucfirst($request->get('firstName'));
        $user->lastName = ucfirst($request->get('lastName'));
        $user->email = strtolower($request->get('email'));
        $user->phone = strtolower($request->get('phone'));
        $user->userType = "Trainee";
        $user->save();

        return response()->json(["message" => "User Created"]);
    }
    public function login(Request $request)
    {
        $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];

        if (Auth::attempt($credentials, true)) {
            $user = Auth::user();

            $user->update(['updated_at' => now(), 'lastLogin' => now(), 'virtual' => 0]);

            event('login', [$user]);

            setcookie("TrainerWorkoutUserId", Crypt::encrypt($user->id), time() + (86400 * 30 * 7), "/");

            if ($user->lang) {
                App::setLocale($user->lang);
            } else {
                App::setLocale(Session::get('lang', 'en'));
            }

            $route = $user->userType == 'Trainer' ? 'trainerWorkouts' : 'traineeWorkouts';
            return redirect()->route($route, ['userName' => Helper::formatURLString($user->firstName . $user->lastName)])->with('message', __('messages.Welcome'));
        }

        return redirect()->back()->withInput()->withErrors(__('messages.WrongLogin'));
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        return Users::find($id);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "email" => "required|email",
            "password" => "nullable",
            "password_confirmation" => "same:password"
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->messages());
        } else {
            $user = Users::find($id);
            $user->firstName = ucfirst($request->get("firstName"));
            $user->lastName = ucfirst($request->get("lastName"));
            $user->email = strtolower($request->get("email"));
            $user->gender = strtolower($request->get("gender"));
            $user->phone = Helper::formatPhone(strtolower($request->get("phone")));

            Event::dispatch('editProfileInformation', [Auth::user()]);

            if ($request->filled("password")) {
                $user->password = Hash::make($request->get("password"));
            }
            $user->save();
            return response()->json("User Modified");
        }
    }

    public function TraineeSave(Request $request)
    {
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "password" => "nullable",
            "password_confirmation" => "same:password",
            "email" => Auth::user()->email != $request->get("email") ? "required|email|unique:users,email,NULL,id,deleted_at,NULL" : "required|email"
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->messages());
        } else {
            $user = Auth::user();
            $user->firstName = ucfirst($request->get("firstName"));
            $user->lastName = ucfirst($request->get("lastName"));
            $user->email = strtolower($request->get("email"));
            $user->phone = Helper::formatPhone(strtolower($request->get("phone")));
            $user->gender = strtolower($request->get("gender"));
            $user->userType = "Trainee";
            if($request->get('birthday')){
                $user->birthday = strtolower($request->get("birthday"));
            }
            if ($request->get("timezone")) {
                $user->timezone = $request->get("timezone");
            }
            if ($request->filled("password")) {
                $user->password = Hash::make($request->get("password"));
            }
            $user->save();

            Helper::checkUserFolder($user->id);

            if ($request->hasFile("image")) {
                $images = Helper::saveImage($request->file("image"), $user->getPath() . config("constants.profilePath") . "/" . $user->id);
                $user->image = $images["image"];
                $user->thumb = $images["thumb"];
                $user->save();
            }
            return redirect()->route('TraineeProfile')->with("message", __('messages.ProfileSaved'));
        }
    }

    public function TrainerSave(Request $request)
    {
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "password" => "nullable",
            "password_confirmation" => "same:password",
            "email" => Auth::user()->email != $request->get("email") ? "required|email|unique:users,email,NULL,id,deleted_at,NULL" : "required|email"
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return redirect()->route('TrainerProfile')->withErrors($validation->messages());
        } else {
            $user = Auth::user();
            $user->firstName = ucfirst($request->get("firstName"));
            $user->lastName = ucfirst($request->get("lastName"));
            $user->email = strtolower($request->get("email"));
            $user->gender = strtolower($request->get("gender"));
            $user->phone = Helper::formatPhone(strtolower($request->get("phone")));
            $user->birthday = !empty(strtolower($request->get("birthday"))) ? strtolower($request->get("birthday")) : null;
            $user->suite = strtolower($request->get("suite"));
            $user->Address = strtolower($request->get("Address"));
            $user->city = strtolower($request->get("city"));
            $user->street = strtolower($request->get("street"));
            $user->country = strtolower($request->get("country"));
            $user->province = strtolower($request->get("province"));
            $user->userType = "Trainer";
            if ($request->get("timezone")) {
                $user->timezone = $request->get("timezone");
            }
            if ($request->filled("password")) {
                $user->password = Hash::make($request->get("password"));
            }
            $user->save();

            Helper::checkUserFolder($user->id);

            if ($request->hasFile("image")) {
                $images = Helper::saveImage($request->file("image"), $user->getPath() . config("constants.profilePath") . "/" . $user->id);
                $user->image = $images["image"];
                $user->thumb = $images["thumb"];
                $user->save();
            }

            if ($request->hasFile("logo")) {
                $userlogo = new UserLogos;
                $userlogo->userId = $user->id;

                $images = Helper::saveImage($request->file("logo"), $user->getPath() . config("constants.profilePath") . "/" . $user->id);
                $userlogo->image = $images["image"];
                $userlogo->thumb = $images["thumb"];

                UserLogos::where("userId", $user->id)->update(["active" => 0]);
                $userlogo->active = 1;

                $userlogo->save();
            }
            return redirect()->route('TrainerProfile')->with("message", __('messages.ProfileSaved'));
        }
    }

    public function destroy($id)
    {
        $user = Users::find($id);
        $user->delete();
        return response()->json("User Deleted");
    }
    public function logout()
    {
        if (Auth::check()) {
            // Feeds::insertFeed("Logout", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            Auth::logout();
            $lang = Session::get("lang");
            Session::flush();
            if (isset($_COOKIE['TrainerWorkoutUserId'])) {
                unset($_COOKIE['TrainerWorkoutUserId']);
                setcookie('TrainerWorkoutUserId', '', time() - 3600, '/');
            }

            if ($lang != "") {
                Session::put("lang", $lang);
                Session::save();
            }
        }
        return redirect()->route("home");
    }

    public function loginFacebook(Request $request)
    {
        $code = $request->get('code');
        $OAuth = new OAuth();
        $OAuth::setHttpClient('CurlClient');
        $fb = $OAuth::consumer('Facebook', $request->get('redirectUri'));

        if (!empty($code)) {
            $token = $fb->requestAccessToken($code);
            $result = json_decode($fb->request('/me?fields=id,first_name,last_name,email'), true);

            $findUser = Users::where("email", $result["email"])->first();
            if ($findUser) {
                if ($findUser->fbUsername == "") {
                    $findUser->fbUsername = $result["id"];
                    $findUser->save();
                }
                Auth::loginUsingId($findUser->id);
                Event::dispatch('loginWithFacebook', [Auth::user()]);
                Auth::user()->update([
                    'updated_at' => now(),
                    'lastLogin' => now(),
                    'virtual' => 0,
                ]);

                return Auth::user()->userType === "Trainer" ? redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)]) : redirect()->route('traineeWorkouts')
                        ->with("message", __("messages.Welcome"));

            } else {
                $user = new Users;
                $user->fill([
                    'firstName' => ucfirst($result["first_name"]),
                    'lastName' => ucfirst($result["last_name"]),
                    'email' => strtolower($result["email"]),
                    'fbUsername' => $result["id"],
                    'userType' => "Trainee",
                    'password' => Hash::make(Str::random(8))
                ]);

                $image = json_decode(file_get_contents("https://graph.facebook.com/" . $result["id"] . "/picture?type=large&redirect=false"))->data->url;
                $subject = __("messages.Emails_registerFB");

                Mail::queueOn(app()->environment(), 'emails.' . config("app.whitelabel") . '.user.' . app()->getLocale() . '.newFBUser', ["user" => serialize($user), "name" => $user->firstName], function ($message) use ($user, $subject) {
                    $message->to($user->email)->cc(config("constants.activityEmail"))->subject($subject);
                });

                $user->activated = now();
                $user->save();

                Helper::checkUserFolder($user->id);
                if ($image) {
                    $file = file_get_contents($image);
                    $images = Helper::saveImage($file, $user->getPath() . config("constants.profilePath") . "/" . $user->id, $image);
                    $user->update([
                        'image' => $images["image"],
                        'thumb' => $images["thumb"]
                    ]);
                }

                Auth::loginUsingId($user->id);
                Event::dispatch('signUpWithFacebook', [Auth::user()]);
                $user->update(['lastLogin' => now()]);
                $user->freebesTrainer();

                Invites::where("email", $user->email)->where("completed", 0)->update(["completed" => 1]);

                return redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                    ->with("message", __("messages.Welcome"))
                    ->with("newUser", true);
            }
        } else {
            $url = $fb->getAuthorizationUri();
            return redirect()->to((string)$url);
        }
    }

    public function loginTraineeFacebook($inviteKey = "")
    {
        $code = request()->get('code');
        $fb = OAuth::consumer('Facebook');

        if (!empty($code)) {
            $token = $fb->requestAccessToken($code);
            $result = json_decode($fb->request('/me?fields=id,first_name,last_name,email'), true);

            if (is_array($result) && array_key_exists("email", $result)) {
                $findUser = Users::where("email", $result["email"])->first();
                if ($findUser) {
                    if ($findUser->fbUsername == "") {
                        $findUser->fbUsername = $result["id"];
                        $findUser->save();
                    }
                    Auth::loginUsingId($findUser->id);
                    Event::dispatch('loginWithFacebook', [Auth::user()]);
                    Auth::user()->update([
                        'updated_at' => now(),
                        'lastLogin' => now()
                    ]);

                    if ($inviteKey != "") {
                        $invite = Invites::where("key", $inviteKey)->where("completed", 0)->first();
                        if ($invite) {
                            $toId = $findUser->id;
                            $fromId = $invite->fakeId;
                            if ($fromId != Auth::user()->id) {
                                Workouts::copyWorkoutsFromTo($fromId, Auth::user()->id);
                            }
                            $invite->completeInvite();
                        }
                    }

                    return Auth::user()->userType === "Trainer"
                        ? redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                        : redirect()->route('traineeWorkouts')
                            ->with("message", __("messages.Welcome"));
                } else {
                    return redirect()->route("home")->with("error", "It is not possible to login with Facebook at the moment.");
                }
            } else {
                // Additional logic can be implemented as per your requirements
            }
        } else {
            $url = $fb->getAuthorizationUri();
            return redirect()->to((string)$url);
        }
    }


    public function shareOnFacebook()
    {
        $user = Auth::user();
        $message = "";
        $object = null;
        $type = request()->get("type");
        $url = request()->get("link");
        $url = URL::to($url);
        $name = "";

        if ($type == "Exercise") {
            $message = Messages::showFacebookMessage("ShareExercise");
            $object = Exercises::find(request()->get("id"));
            Feeds::insertFeed("SharedExerciseFacebook", $user->id, $user->firstName, $user->lastName);
        } else if ($type == "Workout") {
            $message = Messages::showFacebookMessage("ShareWorkout");
            $object = Workouts::find(request()->get("id"));
            Feeds::insertFeed("SharedWorkoutFacebook", $user->id, $user->firstName, $user->lastName);
        } else {
            $message = Messages::showFacebookMessage("GenericFacebook");
        }

        $response = $user->postFBTimeline($user, $message, $url, ["name" => $object->name], true);
        if ($response["error"]) {
            return $this->responseJsonError($response["message"]);
        } else {
            return $this->responseJson($response["message"]);
        }
    }

    public function demoSignUp()
    {
        $accountType = request()->get("type");

        $rules = ["email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL"];
        $validation = Validator::make(request()->all(), $rules);
        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->messages());
        } else {
            $user = new Users;
            $user->email = strtolower(request()->get("email"));
            if (request()->get("timezone") != "") {
                $user->timezone = request()->get("timezone");
            }
            $user->userType = $accountType;
            $user->save();

            if ($user->userType == "Trainer") {
                try {
                    MailchimpWrapper::lists()->subscribe(config("constants.mailChimpGetEarlyAccessListTrainer"), ['email' => request()->get("email"), 'email_address' => request()->get("status"), 'email' => "subscribed"]);
                } catch (Exception $e) {
                    Log::error($e);
                }
                $user->freebesTrainer();
            } else {
                $user->freebesTrainee();
                try {
                    MailchimpWrapper::lists()->subscribe(config("constants.mailChimpGetEarlyAccessListTrainee"), ['email' => request()->get("email"), 'email_address' => request()->get("status"), 'email' => "subscribed"]);
                } catch (Exception $e) {
                    Log::error($e);
                }
            }

            return view('SignUpComplete');
        }
    }

    public function personifyFromGroup($userId)
    {
        if (!Groups::checkGroupPermissions(Auth::user()->id, $userId)) {
            return redirect()->back()->withErrors(Lang::get("NoPermissions"));
        } else {
            $findUser = Users::find($userId);
            if ($findUser) {
                Session::put("originalUser", Auth::user());
                Auth::loginUsingId($findUser->id);

                return redirect()->route('trainerWorkouts', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                    ->with("message", Lang::get("messages.Welcome"));
            }
        }
    }

    public function personifyFromGroupBack()
    {
        if (Session::has("originalUser")) {
            Auth::loginUsingId(Session::get("originalUser")->id);
            Session::forget('originalUser');
            return redirect()->route('employeeManagement')->with("message", Lang::get("messages.PersonifyBack"));
        }
    }


    //=======================================================================================================================
    // API
    //=======================================================================================================================

    public function APIRegistration()
    {
        if (request()->get("type") == "Trainer") {
            $validation = Users::validate(request()->all(), ["termsAndConditions" => "required"]);
            $user = new Users;
            $user->firstName = ucfirst(request()->get("firstName"));
            $user->lastName = ucfirst(request()->get("lastName"));
            $user->email = strtolower(request()->get("email"));
            $user->password = Hash::make(request()->get("password"));
            $user->userType = "Trainer";
            $user->updated_at = now();
            $user->lastLogin = now();
            $user->lastLoginApp = now();
            $user->save();

            $token = Auth::guard('api')->login($user);
            Event::dispatch('apiSignUp', [Auth::guard('api')->user()]);
            $user->freebesTrainer();
            Feeds::insertFeed("SignUp", Auth::guard('api')->user()->id, Auth::guard('api')->user()->firstName, Auth::guard('api')->user()->lastName);

            $result = Helper::APIOK();
            $result["message"] = Lang::get("messages.Welcome");
            $result["data"] = Auth::guard('api')->user();
            $result["data"]['token'] = $token;
            return $result;
        } else {
            $user = new Users;
            $user->firstName = ucfirst(request()->get("firstName"));
            $user->lastName = ucfirst(request()->get("lastName"));
            $user->email = strtolower(request()->get("email"));
            $user->password = Hash::make(request()->get("password"));
            $user->userType = "Trainee";
            $user->updated_at = now();
            $user->lastLogin = now();
            $user->lastLoginApp = now();
            $user->save();

            $token = Auth::guard('api')->login($user);
            Event::dispatch('apiSignUp', [Auth::guard('api')->user()]);
            $user->freebesTrainee();
            Feeds::insertFeed("SignUp", Auth::guard('api')->user()->id, Auth::guard('api')->user()->firstName, Auth::guard('api')->user()->lastName);

            $result = Helper::APIOK();
            $result["message"] = Lang::get("messages.Welcome");
            $result["data"] = Auth::guard('api')->user();
            $result["data"]['token'] = $token;
            return $result;
        }
    }


    public function APIlogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $result = [
            "data" => "",
            "status" => "error",
            "message" => Lang::get("messages.WrongLogin"),
            "total" => ""
        ];

        if ($token = Auth::guard('api')->attempt(['email' => request()->get("email"), 'password' => request()->get("password")], true)) {
            Feeds::insertFeed("Welcome", Auth::guard('api')->user()->id, Auth::guard('api')->user()->firstName, Auth::guard('api')->user()->lastName);
            Event::dispatch('apiLogin', [Auth::guard('api')->user()]);
            $user = Auth::guard('api')->user();
            $user->appInstalled = 1;
            $user->updated_at = now();
            $user->lastLogin = now();
            $user->lastLoginApp = now();
            $user->save();

            $result["data"] = Auth::guard('api')->user()->toArray();
            $result["data"]['token'] = $token;
            $result["data"]["weight"] = Weights::where("userId", Auth::guard('api')->user()->id)->orderBy("created_at", "desc")->get();
            $result["data"]["objectives"] = Objectives::where("userId", Auth::guard('api')->user()->id)->orderBy("created_at", "desc")->get();
            $result["status"] = "ok";
            $result["message"] = Lang::get("messages.Welcome");
            return $this->responseJson($result);
        } else {
            return $this->responseJson($result);
        }
    }
    public function APIloginAuto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $result = [
            "data" => "",
            "status" => "error",
            "message" => Lang::get("messages.WrongLogin"),
            "total" => ""
        ];

        $user = Users::where("email", $request->get("email"))->first();

        if ($user) {
            $user->updated_at = now();
            $user->lastLogin = now();
            $user->lastLoginApp = now();
            $user->save();

            $token = Auth::guard('api')->login($user);
            Event::dispatch('apiLogin', [Auth::guard('api')->user()]);
            Feeds::insertFeed("Welcome", Auth::guard('api')->user()->id, Auth::guard('api')->user()->firstName, Auth::guard('api')->user()->lastName);

            $result["data"] = Auth::guard('api')->user()->toArray();
            $result["data"]["token"] = $token;
            $result["data"]["weight"] = Weights::where("userId", Auth::guard('api')->user()->id)->orderBy("created_at", "DESC")->get();
            $result["data"]["objectives"] = Objectives::where("userId", Auth::guard('api')->user()->id)->orderBy("created_at", "DESC")->get();
            $result["status"] = "ok";
            $result["message"] = Lang::get("messages.Welcome");
        }

        return $this->responseJson($result);
    }

    public function APIlogout()
    {
        $result = Helper::APIOK();

        if (Auth::check()) {
            Auth::logout();
        }

        return $result;
    }

    public function APIAppSettings(Request $request)
    {
        if ($request->get("action") == "app_initiated") {
            $user = Auth::user();
            $user->demoApp = 1;
            $user->save();
        }
    }

    public function APIEditProfile(Request $request)
    {
        $result = Helper::APIERROR();
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "email" => "required|email",
        ];

        if (Auth::check()) {
            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                $result["message"] = $validation->messages();
            } else {
                $user = Auth::user();
                $user->firstName = ucfirst($request->get("firstName", $user->firstName));
                $user->lastName = ucfirst($request->get("lastName", $user->lastName));
                $user->email = strtolower($request->get("email", $user->email));
                $user->phone = Helper::formatPhone($request->get("phone", $user->phone));
                $user->birthday = $request->get("birthday", $user->birthday);
                $user->timezone = $request->get("timezone", $user->timezone);

                if ($request->get("password")) {
                    $user->password = Hash::make($request->get("password"));
                }

                $user->save();
                Helper::checkUserFolder($user->id);

                if ($request->hasFile("image0")) {
                    $images = Helper::saveImage($request->file("image0"), $user->getPath() . config("constants.profilePath") . "/" . $user->id);
                    $user->image = $images["image"];
                    $user->thumb = $images["thumb"];
                    $user->save();
                }

                $result = Helper::APIOK();
                $result["message"] = Lang::get("messages.ProfileSaved");
            }
        } else {
            $result["message"] = Lang::get("messages.LoginRequired");
        }

        return $result;
    }

    public function _index()
    {
        return View::make('ControlPanel.Users');
    }

    public function _ApiList(Request $request)
    {
        $response = Users::orderBy("id", "DESC")->latest();
        return DataTables::eloquent($response)
            ->addIndexColumn()
            ->make(true);
    }

    public function _AddEdit(Request $request)
    {
        if ($request->has("hiddenId") && !empty($request->get("hiddenId"))){
            return $this->_update($request->get("hiddenId"),$request);
        }else{
            return $this->_create($request);
        }
    }

    public function _create(Request $request)
    {
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "email" => "required|email|unique:users,email,NULL,id,deleted_at,NULL",
            "certifications" => "max:1000",
            "past_experience" => "max:1000",
            "biography" => "max:1000",
            "specialities" => "max:1000"
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        }

        $user = new Users;
        $user->fill($request->except('password'));
        if ($request->get("password")) $user->password = Hash::make($request->get("password"));
        $user->save();
        $user->freebesTrainer();

        return $this->responseJson(Messages::showControlPanel("UserCreated"));
    }

    public function _show($user)
    {
        $user = Users::find($user);
        return $user;
    }

    public function _update($id,Request $request)
    {
        $rules = [
            "firstName" => "required|min:2",
            "lastName" => "required|min:2",
            "certifications" => "max:1000",
            "past_experience" => "max:1000",
            "biography" => "max:1000",
            "specialities" => "max:1000"
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->responseJsonErrorValidation($validation->messages());
        }

        $user = Users::find($id);
        $user->update($request->except('password'));

        if ($request->get("password")) {
            $user->password = Hash::make($request->get("password"));
        }

        $user->save();

        return $this->responseJson(Messages::showControlPanel("UserModified"));
    }

    public function _destroy($id)
    {
        $user = Users::find($id);
        $user->delete();

        return $this->responseJson(Messages::showControlPanel("UserDeleted"));
    }

    public function controlPanelAPIList()
    {
        return response()->json(["data" => Users::orderBy("created_at", "DESC")->get()]);
    }

    public function controlPanelLoginUserAdmin($id)
    {
        $findUser = Users::find($id);

        if ($findUser) {
            Auth::loginUsingId($findUser->id);

            if (Auth::user()->userType === "Trainer") {
                Tasks::dailyReminderChecker();
                return redirect()->route('Trainer', ['username' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                    ->with("message", Lang::get("messages.Welcome"));
            }

            return redirect()->route('Trainee', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                ->with("message", Lang::get("messages.Welcome"));
        }
    }

}
