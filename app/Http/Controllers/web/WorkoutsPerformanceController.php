<?php

namespace App\Http\Controllers\web;

use App\Http\Libraries\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\WorkoutsPerformances;
use App\Models\Clients;

class WorkoutsPerformanceController extends BaseController
{
    public function workoutsPerformanceClientsIndex()
    {
        $dateStart = date('Y-m-d', strtotime('previous sunday'));
        $dateEnd = date('Y-m-d', strtotime('today'));

        return View::make("trainer.reports.workoutsPerformanceClients")
            ->with("dateStart", $dateStart)
            ->with("dateEnd", $dateEnd);
    }

    public function workoutsPerformance(Request $request)
    {
        $workoutsPeformance = [];
        $performances = WorkoutsPerformances::where("forTrainer", Auth::user()->id)
            ->whereNotNull("dateCompleted")
            ->get();
        $clients = Clients::where("trainerId", Auth::user()->id)->get();

        $arrayData = [];
        if ($request->get("arrayData") != "") {
            $arrayData = json_decode($request->get("arrayData"), true);
        }

        $dateEnd = is_array($arrayData) && array_key_exists("dateEnd", $arrayData) && $arrayData["dateEnd"] != ""
            ? date($arrayData["dateEnd"])
            : date('Y-m-d', strtotime('today'));

        $dateStart = is_array($arrayData) && array_key_exists("dateStart", $arrayData) && $arrayData["dateStart"] != ""
            ? date($arrayData["dateStart"])
            : date("Y-m-d", strtotime("-7 day", strtotime($dateEnd)));

        $days = [];
        $date = $dateStart;

        while (strtotime($date) <= strtotime($dateEnd)) {
            array_push($days, $date);
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }

        foreach ($clients as $client) {
            $workoutsPeformance[(string)$client->userId] = [];
            $date = $dateStart;
            while (strtotime($date) <= strtotime($dateEnd)) {
                $workoutsPeformance[(string)$client->userId][$date] = [];
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        }

        if (!array_key_exists(Auth::user()->id, $workoutsPeformance)) {
            $date = $dateStart;
            while (strtotime($date) <= strtotime($dateEnd)) {
                $workoutsPeformance[(string)Auth::user()->id][$date] = [];
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        }

        foreach ($performances as $performance) {
            if ($dateStart <= date($performance->dateCompleted) &&
                date($performance->dateCompleted) < date("Y-m-d", strtotime("+1 day", strtotime($dateEnd)))
            ) {
                $key = (new \DateTime($performance->dateCompleted))->format('Y-m-d');
                if (array_key_exists((string)$performance->userId, $workoutsPeformance)) {
                    array_push($workoutsPeformance[(string)$performance->userId][$key], $performance);
                }
            }
        }

        return View::make("widgets.reports.workoutsPerformance")
            ->with("performances", $workoutsPeformance)
            ->with("clients", $clients)
            ->with("dates", $days);
    }

    public function workoutsPerformanceDetail($id = "")
    {
        if ($id != "") {
            $performance = WorkoutsPerformances::find($id);
            if ($performance) {
                return View::make("widgets.reports.workoutsPerformanceDetail")
                    ->with("performance", $performance);
            }
        }
    }

    public function API_List_WorkoutsPerformance(Request $request)
    {
        $clients = Clients::select('clients.id','clients.userId','clients.trainerId')
            ->join('users', 'users.id', '=', 'clients.userId')
            ->with(['user' => function ($query) {
                $query->select('id','firstName','lastName','email');
            }])
            ->with(['workout_preformed' => function ($query) use($request) {
                $query->select('id','workoutId','userId','ratingId','comments','timeInSeconds','dateCompleted')
                    ->with(['rating' => function ($query) {
                        $query->select('id','name');
                    }])
                    ->with(['workout' => function ($query) {
                        $query->select('id','name');
                    }])
                    ->where(['forTrainer' => Auth::user()->id])
                    ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
                        $query->whereDate('dateCompleted', '>=', $request->start_date);
                        $query->whereDate('dateCompleted', '<=', $request->end_date);
                    });
            }])
            ->where(["clients.trainerId" => Auth::user()->id,'users.deleted_at' => null]);
        $clientCounts = $clients->count();
        $clients = $clients->get();

        $response = Helper::APIOK();
        $response['data'] = $clients;
        $response['total'] = $clientCounts;
        return $this::responseJson($response);
    }

}
