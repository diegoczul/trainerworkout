<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Http;
use App\Models\UserMessages;
use App\Models\Users;
use App\Models\Clients;

class UserMessagesController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $total = UserMessages::where("to", "=", $userId)->whereNull("read")->orderBy('sent', 'DESC')->count();
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        $data = json_encode([
            'total' => $total
        ]);
        return $data;
    }

    public function indexOld(Request $request)
    {
        $userId = Auth::user()->id;
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        return View::make("notifications.notifications");
    }

    public function indexMail(Request $request)
    {
        $userId = Auth::user()->id;
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        return View::make("widgets.full.mail")
            ->with("messages", UserMessages::getInbox());
    }

    public function dialog($user)
    {
        return View::make("widgets.full.dialog")
            ->with(
                "messages",
                UserMessages::where(
                    function ($query) {
                        $query->orWhere("from", Auth::user()->id);
                        $query->orWhere("to", Auth::user()->id);
                    }
                )->orderBy("sent", "ASC")->get()
            )
            ->with("friend", Users::find($user));
    }

    public function composeMail(Request $request)
    {
        $userId = Auth::user()->id;
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        return View::make("widgets.full.composeMail");
    }

    public function composeMailToUser($user, Request $request)
    {
        $client = Users::find($user);
        $userId = Auth::user()->id;
        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }
        return View::make("widgets.full.composeMail")
            ->with("client", $client);
    }

    public function AddEdit(Request $request)
    {
        if ($request->get("id") && $request->get("id") != "") {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function readUserMessages(Request $request)
    {
        UserMessages::readUserMessages($request->get("user"), $request->get("user"));
    }

    public function create(Request $request)
    {
        if (!$request->get("friend")) {
            return $this::responseJsonError(Lang::get("messages.NoFriendChosen"));
        }
        $validation = UserMessages::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $messages = new UserMessages;
            $messages->message = $request->get("message");
            $messages->from = Auth::user()->id;
            $messages->to = $request->get("friend");
            $messages->sent = Helper::dateToUnix(date("Y-m-d H:i:s"));
            $messages->read = 1;
            $messages->user_read = 0;
            $messages->save();
            return $this::responseJson(Lang::get("messages.MessageSent"));
        }
    }

    public function eventMessageClient($userId, $userId2)
    {
        $userTo = Users::find($userId2);

        if ($userTo) {
            if (Clients::checkIfTrainerHasClient($userId, $userId2)) {
                Event::dispatch('messageClient', [Auth::user(), $userTo->firstName . " " . $userTo->lastName]);
            } else if (Clients::checkIfTraineeHasTrainer($userId, $userId2)) {
                Event::dispatch('messagePersonalTrainer', [Auth::user(), $userTo->firstName . " " . $userTo->lastName]);
            } else {
                Event::dispatch('messageNoneClient', [Auth::user(), $userTo->firstName . " " . $userTo->lastName]);
            }
        }
    }

    public function eventTest()
    {
        $response = Http::get("http://www.trainer-workout.com");
        return $response->body();
    }

    public function destroy($id)
    {
        $obj = UserMessages::find($id);
        if (!$obj) {
            return $this::responseJsonError(Lang::get("messages.NotFound"));
        }
        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.MessageDeleted"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
