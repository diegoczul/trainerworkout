<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use App\Models\Testimonials;
use App\Models\Notifications;
use App\Models\Feeds;
use App\Models\Users;

class TestimonialsController extends BaseController
{
    public $pageSize = 9;
    public $pageSizeFull = 9;

    public function index($request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        return View::make("widgets.base.testimonials")
            ->with("testimonials", Testimonials::with("fUser")->with("user")
                ->where("userId", "=", $userId)
                ->orderBy('recordDate', 'ASC')
                ->take($this->pageSize)
                ->get())
            ->with("permissions", $permissions)
            ->with("total", Testimonials::where("userId", "=", $userId)->count());
    }

    public function indexFull($request)
    {
        $userId = Auth::user()->id;
        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) {
                $userId = $request->get("userId");
            }
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        if ($request->get("pageSize")) {
            $this->pageSize = $request->get("pageSize") + $this->pageSize;
        }

        $testimonials = null;
        $testimonialsCount = 0;

        if ($userId != Auth::user()->id) {
            $testimonials = Testimonials::with("fUser")->with("user")
                ->where("userId", "=", $userId)
                ->orderBy('updated_at', 'DESC')
                ->whereNotNull("approved")
                ->take($this->pageSize)
                ->get();
            $testimonialsCount = Testimonials::where("userId", "=", $userId)
                ->whereNotNull("approved")
                ->count();
        } else {
            $testimonials = Testimonials::with("fUser")->with("user")
                ->where("userId", "=", $userId)
                ->orderBy('updated_at', 'DESC')
                ->take($this->pageSize)
                ->get();
            $testimonialsCount = Testimonials::where("userId", "=", $userId)->count();
        }

        return View::make("widgets.full.testimonials")
            ->with("testimonials", $testimonials)
            ->with("permissions", $permissions)
            ->with("total", $testimonialsCount);
    }

    public function approveTestimonial($request)
    {
        $user = Auth::user();

        $status = $request->get("status");
        $testimonialId = $request->get("id");

        $testimonial = Testimonials::find($testimonialId);

        if ($status == "approve") {
            $testimonial->approved = date("Y-m-d H:i:s");
            $testimonial->save();
            return $this::responseJson(Lang::get("messages.TestimonialApproved"));
        } else {
            $testimonial->approved = null;
            $testimonial->save();
            return $this::responseJson(Lang::get("messages.TestimonialNotApproved"));
        }
    }

    public function AddEdit($request)
    {
        if ($request->get("id") && $request->get("id") != "") {
            return $this->update($request->get("id"));
        } else {
            return $this->create($request);
        }
    }

    public function create($request)
    {
        $user = Auth::user();
        $userId = $user->id;

        $permissions = null;

        if ($request->get("userId")) {
            $permissions = Helper::checkPremissions(Auth::user()->id, $request->get("userId"));
            if ($permissions["view"]) $userId = $request->get("userId");
            $userId = $request->get("userId");
        } else {
            $permissions = Helper::checkPremissions(Auth::user()->id, null);
        }

        $validation = Testimonials::validate($request->all());
        if ($validation->fails()) {
            return $this::responseJsonErrorValidation($validation->messages());
        } else {
            $testimonials = new Testimonials;
            $testimonials->rating = $request->get("rating");
            $testimonials->testimonial = $request->get("testimonial");
            $testimonials->userId = $userId;
            $testimonials->fromUser = Auth::user()->id;
            $testimonials->save();

            if ($testimonials->userId != $testimonials->fromUser) {
                Notifications::insertDynamicNotification("NewTestimonial", $userId, Auth::user()->id, ["firstName" => Auth::user()->firstName, "lastName" => Auth::user()->lastName], true);
            }

            $friend = Users::find($userId);
            if ($friend) {
                Feeds::insertDynamicFeed("NewTestimonial", $user->id, Auth::user()->id, ["firstName" => Auth::user()->firstName, "lastName" => Auth::user()->lastName, "friendFirstName" => $friend->firstName, "friendLastName" => $friend->lastName]);
            }

            return $this::responseJson(Lang::get("messages.TestimonialAdded"));
        }
    }

    public function destroy($id)
    {
        $obj = Testimonials::find($id);
        if (!$obj) return $this::responseJsonError(Lang::get("messages.NotFound"));

        if ($this->checkPermissions($obj->userId, Auth::user()->id)) {
            $obj->delete();
            return $this::responseJson(Lang::get("messages.DeleteTestimonial"));
        } else {
            return $this::responseJsonError(Lang::get("messages.Permissions"));
        }
    }
}
