<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index()
    {
        $data = $this->subscriptionService->getSubscriptionData(Auth::user());
        return view('billing.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'plan' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $this->subscriptionService->createSubscription(
            Auth::user(),
            $request->plan,
            $request->payment_method
        );

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    public function cancel()
    {
        $this->subscriptionService->cancelSubscription(Auth::user());

        return redirect()->route('subscriptions.index')->with('success', 'Subscription cancelled successfully.');
    }
}
