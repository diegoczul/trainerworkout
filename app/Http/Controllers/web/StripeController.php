<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Price;
use Stripe\Product;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1', // Store UI price in dollars
        ]);

        $user = Auth::user();

        // Set Stripe API key
        $debug = false;
        \Stripe\Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));



        // 1. Create a Stripe Product
        $product = Product::create([
            'name' => $request->name,
            'description' => strip_tags($request->description), // optional
        ]);

        // 2. Create a Stripe Price (monthly recurring)
        $price = Price::create([
            'unit_amount' => $request->price * 100, // Store price in cents for Stripe
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $product->id,
        ]);

        Log::info('Stripe Product ID: ' . $product->id);
        Log::info('Stripe Price ID: ' . $price->id);

        // 3. Store locally in `plans` table
        DB::table('plans')->insert([
            'user_id' => $user->id,
            'name' => $request->name,
            'description' => $request->description,
            'frequency' => 'monthly',
            'price' => $request->price, // Store as dollars for UI
            'stripe_price' => $request->price * 100, // Store as cents for Stripe
            'created_at' => now(),
            'updated_at' => now(),
            'number_subscriptions' => 0,
            'stripe_product_id' => $product->id, // Store Stripe product ID
            'stripe_price_id' => $price->id, // Store Stripe price ID
        ]);

        return redirect()->back()->with('success', 'Plan created successfully.');
    }


    public function editPlan(Request $request, $id)
    {
        $user = Auth::user();

        // Check if the plan belongs to the logged-in user
        $plan = Plan::where('id', $id)->where('user_id', $user->id)->first();

        if (!$plan) {
            // If the plan is not found or doesn't belong to the user, return an error
            return redirect()->route('myPlansIndex')->with('error', 'You are not authorized to edit this plan.');
        }

        // Return the plan details for pre-filling the form
        return response()->json([
            'name' => $plan->name,
            'description' => $plan->description,
            'price' => $plan->price, // Convert from cents to dollars
            'stripe_price' => $plan->price * 100, // Convert from cents to dollars
            'id' => $plan->id
        ]);
    }

    public function getPlanDetails(Request $request, $id)
    {
        $user = Auth::user();

        // Get the plan by ID, ensuring it's owned by the logged-in user
        $plan = Plan::where('id', $id)->where('user_id', $user->id)->first();

        if (!$plan) {
            return response()->json(['error' => 'You are not authorized to view this plan.'], 403);
        }

        // Return plan details for pre-filling the form
        return response()->json([
            'name' => $plan->name,
            'description' => $plan->description,
            'price' => $plan->price, // Return the price in dollars (UI price)
            'stripe_price' => $plan->stripe_price, // Return the price in cents (Stripe price)
            'id' => $plan->id
        ]);
    }

    public function updatePlan(Request $request, $id)
    {
        $user = Auth::user();
        $plan = Plan::where('id', $id)->where('user_id', $user->id)->first();

        if (!$plan) {
            return redirect()->route('myPlansIndex')->with('error', 'You are not authorized to update this plan.');
        }

        $data = $this->handleStripePlan($plan, $request);
        $plan->update($data);

        return redirect()->route('myPlansIndex')->with('success', 'Plan updated successfully.');
    }

    public function savePlan(Request $request, $id = null)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
        ]);

        $plan = is_null($id) ? new Plan() : Plan::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if ($plan->user_id && $plan->user_id !== $user->id) {
            return redirect()->route('myPlansIndex')->with('error', 'You do not have permission to edit this plan.');
        }

        $plan->user_id = $user->id;

        $data = $this->handleStripePlan($plan, $request);
        $plan->fill($data);
        $plan->save();

        return redirect()->route('myPlansIndex')->with('success', 'Plan saved successfully.');
    }



    private function handleStripePlan(Plan $plan, Request $request): array
    {
        $debug = false;
        Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));


        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stripe_price' => $request->price * 100,
        ];

        // Create product if missing
        if (!$plan->stripe_product_id) {
            $product = Product::create([
                'name' => $request->name,
                'description' => strip_tags($request->description),
            ]);
            $data['stripe_product_id'] = $product->id;
        } else {
            Product::update($plan->stripe_product_id, [
                'name' => $request->name,
                'description' => strip_tags($request->description),
            ]);
        }

        // Create price only if price changed or missing
        if (!$plan->stripe_price_id || (int)$plan->stripe_price !== (int)$data['stripe_price']) {
            $price = Price::create([
                'unit_amount' => $data['stripe_price'],
                'currency' => 'usd',
                'recurring' => ['interval' => 'month'],
                'product' => $data['stripe_product_id'] ?? $plan->stripe_product_id,
            ]);
            $data['stripe_price_id'] = $price->id;

            Log::info('Stripe Price created or updated', [
                'plan_id' => $plan->id ?? 'new',
                'price_id' => $price->id,
            ]);
        }

        return $data;
    }



    public function destroy($id)
    {
        $user = Auth::user();

        $plan = Plan::where('id', $id)->where('user_id', $user->id)->first();

        if (!$plan) {
            return redirect()->route('myPlansIndex')->with('error', 'You are not authorized to delete this plan.');
        }

        try {
            $debug = false;
            \Stripe\Stripe::setApiKey($debug ? config("constants.STRIPETestsecret_key") : config("constants.STRIPEsecret_key"));

            // Archive the Stripe product instead of deleting
            if ($plan->stripe_product_id) {
                \Stripe\Product::update($plan->stripe_product_id, ['active' => false]);
            }

            $plan->delete();

            return redirect()->route('myPlansIndex')->with('success', 'Plan deleted (and product archived) successfully.');
        } catch (\Exception $e) {
            return redirect()->route('myPlansIndex')->with('error', 'Stripe error: ' . $e->getMessage());
        }
    }
}
