<?php

namespace App\Http\Controllers\Web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\Feeds;
use App\Models\Notifications;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class FeedsController extends BaseController
{
    public $pageSize = 8;
    public $pageSizeFull = 8;

    public function indexClients(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $permissions = null;

        $clientList = Clients::where("trainerId", $userId)->pluck("userId")->toArray();
        if (empty($clientList)) {
            $clientList = [0];
        }

        if ($request->has("userId")) {
            $permissions = Helper::checkPremissions($user->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
                $clientList = [$request->get("userId")];
                if (empty($clientList)) {
                    $clientList = [0];
                }
            }
        } else {
            $permissions = Helper::checkPremissions($user->id, null);
        }

        if ($request->has("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return view("widgets.full.feedClient")
            ->with("feeds", DB::table("feeds")
                ->select("feeds.id as feedId", "message", "userId", "feeds.created_at as date", "fromId", "action", "link", "type", DB::raw("'feed' as sourceType"), "users.*")
                ->leftJoin("users", "users.id", "=", "userId")
                ->whereIn("userId", $clientList)
                ->whereNull("archived_at")
                ->union(DB::table("notifications")
                    ->select("notifications.id as feedId", "message", "userId", "notifications.created_at as date", "fromId", "action", "link", "type", DB::raw("'notification' as sourceType"), "users.*")
                    ->leftJoin("users", "users.id", "=", "fromId")
                    ->whereIn("fromId", $clientList)
                    ->where("display", "!=", "top")
                    ->whereNull("archived_at"))
                ->orderBy('date', 'DESC')
                ->take($this->pageSize)
                ->get())
            ->with("permissions", $permissions)
            ->with("user", $user)
            ->with("total", count(DB::table("feeds")
                ->select("message", "userId", "feeds.created_at as date", "fromId", "action", "link", "users.*")
                ->leftJoin("users", "users.id", "=", "userId")
                ->whereIn("userId", $clientList)
                ->whereNull("archived_at")
                ->union(DB::table("notifications")
                    ->select("message", "userId", "notifications.created_at as date", "fromId", "action", "link", "users.*")
                    ->leftJoin("users", "users.id", "=", "fromId")
                    ->whereIn("fromId", $clientList)
                    ->where("display", "!=", "top")
                    ->whereNull("archived_at"))
                ->orderBy('date', 'DESC')
                ->get()));
    }

    public function AddEdit(Request $request)
    {
        if ($request->has("id") && $request->get("id") != "") {
            return $this->update($request->get("id"));
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
            $clients->client = $request->get("client");
            $clients->measureable = $request->get("measureable");
            $clients->recordDate = $request->get("dateRecord");
            $clients->userId = Auth::user()->id;
            $clients->save();
            Feeds::insertFeed("NewObjective", Auth::user()->id, Auth::user()->firstName, Auth::user()->lastName);
            return $this::responseJson(Lang::get("messages.ObjectiveAdded"));
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /clients
     *
     * @return Response
     */
    public function store()
    {
        // Method stub for storing clients
    }

    /**
     * Display the specified resource.
     * GET /clients/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // Method stub for showing specific client
    }

    /**
     * Show the form for editing the specified resource.
     * GET /clients/{id}/edit
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // Method stub for editing a specific client
    }

    /**
     * Update the specified resource in storage.
     * PUT /clients/{id}
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // Method stub for updating a client
    }

    public function destroy($id)
    {
        $obj = Users::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.ObjectiveDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }

    public function archive($type, $id)
    {
        $obj = ($type == "Feed") ? Feeds::find($id) : Notifications::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }
        $obj->archived_at = now();
        $obj->save();
        return $this::responseJson(Lang::get("messages.FeedArchived"));
    }

    public function ControlPanelFeeds()
    {
        $feeds = Feeds::with("user")->whereNull("reported_at")->orderBy("reported_at", "Desc")->get();
        $date = now()->format("Y-m-d");
        $email = Config::get("mail.username");
        Feeds::whereNull("reported_at")->update(["reported_at" => now()]);
        Mail::queueOn(env('APP_ENV'), 'ControlPanel.emails.feeds', ["date" => $date, "feeds" => serialize($feeds)], function ($message) use ($email, $date) {
            $message->to($email)->subject("Activity of " . $date);
        });
    }
}
