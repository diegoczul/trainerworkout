<?php

namespace App\Http\Controllers\Web;

use App\Mail\FeedbackMail;
use App\Models\Appointments;
use App\Models\Availabilities;
use App\Models\Friends;
use App\Models\Invites;
use App\Models\Measurements;
use App\Models\Notifications;
use App\Models\Objectives;
use App\Models\Permissions;
use App\Models\SessionsUsers;
use App\Models\Sharings;
use App\Models\Tags;
use App\Models\Tasks;
use App\Models\TemplateSets;
use App\Models\UserUpdates;
use App\Models\WorkoutsGroups;
use App\Models\Workoutsperformances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\MembershipsUsers;
use App\Models\Memberships;
use App\Models\Exercises;
use App\Models\WorkoutsExercises;
use App\Models\Feeds;
use App\Models\Clients;
use App\Models\Weights;
use App\Models\Pictures;
use App\Models\Workouts;
use Illuminate\Support\Str;
use UsersSettings;

class SystemController extends BaseController
{
    public function index()
    {
        return View::make("ControlPanel.index");
    }

    public function syncWithStripeAndCheckMemberships(Request $request)
    {
        $stripeKey = Config::get("app.debug") ? Config::get("constants.STRIPETestsecret_key") : Config::get("constants.STRIPEsecret_key");
        \Stripe\Stripe::setApiKey($stripeKey);

        $users = Users::all();
        $interval = "month";
        $quantity = 1;
        $today = date("Y-m-d");

        foreach ($users as $user) {
            $usermems = MembershipsUsers::where("userId", $user->id)->orderBy("subscriptionStripeKey", "DESC")->get();
            $first = true;

            if ($usermems->count() > 0) {
                foreach ($usermems as $usermem) {
                    if (!$first && empty($usermem->subscriptionStripeKey)) {
                        $usermem->delete();
                    }
                    $first = false;
                }
            } else {
                $membership = Memberships::find(Config::get("constants.freeTrialMembershipId"));
                $interval = match ($membership->durationType) {
                    "yearly" => "years",
                    "monthly" => "months",
                    default => "days",
                };

                MembershipsUsers::create([
                    'membershipId' => Config::get("constants.freeTrialMembershipId"),
                    'expiry' => date('Y-m-d', strtotime("$today + $quantity $interval")),
                    'registrationDate' => $today,
                    'userId' => $user->id,
                ]);
            }

            $subscription = $user->getStripeSubscription();
            if ($subscription) {
                $user->updateStripeMembership($subscription->plan->id);
            } else {
                $membership = $user->getTrainerWorkoutMembership();
                if ($membership && $membership->hasMemberhsipExpired()) {
                    $user->updateToMembership(Config::get("constants.freeTrialMembershipId"));
                } elseif (!$membership) {
                    $user->updateToMembership(Config::get("constants.freeTrialMembershipId"));
                }
            }
        }
    }

    public function fixUsedExercises()
    {
        $exercises = Exercises::all();
        foreach ($exercises as $exercise) {
            $exercise->update(['used' => WorkoutsExercises::where("exerciseId", $exercise->id)->count()]);
        }
    }

    public function dailyActivity(Request $request)
    {
        Log::info("Running DAILY ACTIVITY");
        $this->ControlPanelFeeds();
        Log::info("Sending confirmation Emails DAILY ACTIVITY");
        $this->reminderConfirmEmails();
        Log::info("Syncing with Stripe");
        $this->syncWithStripeAndCheckMemberships($request);
    }

    public function reminderConfirmEmails()
    {
        $users = Users::whereNull("activated")->orWhere("activated", "=", "")->get();
        $intervals = [2, 5, 10, 15];

        foreach ($intervals as $interval) {
            foreach ($users as $user) {
                $toCompareDate = date('Y-m-d', strtotime($user->created_at . " + $interval days"));
                if (empty($user->token)) {
                    $user->update(['token' => Str::uuid()]);
                }
                if ($toCompareDate == date("Y-m-d")) {
                    $lang = $user->lang ?: "en";
                    $subject = __("messages.Emails_ReminderTrainerWorkoutEmailConfirmation");
                    Mail::queue('emails.' . Config::get("app.whitelabel") . ".user.$lang.reminderActivateEmail", compact('user'), function ($message) use ($user, $subject) {
                        $message->to($user->email)->subject($subject);
                    });
                }
            }
        }
    }

