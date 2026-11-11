<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $org = $request->user()->currentOrganization;
        return Inertia::render('Core/Subscription/Index', [
            'plans' => Plan::with('modules')->where('is_active', true)->get(),
            'subscription' => $org->subscription()?->with('plan.modules')->first(),
        ]);
    }

    public function upgrade()
    {
        return Inertia::render('Core/Subscription/Upgrade', [
            'plans' => Plan::with('modules')->where('is_active', true)->get(),
        ]);
    }

    public function success()
    {
        return Inertia::render('Core/Subscription/Success');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'payment_method' => 'required|string',
        ]);

        $org = $request->user()->currentOrganization;
        $plan = Plan::findOrFail($request->plan_id);
        $price = $request->billing_cycle === 'yearly'
            ? $plan->stripe_yearly_price_id
            : $plan->stripe_monthly_price_id;

        try {
            $org->newSubscription('default', $price)->create($request->payment_method);
            $org->subscription()->update(['plan_id' => $plan->id]);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }

        return redirect()->route('subscription.success');
    }

    public function cancel(Request $request)
    {
        $org = $request->user()->currentOrganization;
        $sub = $org->subscription;
        if ($sub) {
            $sub->update(['ends_at' => now()->endOfMonth()]);
        }
        return back()->with('success', 'Subscription will be cancelled at end of billing period.');
    }
}
