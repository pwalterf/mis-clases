<?php

namespace App\Services\Admin;

use App\Models\Classroom;
use App\Models\Subscription;

class SubscriptionService
{
    public function createSubscription(Classroom $classroom, array $subscriptionData): Subscription
    {
        $subscription = $classroom->subscriptions()->create($subscriptionData);

        return $subscription;
    }

    public function destroySubscription(Subscription $subscription): void
    {
        $subscription->delete();
    }
}