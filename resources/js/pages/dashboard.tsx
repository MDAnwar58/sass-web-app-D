import { Head, Link, usePage } from '@inertiajs/react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Package, ShoppingCart, GraduationCap, TrendingUp, ArrowUpRight } from 'lucide-react';

export default function Dashboard() {
  const { active_modules, organization } = usePage().props as any;

  const stats: { label: string; href: string; icon: React.ElementType }[] = [];

  if (active_modules?.includes('inventory')) {
    stats.push({ label: 'Products', icon: Package, href: '/inventory/products' });
    stats.push({ label: 'Sales', icon: ShoppingCart, href: '/inventory/sales' });
  }

  if (active_modules?.includes('lms')) {
    stats.push({ label: 'Courses', icon: GraduationCap, href: '/lms/courses' });
  }

  return (
    <>
      <Head title="Dashboard" />
      <div className="flex h-full flex-1 flex-col gap-4 p-4">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold">
              {organization ? `${organization.name} Dashboard` : 'Dashboard'}
            </h1>
            <p className="text-muted-foreground">Welcome back! Here's your overview.</p>
          </div>
          {!organization && (
            <Link href="/organizations/create">
              <Button>Create Organization</Button>
            </Link>
          )}
        </div>

        {stats.length > 0 && (
          <div className="grid auto-rows-min gap-4 md:grid-cols-2 lg:grid-cols-3">
            {stats.map((stat) => (
              <Link key={stat.label} href={stat.href}>
                <Card className="hover:bg-muted/50 transition-colors">
                  <CardHeader className="flex flex-row items-center justify-between pb-2">
                    <CardTitle className="text-sm font-medium text-muted-foreground">{stat.label}</CardTitle>
                    <stat.icon size={18} className="text-muted-foreground" />
                  </CardHeader>
                  <CardContent>
                    <div className="text-2xl font-bold">—</div>
                  </CardContent>
                </Card>
              </Link>
            ))}
            <Card>
              <CardHeader className="flex flex-row items-center justify-between pb-2">
                <CardTitle className="text-sm font-medium text-muted-foreground">Revenue</CardTitle>
                <TrendingUp size={18} className="text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">$0.00</div>
                <p className="text-xs text-muted-foreground flex items-center gap-1 mt-1"><ArrowUpRight size={12} /> No data yet</p>
              </CardContent>
            </Card>
          </div>
        )}

        {!organization && (
          <div className="flex-1 flex items-center justify-center">
            <div className="text-center max-w-md">
              <h2 className="text-xl font-semibold mb-2">Get Started</h2>
              <p className="text-muted-foreground mb-4">Create an organization to start managing your products, courses, and more.</p>
              <Link href="/organizations/create"><Button size="lg">Create Your First Organization</Button></Link>
            </div>
          </div>
        )}

        {organization && stats.length === 0 && (
          <div className="flex-1 flex items-center justify-center">
            <div className="text-center max-w-md">
              <h2 className="text-xl font-semibold mb-2">No Modules Active</h2>
              <p className="text-muted-foreground mb-4">Subscribe to a plan to unlock features like Inventory and LMS.</p>
              <Link href="/subscription/upgrade"><Button size="lg">View Plans</Button></Link>
            </div>
          </div>
        )}

        <div className="relative min-h-[30vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
          <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
        </div>
      </div>
    </>
  );
}

Dashboard.layout = {
  breadcrumbs: [{ title: 'Dashboard', href: '/dashboard' }],
};