    public function sendFeedback(Request $request)
    {
        $feedback = $request->get("feedback");
        $date = now()->toDateString();
        $user = Auth::user();
        $email = Config::get("app.feedbackEmail");
        Mail::to($email)->queue(new FeedbackMail($date, $user, $feedback));

        $message = __("messages.thankyoufeedback");
        $username = strtolower(Auth::user()->firstName.Auth::user()->lastName);
        return Auth::check() ? match (Auth::user()->userType) {
                "Trainer" => redirect()->route("trainerWorkouts",['userName' => $username])->with("message", $message),
                "Trainee" => redirect()->route("traineeWorkouts",['userName' => $username])->with("message", $message),
                default => redirect()->route("home")->with("message", $message),
            } : redirect()->route("home")->with("message", $message);
    }

    public function weeklyActivity()
    {
    }

    public function ControlPanelFeeds()
    {
        $feeds = Feeds::with("user")->whereNull("reported_at")->orderBy("reported_at", "Desc")->get();
        $date = now()->toDateString();
        $email = Config::get("mail.username");

        Feeds::whereNull("reported_at")->update(['reported_at' => $date]);
        Mail::queue('ControlPanel.emails.feeds', compact('date', 'feeds'), function ($message) use ($email, $date) {
            $message->to($email)->subject("Activity of $date");
        });
    }

    public static function sendTrainerClientWorkoutRevision()
    {
        $trainers = Users::where("userType", "Trainer")->get();

        foreach ($trainers as $trainer) {
            $interval = UsersSettings::getValueOrDefault($trainer->id, "setting_workout_reminder", 14);
            $times = UsersSettings::getValueOrDefault($trainer->id, "setting_workout_reminder_number", 8);
            $clients = Clients::where("trainerId", $trainer->id)->where("userId", "!=", Config::get("constants.onboardingClient"))->get();

            foreach ($clients as $client) {
                $workouts = Workouts::where("authorId", $trainer->id)->where("userId", $client->userId)->get();
                foreach ($workouts as $workout) {
                    $date = $workout->lastRevized ?: $workout->created_at;
                    $toCompareDate = date('Y-m-d', strtotime("$date + $interval days"));
                    if ($toCompareDate < now()->toDateString() || $times < $workout->timesPerformedRevized) {
                        $workout->update(['lastRevized' => now(), 'timesPerformedRevized' => 0]);
                    }
                }
            }
        }
    }

    public static function sendTrainerWeightReminder()
    {
        $trainers = Users::where("userType", "Trainer")->get();
        foreach ($trainers as $trainer) {
            $interval = UsersSettings::getValueOrDefault($trainer->id, "setting_weight_reminder_number", 14);
            $clients = Clients::where("trainerId", $trainer->id)->where("userId", "!=", Config::get("constants.onboardingClient"))->get();

            foreach ($clients as $client) {
                $lastWeight = Weights::where("userId", $client->user->id)->latest()->first();
                if ($lastWeight) {
                    $date = $lastWeight->reminded ?: $lastWeight->created_at;
                    $toCompareDate = date('Y-m-d', strtotime("$date + $interval days"));
                    if ($toCompareDate < now()->toDateString()) {
                        $lastWeight->update(['reminded' => now()]);
                    }
                }
            }
        }
    }

    public static function sendTrainerPicturesReminder()
    {
        $trainers = Users::where("userType", "Trainer")->get();

        foreach ($trainers as $trainer) {
            $interval = UsersSettings::getValueOrDefault($trainer->id, "setting_pictures_reminder_number", 14);
            $clients = Clients::where("trainerId", $trainer->id)->where("userId", "!=", Config::get("constants.onboardingClient"))->get();

            foreach ($clients as $client) {
                $picture = Pictures::where("userId", $client->user->id)->latest()->first();
                if ($picture) {
                    $date = $picture->reminded ?: $picture->created_at;
                    $toCompareDate = date('Y-m-d', strtotime("$date + $interval days"));
                    if ($toCompareDate < now()->toDateString()) {
                        $picture->update(['reminded' => now()]);
                    }
                }
            }
        }
    }

