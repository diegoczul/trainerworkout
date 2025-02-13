<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use App\Models\Clients;
use App\Models\Memberships;
use App\Models\Users;
use App\Models\Invites;
use App\Models\Friends;
use App\Models\Notifications;
use App\Models\Feeds;
use App\Models\Workoutsperformances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class ClientsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $total = 0;
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        if ($request->get("search")) {
            $search = $request->get("search");
            $clients = Clients::whereHas('user', function ($query) use ($search) {
                $query->where(function ($query2) use ($search) {
                    $query2->orWhere('firstName', 'LIKE', "%$search%")
                        ->orWhere('lastName', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                });
            })->where("trainerId", $userId)
                ->orderBy('updated_at', 'DESC')
                ->take($this->pageSize)
                ->get();
            $total = Clients::whereHas('user', function ($query) use ($search) {
                $query->where(function ($query2) use ($search) {
                    $query2->orWhere('firstName', 'LIKE', "%$search%")
                        ->orWhere('lastName', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                });
            })->where("trainerId", $userId)
                ->orderBy('updated_at', 'DESC')
                ->count();
        } else {
            $clients = Clients::with("user")
                ->where("trainerId", $userId)
                ->orderBy('updated_at', 'DESC')
                ->take($this->pageSize)
                ->get();
            $total = Clients::where("trainerId", $userId)->count();
        }

        return View::make("widgets.base.clients")
            ->with("clients", $clients)
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", $total);
    }

    public function showClients()
    {
        return View::make("trainer.clients");
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions(Auth::id(), $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::id(), null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize += $request->get("pageSize");
        }

        return View::make("widgets.full.clients")
            ->with("clients", Users::where("userId", $userId)->orderBy('recordDate', 'ASC')->take($this->pageSize)->get())
            ->with("permissions", $permissions)
            ->with("user", Auth::user())
            ->with("total", Users::where("userId", $userId)->count());
    }

    public function showClientList()
    {
        $emails = Clients::select("email")
            ->distinct()
            ->where("trainerId", Auth::id())
            ->whereNotNull("email")
            ->leftJoin("users", "users.id", "=", "userId")
            ->pluck("email");
        return response()->json($emails);
    }

    public function modifyClient(Request $request)
    {
        $user = Clients::find($request->get("client"));
        if ($user) {
            if ($user->user->virtual == 1 || $user->user->lastLogin == null) {
                $trainee = $user->user;
                $trainee->firstName = $request->get("firstName");
                $trainee->lastName = $request->get("lastName");
                $trainee->email = $request->get("email");
                $trainee->phone = Helper::formatPhone($request->get("phone"));
                $trainee->save();
                return response()->json(Lang::get("messages.ProfileSaved"));
            } else {
                return response()->json(["error" => Lang::get("messages.NotControlAccount")]);
            }
        }
    }

    public function clientProfile($clientId, $clientName = "")
    {
        $userId = Auth::id();
        $user = Clients::find($clientId);

        if ($user) {
            $permissions = null;
            if (request()->has("userId")) {
                $permissions = Helper::checkPremissions(Auth::id(), request()->get("userId"));
                if ($permissions["view"]) {
                    $userId = request()->get("userId");
                }
            } else {
                $permissions = Helper::checkPremissions(Auth::id(), null);
            }

            if (request()->has("pageSize")) {
                $this->pageSize += request()->get("pageSize");
            }

            $performances = Workoutsperformances::where("forTrainer", Auth::id())
                ->where("userId", $user->userId)
                ->whereNotNull("dateCompleted")
                ->count();

            return View::make("trainer.client")
                ->with("performances", $performances)
                ->with("user", $user)
                ->with("client", $user);
        }

        return redirect()->route('Trainer', ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
            ->withErrors(Lang::get("messages.UserNotFound"));
    }

    public function subscribeClient(Request $request)
    {
        $id = $request->get("clientId");
        $client = Clients::find($id);
        if ($client) {
            if ($request->get("subscribeToClient") == "true") {
                $client->subscribeClient = 1;
                $client->save();
                $clientName = $client->user ? $client->user->getCompleteName() : "";
                return response()->json(Lang::get("messages.SubscribedToClient", ["client" => $clientName]));
            } else {
                $client->subscribeClient = 0;
                $client->save();
                $clientName = $client->user ? $client->user->getCompleteName() : "";
                return response()->json(Lang::get("messages.NotSubscribedToClient", ["client" => $clientName]));
            }
        }

        return response()->json(["error" => Lang::get("messages.NotSubscribedToClient")]);
    }

    public function confirmClientByInvitation($invite)
    {
        $invite = Invites::where("key", $invite)->first();
        if ($invite) {
            $user = Users::find($invite->fakeId);
            $trainer = Users::find($invite->userId);
            if ($user && $trainer) {
                $client = Clients::where("trainerId", $invite->userId)
                    ->where("userId", $user->id)
                    ->first();
                $client->approvedClient = 1;
                $client->save();
                $invite->viewed = 1;
                $invite->completed = 1;
                $invite->save();

                if (Friends::where("followingId", $user->id)->where("userId", $trainer->id)->count() == 0) {
                    $friends = new Friends;
                    $friends->followingId = $user->id;
                    $friends->userId = $trainer->id;
                    $friends->chat = 1;
                    $friends->save();
                    Notifications::insertDynamicNotification("Following", $user->id, $trainer->id, ["firstName" => $trainer->firstName, "lastName" => $trainer->lastName]);
                    Feeds::insertDynamicFeed("Following", $trainer->id, $user->id, ["firstName" => $trainer->firstName, "lastName" => $trainer->lastName, "friendFirstName" => $user->firstName, "friendLastName" => $user->lastName]);
                }

                if (Friends::where("userId", $trainer->id)->where("followingId", $user->id)->count() == 0) {
                    $friends = new Friends;
                    $friends->followingId = $trainer->id;
                    $friends->userId = $user->id;
                    $friends->chat = 1;
                    $friends->save();
                    Notifications::insertDynamicNotification("Following", $trainer->id, $user->id, ["firstName" => $user->firstName, "lastName" => $user->lastName]);
                    Feeds::insertDynamicFeed("Following", $user->id, $trainer->id, ["firstName" => $user->firstName, "lastName" => $user->lastName, "friendFirstName" => $trainer->firstName, "friendLastName" => $trainer->lastName]);
                }

                Feeds::insertDynamicFeed("NewTrainer", $user->id, $trainer, ["userName" => $user->getCompleteName(), "firstName" => $user->firstName, "lastName" => $user->lastName, "trainerName" => $trainer->getCompleteName(), "link" => $trainer->getURL()]);
                Notifications::insertDynamicNotification("ClientAccepted", $client->trainer, $client->user, ["userName" => $user->getCompleteName(), "firstName" => $user->firstName, "lastName" => $user->lastName]);

                if (Auth::check()) {
                    if (array_key_exists("HTTP_REFERER", $_SERVER) && $_SERVER['HTTP_REFERER'] != "") {
                        return response()->json(Lang::get("messages.TrainerConfirmed"));
                    } else {
                        return redirect()->route(Auth::user()->userType, ['userName' => Helper::formatURLString(Auth::user()->firstName . Auth::user()->lastName)])
                            ->with("message", Lang::get("messages.TrainerConfirmed"));
                    }
                } else {
                    return redirect()->route('home')->with("message", Lang::get("messages.TrainerConfirmed"));
                }
            }
        }

        return response()->json(["error" => Lang::get("messages.NotFound")]);
    }

    public function addClientTrainer(Request $request)
    {
        $validation = Validator::make($request->all(), ["firstName" => "required"]);

        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $user = Users::where("email", $request->get("email"))->first();

            if (!$user) {
                $user = new Users();
                $user->userType = "Trainee";
                $user->firstName = $request->get("firstName");
                $user->lastName = $request->get("lastName");
                $user->email = $request->get("email");
                $user->phone = Helper::formatPhone($request->get("phone"));
                $user->virtual = $request->get("clientLink") === "Yes" ? 0 : 1;
                $user->save();
            }

            $subscribe = $request->get("subscribe") === "Yes";
            $message = $request->get("personalizedTxt");

            if (Clients::where("userId", $user->id)->where("trainerId", Auth::user()->id)->count() == 0) {
                $client = Auth::user()->addClient($user, null, $subscribe, $message);
                return $this::responseJson(Lang::get("messages.ClientInvitation"));
            } else {
                return $this::responseJsonError(Lang::get("messages.ClientAlreadyInvited"));
            }
        }
    }

    public function addClient(Request $request)
    {
        $addType = "New";
        $response = Memberships::checkMembership(Auth::user());

        if ($response == "") {
            if ($request->filled("user")) {
                $user = Users::find($request->get("user"));

                if (Clients::where("userId", $user->id)->where("trainerId", Auth::user()->id)->count() > 0) {
                    return $this::responseJsonError(Lang::get("messages.ClientAlreadyInvited"));
                }

                $type = "ClientRequest";
                $client = new Clients;
                $client->userId = $user->id;
                $client->trainerId = Auth::user()->id;
                $client->approvedClient = 0;
                $client->approvedTrainer = 1;
                $client->save();

                $invite = new Invites;
                $invite->userId = Auth::user()->id;
                $invite->fakeId = $user->id;
                $invite->firstName = $user->firstName;
                $invite->lastName = $user->lastName;
                $invite->email = $user->email;
                $invite->key = GUID::generate();
                $invite->type = "ClientRequest";
                $invite->save();

                if (Friends::where("followingId", $user->id)->where("userId", Auth::user()->id)->count() == 0) {
                    $friends = new Friends;
                    $friends->followingId = $user->id;
                    $friends->userId = Auth::user()->id;
                    $friends->chat = 1;
                    $friends->save();

                    if ($user) {
                        Notifications::insertDynamicNotification("Following", $user->id, Auth::user()->id, [
                            "firstName" => Auth::user()->firstName,
                            "lastName" => Auth::user()->lastName,
                        ]);

                        Feeds::insertDynamicFeed("Following", Auth::user()->id, $user->id, [
                            "firstName" => Auth::user()->firstName,
                            "lastName" => Auth::user()->lastName,
                            "friendFirstName" => $user->firstName,
                            "friendLastName" => $user->lastName,
                        ]);
                    }
                }

                if (Friends::where("followingId", Auth::user()->id)->where("userId", $user->id)->count() == 0) {
                    $friends = new Friends;
                    $friends->followingId = Auth::user()->id;
                    $friends->userId = $user->id;
                    $friends->chat = 1;
                    $friends->save();

                    if ($user) {
                        Notifications::insertDynamicNotification("Following", Auth::user()->id, $user->id, [
                            "firstName" => $user->firstName,
                            "lastName" => $user->lastName,
                        ]);

                        Feeds::insertDynamicFeed("Following", $user->id, Auth::user()->id, [
                            "firstName" => $user->firstName,
                            "lastName" => $user->lastName,
                            "friendFirstName" => Auth::user()->firstName,
                            "friendLastName" => Auth::user()->lastName,
                        ]);
                    }
                }

                Feeds::insertDynamicFeed("ClientRequest", Auth::user()->id, $user->id, [
                    "firstName" => Auth::user()->firstName,
                    "lastName" => Auth::user()->lastName,
                    "friendFirstName" => $user->firstName,
                    "friendLastName" => $user->lastName,
                ]);

                Notifications::insertDynamicNotification("ClientInvitation", $user, Auth::user(), [
                    "link" => URL::to("/Clients/Invitation/" . $invite->key . "/"),
                    "firstName" => Auth::user()->firstName,
                    "lastName" => Auth::user()->lastName,
                ], true, $invite->key);

                Event::dispatch('sendInviteToClient', [Auth::user(), $user->id]);

                return $this::responseJson(Lang::get("messages.ClientInvited"));
            } else {
                $rules = ["firstName" => "required", "lastName" => "required", "email" => "required|email|unique:users"];
                $validation = Validator::make($request->all(), $rules);

                if ($validation->passes()) {
                    $user = new Users;
                    $user->firstName = ucfirst($request->get("firstName"));
                    $user->lastName = ucfirst($request->get("lastName"));
                    $user->email = $request->get("email");
                    $user->userType = "TempTrainee";
                    $user->save();

                    $client = new Clients;
                    $client->userId = $user->id;
                    $client->trainerId = Auth::user()->id;
                    $client->approvedClient = 1;
                    $client->approvedTrainer = 1;
                    $client->save();

                    // Additional Friend and Notification Logic Continues...
                } else {
                    return $this::responseJsonErrorValidation($validation->messages());
                }
            }
        } else {
            return $this::responseJsonError($response);
        }
    }


    public function AddEdit(Request $request)
    {
        if ($request->filled('id') && $request->get('id') != "") {
            return $this->update($request->get('id'));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $validation = Users::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $clients = new Users;
            $clients->client = $request->get('client');
            $clients->measureable = $request->get('measureable');
            $clients->recordDate = $request->get('dateRecord');
            $clients->userId = Auth::user()->id;
            $clients->save();

            Feeds::insertFeed("NewObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);

            return $this::responseJson(Lang::get("messages.ObjectiveAdded"));
        }
    }

    public function store(Request $request)
    {
        // Store logic
    }

    public function show($id)
    {
        // Show resource logic
    }

    public function edit($id)
    {
        // Edit resource logic
    }

    public function update($id, Request $request)
    {
        // Update resource logic
    }

    public function destroy($id)
    {
        $obj = Clients::find($id);
        if (!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

        if ($this->checkPermissions($obj->trainerId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.ClientDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

}
