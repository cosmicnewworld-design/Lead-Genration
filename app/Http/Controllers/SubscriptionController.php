<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;

class SubscriptionController extends Controller
{
    public function checkout(Request $request, $plan)
    {
        $plan = Plan::where('slug', $plan)->firstOrFail();
        $user = $request->user();

        $checkout = $user->newSubscription($plan->name, $plan->stripe_plan_id)
            ->checkout([
                'success_url' => route('dashboard'),
                'cancel_url' => route('billing.index'),
            ]);

        return view('checkout.index', [
            'checkout' => $checkout,
            'stripe_key' => config('cashier.key')
        ]);
    }
}
