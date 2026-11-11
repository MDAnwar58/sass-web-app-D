import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export default function CreateCourse() {
  const { data, setData, post, processing, errors } = useForm({
    title: '', description: '',
    level: 'beginner', language: 'en',
    duration_minutes: '0', price: '0', thumbnail: '',
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    post('/lms/courses');
  };

  return (
    <AppLayout breadcrumbs={[
      { title: 'Courses', href: '/lms/courses' },
      { title: 'Create', href: '/lms/courses/create' },
    ]}>
      <Head title="Create Course" />
      <div className="max-w-2xl mx-auto">
        <Card>
          <CardHeader><CardTitle>Create Course</CardTitle></CardHeader>
          <CardContent>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="title">Title</Label>
                <Input id="title" value={data.title} onChange={(e) => setData('title', e.target.value)} />
                {errors.title && <p className="text-sm text-destructive">{errors.title}</p>}
              </div>
              <div className="space-y-2">
                <Label htmlFor="description">Description</Label>
                <Textarea id="description" rows={4} value={data.description} onChange={(e) => setData('description', e.target.value)} />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Level</Label>
                  <Select value={data.level} onValueChange={(v) => setData('level', v)}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value="beginner">Beginner</SelectItem>
                      <SelectItem value="intermediate">Intermediate</SelectItem>
                      <SelectItem value="advanced">Advanced</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label>Language</Label>
                  <Select value={data.language} onValueChange={(v) => setData('language', v)}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value="en">English</SelectItem>
                      <SelectItem value="es">Spanish</SelectItem>
                      <SelectItem value="fr">French</SelectItem>
                      <SelectItem value="de">German</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label>Duration (minutes)</Label>
                  <Input type="number" value={data.duration_minutes} onChange={(e) => setData('duration_minutes', e.target.value)} />
                </div>
                <div className="space-y-2">
                  <Label>Price ($)</Label>
                  <Input type="number" step="0.01" value={data.price} onChange={(e) => setData('price', e.target.value)} />
                </div>
              </div>
              <div className="space-y-2">
                <Label>Thumbnail URL</Label>
                <Input value={data.thumbnail} onChange={(e) => setData('thumbnail', e.target.value)} placeholder="https://..." />
              </div>
              <div className="flex gap-4 pt-2">
                <Button type="submit" disabled={processing}>Create Course</Button>
                <Link href="/lms/courses"><Button variant="outline" type="button">Cancel</Button></Link>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
