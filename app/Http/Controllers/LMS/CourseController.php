<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $courses = Course::with('instructor')
            ->where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->withCount(['enrollments', 'lessons'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('LMS/Courses/Index', [
            'courses' => $courses,
            'filters' => $request->only(['search', 'status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('LMS/Courses/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'language' => 'required|string|max:10',
        ]);

        $data['organization_id'] = $request->user()->currentOrganization->id;
        $data['instructor_id'] = $request->user()->id;
        $data['slug'] = str($data['title'])->slug()->append('-' . uniqid());

        Course::create($data);

        return redirect()->route('lms.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $this->authorizeOrg($course);
        return Inertia::render('LMS/Courses/Show', [
            'course' => $course->load([
                'sections.lessons',
                'instructor',
            ])->loadCount(['enrollments']),
        ]);
    }

    public function edit(Course $course)
    {
        $this->authorizeOrg($course);
        return Inertia::render('LMS/Courses/Edit', [
            'course' => $course->load('sections.lessons'),
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $this->authorizeOrg($course);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,published,archived',
        ]);
        $course->update($data);
        return redirect()->route('lms.courses.index')
            ->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $this->authorizeOrg($course);
        $course->delete();
        return back()->with('success', 'Course deleted.');
    }

    private function authorizeOrg(Course $course): void
    {
        abort_unless(
            $course->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}
