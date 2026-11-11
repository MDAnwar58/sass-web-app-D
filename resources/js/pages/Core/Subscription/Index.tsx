import { Head, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import type { Plan, Subscription as SubscriptionType } from '@/types/core';

interface Props {
  plans: Plan[];
  subscription?: SubscriptionType;
}

export default function SubscriptionIndex({ plans, subscription }: Props) {
  return (
    <AppLayout breadcrumbs={[{ title: 'Subscription', href: '/subscription' }]}>
      <Head title="Subscription" />
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold">Subscription</h1>
          <p className="text-muted-foreground">Manage your plan and billing.</p>
        </div>

        {subscription && (
          <Card>
            <CardHeader>
              <CardTitle>Current Plan</CardTitle>
              <CardDescription>You are on the {subscription.plan.name} plan</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="flex items-center gap-2">
                <Badge>{subscription.stripe_status}</Badge>
                <span className="text-sm text-muted-foreground">{subscription.billing_cycle} billing</span>
              </div>
            </CardContent>
            <CardFooter>
              <Button variant="destructive" onClick={() => router.post('/subscription/cancel')}>Cancel Subscription</Button>
            </CardFooter>
          </Card>
        )}

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {plans.map((plan) => (
            <Card key={plan.id} className={subscription?.plan.id === plan.id ? 'border-primary' : ''}>
              <CardHeader>
                <CardTitle>{plan.name}</CardTitle>
                <CardDescription>{plan.description}</CardDescription>
                <div className="mt-2"><span className="text-3xl font-bold">${plan.monthly_price}</span><span className="text-muted-foreground">/mo</span></div>
              </CardHeader>
              <CardContent>
                <ul className="space-y-2">
                  {plan.modules.map((mod) => (
                    <li key={mod.id} className="text-sm flex items-center gap-2"><span className="text-primary">✓</span> {mod.name}</li>
                  ))}
                </ul>
              </CardContent>
              <CardFooter>
                <Button className="w-full" variant={subscription?.plan.id === plan.id ? 'outline' : 'default'}
                  disabled={subscription?.plan.id === plan.id}>
                  {subscription?.plan.id === plan.id ? 'Current Plan' : 'Subscribe'}
                </Button>
              </CardFooter>
            </Card>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
