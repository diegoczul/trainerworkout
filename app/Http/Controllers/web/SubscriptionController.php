<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    //
    public function subscribe(Request $request, $planId)
    {
        // You’ll plug Stripe subscription creation here later
        return response()->json(['message' => "Subscribed to plan ID $planId"]);
    }
}
