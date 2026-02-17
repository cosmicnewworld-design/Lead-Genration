<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierWebhookController;

class WebhookController extends CashierWebhookController
{
    /**
     * Handle a Stripe webhook call.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleWebhook(Request $request)
    {
        $payload = json_decode($request->getContent(), true);
        $method = 'handle' . \Illuminate\Support\Str::studly(str_replace('.', '_', $payload['type']));

        if (method_exists($this, $method)) {
            return $this->{$method}($payload);
        }

        return parent::handleWebhook($request);
    }

    /**
     * Handle customer subscription created.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionCreated(array $payload)
    {
        $subscription = $payload['data']['object'];
        $user = \App\Models\User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            // Log subscription creation
            \Log::info('Subscription created', [
                'user_id' => $user->id,
                'subscription_id' => $subscription['id'],
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle customer subscription updated.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionUpdated(array $payload)
    {
        $subscription = $payload['data']['object'];
        $user = \App\Models\User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            \Log::info('Subscription updated', [
                'user_id' => $user->id,
                'subscription_id' => $subscription['id'],
                'status' => $subscription['status'],
            ]);
        }

        return $this->successMethod();
    }

    /**
     * Handle customer subscription deleted.
     *
     * @param  array  $payload
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        $subscription = $payload['data']['object'];
        $user = \App\Models\User::where('stripe_id', $subscription['customer'])->first();

        if ($user) {
            \Log::info('Subscription cancelled', [
                'user_id' => $user->id,
                'subscription_id' => $subscription['id'],
            ]);
        }

        return $this->successMethod();
    }
}
