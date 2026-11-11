import { Head, Link, useForm } from '@inertiajs/react';
import AuthLayout from '@/layouts/auth-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

export default function CreateOrganization() {
  const { data, setData, post, processing, errors } = useForm({
    name: '', slug: '', email: '', phone: '',
  });

  return (
    <>
      <Head title="Create Organization" />
      <div className="flex min-h-screen items-center justify-center p-4">
        <Card className="w-full max-w-lg">
          <CardHeader>
            <CardTitle>Create your organization</CardTitle>
            <CardDescription>Set up your workspace to get started.</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={(e) => { e.preventDefault(); post('/organizations'); }} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="name">Organization Name</Label>
                <Input id="name" value={data.name} onChange={(e) => setData('name', e.target.value)} placeholder="Acme Inc." />
                {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="slug">Slug</Label>
                <Input id="slug" value={data.slug} onChange={(e) => setData('slug', e.target.value)} placeholder="acme-inc" />
                {errors.slug && <p className="text-sm text-destructive">{errors.slug}</p>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="email">Email (optional)</Label>
                <Input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} placeholder="admin@acme.com" />
              </div>
              <div className="flex gap-4 pt-2">
                <Button type="submit" disabled={processing}>Create Organization</Button>
                <Link href="/dashboard"><Button variant="outline" type="button">Cancel</Button></Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </>
  );
}
