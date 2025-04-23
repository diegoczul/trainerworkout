<?php

// app/Http/Controllers/web/PlanSubscriptionController.php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Sharings;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PlanSubscriptionController extends Controller
{
    public function viewShared($link)
    {
        $sharing = Sharings::where('access_link', $link)->firstOrFail();
        $plan = Plan::findOrFail($sharing->aux);
        $trainer = Users::findOrFail($sharing->fromUser);

        return view('trainer.shared', [
            'plan' => $plan,
            'trainer' => $trainer,
            'link' => $link
        ]);
    }

    public function subscribeClientToPlan(Request $request, $planId)
    {
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();

        // Put it into the session cart so it goes to checkout
        $cart = [
            "items" => [[
                "id" => $plan->id,
                "type" => "Membership", // Reuse Membership logic
                "identifier" => 1,
                "price" => $plan->price,
                "orderItemId" => null
            ]],
            "orderId" => 0,
            "quantity" => 1,
            "provincialTax" => 0,
            "federalTax" => 0,
            "subtotal" => $plan->price,
            "total" => $plan->price
        ];

        Session::put("cart", $cart);
        Session::save();

        return redirect()->route("StoreCheckout");
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|integer',
            'trainer_id' => 'required|integer',
            'stripe_price_id' => 'required|string',
            'email' => 'required|email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'nullable|string',
        ]);

        // Step 1: Create or fetch user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'firstName' => $request->first_name,
                'lastName' => $request->last_name,
                'phone' => $request->phone,
                'userType' => 'trainee',
                'password' => bcrypt(Str::random(10)),
            ]
        );

        // Step 2: Insert into plans_users (SQL will be done after if needed)
        DB::table('plans_users')->insert([
            'trainer_id' => $request->trainer_id,
            'plan_id' => $request->plan_id,
            'user_id' => $user->id,
            'price' => Plan::find($request->plan_id)->price ?? 0,
            'stripe_price_id' => $request->stripe_price_id,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Step 3: Send email notifications (we'll stub this for now)
        // Mail::to($user->email)->send(new PlanSubscribed(...));
        // Mail::to($trainer->email)->send(new PlanSubscribedNotification(...));

        return response()->json(['success' => true]);
    }
}
