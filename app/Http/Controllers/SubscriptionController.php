<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tenant = auth()->user()->tenant;
        $plans = Plan::all();
        $currentPlan = $tenant->subscription('default') ?
            Plan::where('stripe_price_id', $tenant->subscription('default')->stripe_price)->first() :
            null;

        return view('billing.index', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'intent' => $tenant->createSetupIntent(),
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => ['required', 'string', 'exists:plans,slug'],
            'payment_method' => ['required', 'string'],
        ]);

        $tenant = auth()->user()->tenant;
        $plan = Plan::where('slug', $request->plan)->firstOrFail();

        $tenant->newSubscription('default', $plan->stripe_price_id)
            ->create($request->payment_method);

        return redirect()->route('billing.index')->with('success', 'Subscription successful!');
    }

    public function swap(Request $request)
    {
        // Logic for swapping plans (upgrade/downgrade)
    }

    public function cancel(Request $request)
    {
        // Logic for cancelling subscription
    }
}
