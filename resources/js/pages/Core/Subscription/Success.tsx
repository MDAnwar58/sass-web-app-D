import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { CheckCircle } from 'lucide-react';

export default function Success() {
  return (
    <AppLayout breadcrumbs={[{ title: 'Success', href: '/subscription/success' }]}>
      <Head title="Subscription Successful" />
      <div className="flex items-center justify-center min-h-[60vh]">
        <Card className="text-center max-w-md">
          <CardHeader>
            <CheckCircle size={48} className="mx-auto text-primary mb-2" />
            <CardTitle>Subscription Successful!</CardTitle>
            <CardDescription>Your subscription has been activated. You can now access all your modules.</CardDescription>
          </CardHeader>
          <CardContent>
            <Link href="/dashboard"><Button>Go to Dashboard</Button></Link>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
