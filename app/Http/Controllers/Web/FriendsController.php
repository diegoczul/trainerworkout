<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use App\Models\Friends;
use App\Models\Users;
use App\Models\Invites;
use App\Models\Notifications;
use App\Models\Feeds;

class FriendsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
            if ($permissions["view"]) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
        }

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("widgets.base.friends")
            ->with("friends", Friends::friend()
                ->where("userId", $userId)
                ->orWhere("followingId", $userId)
                ->take($this->pageSize)
                ->get()
            )
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", Friends::friend()
                ->where("userId", $userId)
                ->orWhere("followingId", $userId)
                ->count()
            );
    }

    public function indexFriends(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
            if ($permissions["view"]) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
        }

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("trainee.friends")
            ->with("friends", Friends::where("userId", $userId)
                ->orderBy('created_at', 'ASC')
                ->take($this->pageSize)
                ->get()
            )
            ->with("user", $user)
            ->with("permissions", $permissions)
            ->with("total", Friends::where("userId", $userId)->count());
    }

    public function indexFriendsTrainer(Request $request)
    {
        $userId = Auth::id();
        $permissions = null;

        if ($request->has('userId')) {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
            if ($permissions["view"]) {
                $userId = $request->get('userId');
            }
        } else {
            $permissions = Helper::checkPremissions($userId, $request->get('userId'));
        }

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("trainer.friends")
            ->with("friends", Friends::where("userId", $userId)
                ->orderBy('created_at', 'ASC')
                ->take($this->pageSize)
                ->get()
            )
            ->with("permissions", $permissions)
            ->with("total", Friends::where("userId", $userId)->count());
    }

    public function indexSuggest(Request $request)
    {
        $userId = Auth::id();
        $search = $request->get('term');

        return $this->responseJson(
            Friends::where(function ($query) use ($userId) {
                $query->orWhere("userId", $userId);
            })
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'followingId');
                })
                ->where(function ($query) use ($search) {
                    $query->orWhere('firstName', 'like', "%$search%")
                        ->orWhere('lastName', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                })
                ->get()
        );
    }

    public function indexFull(Request $request)
    {
        $userId = Auth::id();

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("widgets.full.friends")
            ->with("friends", Friends::where("userId", $userId)
                ->orderBy('created_at', 'ASC')
                ->take($this->pageSize)
                ->get()
            )
            ->with("total", Friends::where("userId", $userId)->count());
    }

    public function indexFullTrainer(Request $request)
    {
        $userId = Auth::id();

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        return View::make("widgets.full.friends")
            ->with("friends", Friends::where("userId", $userId)
                ->orderBy('created_at', 'ASC')
                ->take($this->pageSize)
                ->get()
            )
            ->with("total", Friends::where("userId", $userId)->count());
    }

    public function searchFriend(Request $request)
    {
        $search = $request->get('search', '');

        if ($request->has('pageSize')) {
            $this->pageSize += $request->get('pageSize');
        }

        $searchArray = explode(" ", $search);
        array_push($searchArray, $search);

        return View::make("widgets.full.friendsSearch")
            ->with("users", Users::where(function ($query) use ($search, $searchArray) {
                foreach ($searchArray as $searchItem) {
                    $query->orWhere('firstName', 'like', "%$searchItem%")
                        ->orWhere('lastName', 'like', "%$searchItem%")
                        ->orWhere(DB::raw("concat(firstName,' ',lastName)"), 'like', "%$searchItem%")
                        ->orWhere('email', 'like', "%$searchItem%");
                }
            })->where("id", "!=", Auth::id())
                ->take($this->pageSize)
                ->get()
            )
            ->with("total", Users::where(function ($query) use ($search, $searchArray) {
                foreach ($searchArray as $searchItem) {
                    $query->orWhere('firstName', 'like', "%$searchItem%")
                        ->orWhere('lastName', 'like', "%$searchItem%")
                        ->orWhere(DB::raw("concat(firstName,' ',lastName)"), 'like', "%$searchItem%")
                        ->orWhere('email', 'like', "%$searchItem%");
                }
            })->where("id", "!=", Auth::id())
                ->count()
            );
    }

    public function addFriend(Request $request)
    {
        if ($request->has('followingId')) {
            return $this->create($request);
        }

        if ($request->has('email')) {
            $lookForUser = Users::where("email", $request->get('email'))->get();

            if (!$lookForUser->isEmpty()) {
                if (!Friends::checkFollower(Auth::id(), $lookForUser->id)) {
                    return $this->createInternal($lookForUser);
                } else {
                    return $this::responseJsonError(Lang::get("messages.AlreadyFollowing"));
                }
            } else {
                $invite = new Invites;
                $invite->userId = Auth::id();
                $invite->email = $request->get('email');
                $invite->key = GUID::generate();
                $invite->type = "TraineeFriendRequest";
                $invite->save();
                $invite->sendInvite();

                return $this::responseJson(Lang::get("messages.InvitationSent"));
            }
        }

        return $this::responseJson(Lang::get("messages.NotFound"));
    }

    public function AddEdit(Request $request)
    {
        if ($request->has('id') && $request->get('id') != "") {
            return $this->update($request->get('id'));
        } else {
            return $this->create($request);
        }
    }

    public function create(Request $request)
    {
        $validation = Friends::validate($request->all());

        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        }

        if (Friends::where("followingId", $request->get('followingId'))
                ->where("userId", Auth::id())
                ->count() > 0
        ) {
            return $this::responseJson(Lang::get("messages.AlreadyFollowing"));
        }

        $friends = new Friends;
        $friends->followingId = $request->get('followingId');
        $friends->userId = Auth::id();
        $friends->save();

        $userAux = Users::find($request->get('followingId'));

        if ($userAux) {
            Notifications::insertDynamicNotification("Following", $userAux->id, Auth::id(), [
                "firstName" => Auth::user()->firstName,
                "lastName" => Auth::user()->lastName
            ]);

            Feeds::insertDynamicFeed("Following", Auth::id(), Auth::id(), [
                "firstName" => Auth::user()->firstName,
                "lastName" => Auth::user()->lastName,
                "friendFirstName" => $userAux->firstName,
                "friendLastName" => $userAux->lastName
            ]);
        }

        return $this::responseJson(Lang::get("messages.FriendAdded"));
    }

    public function createInternal(Request $request, $user)
    {
        $friends = new Friends;
        $friends->followingId = $user->id;
        $friends->userId = Auth::id();
        $friends->save();

        $userAux = Users::find($user->id);

        if ($userAux) {
            Notifications::insertDynamicNotification("Following", $userAux->id, Auth::id(), [
                "firstName" => $userAux->firstName,
                "lastName" => $userAux->lastName
            ]);

            Feeds::insertDynamicFeed("Following", Auth::id(), Auth::id(), [
                "firstName" => Auth::user()->firstName,
                "lastName" => Auth::user()->lastName,
                "friendFirstName" => $userAux->firstName,
                "friendLastName" => $userAux->lastName
            ]);
        }

        return $this::responseJson(Lang::get("messages.FriendAdded"));
    }

    public function destroy(Request $request, $id)
    {
        $obj = Friends::find($id);

        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::id())) {
            $userAux = Users::find($obj->followingId);
            $obj->delete();

            return $this::responseJson(Lang::get("messages.FriendUnFollowed"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