    public static function sendTrainerMeasurementsReminder()
    {
        $trainers = Users::where("userType", "Trainer")->get();

        foreach ($trainers as $trainer) {
            $interval = 14; //DEFAULT 14 DAYS reminder
            $setting = UsersSettings::where("userId", $trainer->id)->where("name", "setting_measurements_reminder_number")->first();
            if ($setting) $interval = $setting->value;
            $clients = Clients::where("trainerId", $trainer->id)->where("userId", "!=", Config::get("constants.onboardingClient"))->get();
            foreach ($clients as $client) {
                $measurement = Measurements::where("userId", $client->user->id)->orderBy("created_at", "DESC")->first();
                if ($measurement) {
                    $date = $measurement->created_at;
                    if ($measurement->reminded != "") $date = $measurement->reminded;
                    $toCompareDate = date('Y-m-d', strtotime($date . " + " . $interval . " days"));
                    if ($toCompareDate < date("Y-m-d")) {
                        $measurement->reminded = date("Y-m-d H:i:s");
                        $measurement->save();
                    }
                }
            }
        }
    }

    public static function sendTrainerInactiveReminder()
    {
        $trainers = Users::where("userType", "Trainer")->get();

        foreach ($trainers as $trainer) {
            $interval = 14; //DEFAULT 14 DAYS reminder
            $setting = UsersSettings::where("userId", $trainer->id)->where("name", "setting_weight_reminder_number")->first();
            if ($setting) $interval = $setting->value;
            $clients = Clients::where("trainerId", $trainer->id)->where("userId", "!=", Config::get("constants.onboardingClient"))->get();
            foreach ($clients as $client) {
                $date = $client->user->created_at;
                if ($client->user->updated_at != "") $date = $client->user->updated_at;
                $toCompareDate = date('Y-m-d', strtotime($date . " + " . $interval . " days"));
                if ($toCompareDate < date("Y-m-d")) {
                    $client->user->updated_at = date("Y-m-d H:i:s");
                    $client->user->save();
                }
            }
        }
    }

    public function migrateWorkout($fromUserId, $toUserId)
    {
        $newWorkout = Workouts::copyWorkoutsFromTo($fromUserId, $toUserId);
    }

