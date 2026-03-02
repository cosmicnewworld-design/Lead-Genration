<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $subscription = $user->subscription('default');

        return view('billing.index', [
            'plans' => $plans,
            'currentSubscription' => $subscription,
            'user' => $user,
        ]);
    }

    public function checkout(Request $request, $plan)
    {
        $plan = Plan::where('slug', $plan)->firstOrFail();
        $user = $request->user();

        return $user->newSubscription($plan->name, $plan->stripe_plan)
            ->checkout([
                'success_url' => route('dashboard') . '?checkout=success',
                'cancel_url' => route('billing.index') . '?checkout=cancelled',
            ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required|exists:plans,slug',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $plan = Plan::where('slug', $request->plan)->firstOrFail();
            $user = $request->user();

            $user->newSubscription($plan->name, $plan->stripe_price_id ?? $plan->stripe_plan_id)
                ->create($request->payment_method);

            return redirect()->route('billing.index')
                ->with('success', 'Subscription created successfully!');
        } catch (\Exception $e) {
            Log::error('Subscription creation error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);

            return back()->with('error', 'Failed to create subscription. Please try again.');
        }
    }

    public function cancel(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->subscribed('default')) {
                return back()->with('error', 'You do not have an active subscription.');
            }

            $user->subscription('default')->cancel();

            return back()->with('success', 'Your subscription has been cancelled.');
        } catch (\Exception $e) {
            Log::error('Subscription cancellation error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);

            return back()->with('error', 'Failed to cancel subscription. Please contact support.');
        }
    }

    public function resume(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->subscription('default')->cancelled()) {
                return back()->with('error', 'Your subscription is not cancelled.');
            }

            $user->subscription('default')->resume();

            return back()->with('success', 'Your subscription has been resumed.');
        } catch (\Exception $e) {
            Log::error('Subscription resume error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);

            return back()->with('error', 'Failed to resume subscription. Please contact support.');
        }
    }

    public function portal(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user->stripe_id) {
                return back()->with('error', 'No billing information found.');
            }

            return $user->redirectToBillingPortal(route('billing.index'));
        } catch (\Exception $e) {
            Log::error('Billing portal error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
            ]);

            return back()->with('error', 'Failed to access billing portal. Please contact support.');
        }
    }
}
