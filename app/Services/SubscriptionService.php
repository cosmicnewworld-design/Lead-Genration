<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscriptionService
{
    public function getSubscriptionData(User $user): array
    {
        $intent = $user->createSetupIntent();

        return [
            'user' => $user,
            'intent' => $intent,
        ];
    }

    public function createSubscription(User $user, string $plan, string $paymentMethod): void
    {
        $user->newSubscription('default', $plan)->create($paymentMethod);
    }

    public function cancelSubscription(User $user): void
    {
        $user->subscription('default')->cancel();
    }
}
