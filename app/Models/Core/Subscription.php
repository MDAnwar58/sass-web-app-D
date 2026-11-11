<?php

namespace App\Models\Core;

use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    protected $fillable = [
        'organization_id', 'plan_id', 'type',
        'stripe_id', 'stripe_status', 'stripe_price',
        'quantity', 'billing_cycle',
        'trial_ends_at', 'ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->stripe_status === 'active';
    }
}
