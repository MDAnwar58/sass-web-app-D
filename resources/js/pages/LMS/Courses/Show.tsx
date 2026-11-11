import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Edit, ChevronLeft } from 'lucide-react';
import type { Course } from '@/types/lms';

interface Props {
  course: Course;
}

export default function ShowCourse({ course }: Props) {
  return (
    <AppLayout breadcrumbs={[
      { title: 'Courses', href: '/lms/courses' },
      { title: course.title, href: `/lms/courses/${course.id}` },
    ]}>
      <Head title={course.title} />
      <div className="space-y-6">
        <div className="flex items-start justify-between">
          <div>
            <Link href="/lms/courses" className="text-sm text-muted-foreground flex items-center gap-1 mb-2"><ChevronLeft size={14} />Back to Courses</Link>
            <h1 className="text-2xl font-bold">{course.title}</h1>
            <div className="flex items-center gap-2 mt-1">
              <Badge variant="outline">{course.level}</Badge>
              <Badge variant={course.status === 'published' ? 'default' : course.status === 'archived' ? 'secondary' : 'outline'}>
                {course.status.charAt(0).toUpperCase() + course.status.slice(1)}
              </Badge>
              {Number(course.price) > 0 ? <span className="text-muted-foreground">${Number(course.price).toFixed(2)}</span> : <span className="text-muted-foreground">Free</span>}
            </div>
          </div>
          <Link href={`/lms/courses/${course.id}/edit`}>
            <Button><Edit size={14} className="mr-2" />Edit</Button>
          </Link>
        </div>

        {course.thumbnail && (
          <img src={course.thumbnail} alt={course.title} className="w-full max-h-64 object-cover rounded-lg" />
        )}

        <Card>
          <CardHeader><CardTitle>Description</CardTitle></CardHeader>
          <CardContent>{course.description || 'No description provided.'}</CardContent>
        </Card>

        <Card>
          <CardHeader><CardTitle>Sections ({course.sections?.length ?? 0})</CardTitle></CardHeader>
          <CardContent>
            {course.sections && course.sections.length > 0 ? (
              <ul className="space-y-3">
                {course.sections.map((section, i) => (
                  <li key={section.id} className="border-b pb-3 last:border-0">
                    <div className="flex items-center gap-2">
                      <span className="flex-shrink-0 w-6 h-6 bg-primary/10 rounded-full flex items-center justify-center text-xs font-bold">{i + 1}</span>
                      <span className="font-medium">{section.title}</span>
                    </div>
                    <p className="text-sm text-muted-foreground ml-8">{section.lessons?.length ?? 0} lessons</p>
                  </li>
                ))}
              </ul>
            ) : (
              <p className="text-muted-foreground">No sections yet.</p>
            )}
          </CardContent>
        </Card>
      </div>
    </AppLayout>
  );
}