    public function migrateWorkouts($workoutNumber = "") {
        $cutInDate = "2016-08-23";

        ini_set('max_execution_time', 420000);
        set_time_limit(420000);

        if ($workoutNumber == "") {
            $workouts = Workouts::where("created_at", "<", $cutInDate)
                ->whereIn("id", array())
                ->orderBy("created_at", "DESC")
                ->get();
        } else {
            $workouts = Workouts::where("id", "=", $workoutNumber)->get();
        }

        $json = [];
        $jsonRest = [];

        foreach ($workouts as $workout) {
            $groups = WorkoutsGroups::where("workoutId", $workout->id)->get();

            foreach ($groups as $group) {
                $workoutExercises = WorkoutsExercises::where("workoutId", $workout->id)
                    ->where("groupId", $group->id)
                    ->orderBy("id", "ASC")
                    ->get();

                $jsonGroup = [];
                $jsonRestGroup = new \stdClass();

                if ($workoutExercises->count() > 1) {
                    $arr = [];
                    $jsonRestGroup->circuitStyle = $group->circuitType ?: "rounds";
                    $arrayRestBetweenCircuitExercises = unserialize($group->restBetweenCircuitExercises);

                    if (is_array($arrayRestBetweenCircuitExercises)) {
                        foreach ($arrayRestBetweenCircuitExercises as $element) {
                            $arr[] = $element;
                        }
                    }

                    $jsonRestGroup->restBetweenCircuitExercises = $arr;

                    if ($jsonRestGroup->circuitStyle == "emom") {
                        $jsonRestGroup->circuitEmom = $group->circuitEmom ?: 1;
                    }

                    if ($jsonRestGroup->circuitStyle == "amrap") {
                        $jsonRestGroup->circuitMaxTime = $group->circuitMaxTime ?: 1;
                    }

                    $jsonRestGroup->circuitRound = $group->intervals ?: 1;
                    $jsonRestGroup->circuitRest = $group->rest;
                }
                $jsonRestGroup->type = $group->type;
                $jsonRestGroup->restTime = $group->restAfter;

                foreach ($workoutExercises as $exercise) {
                    $sub = new \stdClass();

                    $sub->repType = $exercise->metric ?: "rep";
                    if ($sub->repType == "reps") $sub->repType = "rep";
                    $sub->metric = $exercise->units ?: "imperial";
                    $sub->notes = $exercise->notes ?: "";
                    $sub->tempo1 = $exercise->tempo1 ?: "";
                    $sub->tempo2 = $exercise->tempo2 ?: "";
                    $sub->tempo3 = $exercise->tempo3 ?: "";
                    $sub->tempo4 = $exercise->tempo4 ?: "";
                    $sub->restBetweenSets = [];

                    $ex = Exercises::withTrashed()->find($exercise->exerciseId);
                    $ex->equipmentId = $exercise->equipmentId;

                    $sub->exercise = $ex;

                    $sets = TemplateSets::where("workoutsExercisesId", $exercise->id)
                        ->orderBy("number", "ASC")
                        ->get();

                    $repsType = [];
                    $weights = [];
                    $reps = [];
                    $speeds = [];
                    $distances = [];
                    $times = [];
                    $hrs = [];
                    $restBetweenSets = [];

                    foreach ($sets as $set) {
                        $repsType[] = $set->metric ?: "rep";

                        if ($set->metric == "time" || ($set->metric == "rep" && $set->type == "cardio")) {
                            $reps[] = $set->time;
                        } else {
                            $reps[] = $set->reps;
                        }

                        $weights[] = $set->weight ?: 0;

                        $speeds[] = $set->speed ?: "";
                        $distances[] = $set->distance ?: "";
                        $times[] = $set->time ?: "";
                        $hrs[] = $set->bpm ?: "";
                        $restBetweenSets[] = $set->rest;
                    }

                    array_pop($restBetweenSets);
                    $sub->repsType = $repsType;
                    $sub->weights = $weights;
                    $sub->hrs = $hrs;
                    $sub->repArray = $reps;
                    $sub->speeds = $speeds;
                    $sub->times = $times;
                    $sub->distances = $distances;
                    $sub->restBetweenSets = $restBetweenSets;

                    $jsonGroup[] = $sub;
                }

                $json[] = $jsonGroup;
                $jsonRest[] = $jsonRestGroup;
            }

            $workout->exerciseGroup = json_encode($json);
            $workout->exerciseGroupRest = json_encode($jsonRest);

            $workout->save();
        }
    }

    public function changeLanguange($locale, Request $request) {
        $url = URL::previous();
        $url = str_replace(Config::get("app.url"), "", $url);

        $routes = Lang::get("routes");
        $routes = array_flip($routes);
        $base = $routes[$url] ?? "";

        App::setLocale($locale);
        Session::put("lang", $locale);
        Session::save();

        if (Auth::check()) {
            Auth::user()->lang = $locale;
            Auth::user()->save();
        }

        $urlTranslated = Lang::get("routes." . $base);

        if ($base == "") {
            if (!$request->header('referer')) {
                return Redirect::route("home");
            } else {
                return Redirect::back()->with('message', Lang::get("messages.LanguageChanged"));
            }
        } else {
            return Redirect::to($urlTranslated)->with('message', Lang::get("messages.LanguageChanged"));
        }
    }

