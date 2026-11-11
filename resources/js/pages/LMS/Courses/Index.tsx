import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Plus, Search, Edit, Trash2, BookOpen } from 'lucide-react';
import type { PaginatedData } from '@/types/core';
import type { Course } from '@/types/lms';

interface Props {
  courses: PaginatedData<Course>;
  filters: { search?: string };
}

export default function CoursesIndex({ courses, filters }: Props) {
  const handleSearch = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const fd = new FormData(e.currentTarget);
    router.get('/lms/courses', { search: fd.get('search') }, { preserveState: true });
  };

  const handleDelete = (id: number) => {
    if (confirm('Delete this course?')) {
      router.delete(`/lms/courses/${id}`);
    }
  };

  return (
    <AppLayout breadcrumbs={[{ title: 'Courses', href: '/lms/courses' }]}>
      <Head title="Courses" />
      <div className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-bold">Courses</h1>
            <p className="text-muted-foreground">{courses.total} total courses</p>
          </div>
          <Link href="/lms/courses/create">
            <Button><Plus size={16} className="mr-2" />Create Course</Button>
          </Link>
        </div>

        <form onSubmit={handleSearch} className="flex gap-2 max-w-sm">
          <Input name="search" placeholder="Search courses..." defaultValue={filters.search ?? ''} />
          <Button type="submit" variant="outline"><Search size={16} /></Button>
        </form>

        <div className="border rounded-lg">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Title</TableHead>
                <TableHead>Level</TableHead>
                <TableHead>Duration</TableHead>
                <TableHead>Price</TableHead>
                <TableHead>Status</TableHead>
                <TableHead className="text-right">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {courses.data.map((course) => (
                <TableRow key={course.id}>
                  <TableCell className="font-medium">{course.title}</TableCell>
                  <TableCell><Badge variant="outline">{course.level}</Badge></TableCell>
                  <TableCell>{course.duration_minutes ? `${course.duration_minutes}m` : '—'}</TableCell>
                  <TableCell>{Number(course.price) > 0 ? `$${Number(course.price).toFixed(2)}` : 'Free'}</TableCell>
                  <TableCell>
                    <Badge variant={course.status === 'published' ? 'default' : 'secondary'}>
                      {course.status.charAt(0).toUpperCase() + course.status.slice(1)}
                    </Badge>
                  </TableCell>
                  <TableCell className="text-right">
                    <div className="flex justify-end gap-2">
                      <Link href={`/lms/courses/${course.id}`}>
                        <Button variant="ghost" size="sm"><BookOpen size={14} /></Button>
                      </Link>
                      <Link href={`/lms/courses/${course.id}/edit`}>
                        <Button variant="ghost" size="sm"><Edit size={14} /></Button>
                      </Link>
                      <Button variant="ghost" size="sm" onClick={() => handleDelete(course.id)} className="text-destructive">
                        <Trash2 size={14} />
                      </Button>
                    </div>
                  </TableCell>
                </TableRow>
              ))}
              {courses.data.length === 0 && (
                <TableRow>
                  <TableCell colSpan={6} className="text-center text-muted-foreground py-12">No courses found.</TableCell>
                </TableRow>
              )}
            </TableBody>
          </Table>
        </div>
      </div>
    </AppLayout>
  );
}
