import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/components/ui/card';
import type { Plan } from '@/types/core';

interface Props {
  plans: Plan[];
}

export default function Upgrade({ plans }: Props) {
  return (
    <AppLayout breadcrumbs={[{ title: 'Upgrade', href: '/subscription/upgrade' }]}>
      <Head title="Upgrade Plan" />
      <div className="space-y-6">
        <div>
          <h1 className="text-2xl font-bold">Upgrade Plan</h1>
          <p className="text-muted-foreground">Choose a plan that fits your needs.</p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {plans.map((plan) => (
            <Card key={plan.id}>
              <CardHeader>
                <CardTitle>{plan.name}</CardTitle>
                <CardDescription>{plan.modules.map(m => m.name).join(', ')}</CardDescription>
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
                <Link href="/subscription" className="w-full"><Button className="w-full">View Details</Button></Link>
              </CardFooter>
            </Card>
          ))}
        </div>
      </div>
    </AppLayout>
  );
}