    public function _indexScripts() {
        return View::make("ControlPanel.MaintenanceScripts")
            ->with("users", Users::select(DB::raw("concat('id: ', id, ' - ', firstName, ' ', lastName, ' ', email) as name"), "id")
                ->orderBy("firstName", "ASC")
                ->orderBy("lastName", "ASC")
                ->pluck("name", "id"))
            ->with("workouts", Workouts::withTrashed()
                ->with("user")
                ->orderBy("workouts.id", "DESC")
                ->get());
    }

    public function fixExercisesTranslations() {
        $previous_locale = App::getLocale();
        $outputToPrint = [];

        $exercises = Exercises::all();
        foreach ($exercises as $exercise) {
            $name = "";
            $has = [];
            $dontHave = [];

            foreach (Config::get("app.locale_available") as $locale) {
                $translation = $exercise->getTranslation($locale, false);
                if (!$translation || $translation->name == "") {
                    $dontHave[] = $locale;
                } else {
                    $has[] = $locale;
                }
            }

            if (count($dontHave) > 0) {
                $winLocale = "en";
                if (count($has) == 1 && $has[0] == "en") $winLocale = $has[0];
                if (count($has) > 1 && in_array($has, "en")) {
                    $winLocale = "en";
                } else {
                    if (count($has) == 0) $winLocale = "en";
                }
            }

            foreach ($dontHave as $dont) {
                $translation = $exercise->getTranslation($locale, false);
                if (!$translation || $translation->name == "") {
                    $ex = $exercise->translateOrNew($dont);
                    $final = "";
                    $subTranslation = $exercise->getTranslation($winLocale, false);
                    $row = DB::select(DB::raw("Select * from exercises where id = " . $exercise->id));
                    if ($subTranslation && $subTranslation->name != "") {
                        $final = $subTranslation->name;
                    } else if (is_array($row) && array_key_exists(0, $row) && $row[0]->name != "") {
                        $final = $row[0]->name;
                    } else {
                        $final = "NEEDS TRANSLATIONS ON ALL LANGUAGES";
                    }
                    $ex->name = $final;
                    $ex->exercises_id = $exercise->id;
                    $ex->created_at = date('Y-m-d H:i:s');
                    $ex->save();
                    $outputToPrint[] = $ex;
                }
            }
        }

        App::setLocale($previous_locale);

        return $this::responseJson($outputToPrint);
    }

    public function removeUserFromDatabase(Request $request) {
        $userId = $request->get("userId");

        Appointments::where("userId", $userId)->forceDelete();
        Availabilities::where("userId", $userId)->forceDelete();
        Clients::where("userId", $userId)->delete();
        Feeds::where("userId", $userId)->forceDelete();
        Friends::where("userId", $userId)->forceDelete();
        Invites::where("userId", $userId)->forceDelete();
        Measurements::where("userId", $userId)->forceDelete();
        MembershipsUsers::where("userId", $userId)->forceDelete();
        Notifications::where("userId", $userId)->forceDelete();
        Objectives::where("userId", $userId)->forceDelete();
        Permissions::where("userId", $userId)->forceDelete();
        Pictures::where("userId", $userId)->forceDelete();
        SessionsUsers::where("userId", $userId)->forceDelete();
        Sharings::where("fromUser", $userId)->forceDelete();
        Tags::where("userId", $userId)->forceDelete();
        Tasks::where("userId", $userId)->forceDelete();
        UserUpdates::where("userId", $userId)->forceDelete();
        Weights::where("userId", $userId)->forceDelete();
        Workoutsperformances::where("userId", $userId)->delete();

        $workouts = Workouts::where("userId", $userId)->get();
        foreach ($workouts as $workout) {
            $workout->forceDelete();
        }

        Users::where("id", $userId)->delete();

        return $this::responseJson("Completed");
    }

    public function restoreWorkout(Request $request) {
        $workoutId = $request->get("workoutId");

        $workout = Workouts::withTrashed()->find($workoutId);
        $workout->restore();
        return $this::responseJson("Completed");
    }

    public function workoutsToRestore(Request $request) {
        $workoutId = $request->get("workoutId");

        $workout = Workouts::withTrashed()->find($workoutId);
        $workout->restore();
        return $this::responseJson("Completed");
    }

}
