<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Enrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['user', 'course'])
            ->when($request->search, fn($q, $s) => $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"))
                ->orWhereHas('course', fn($q) => $q->where('title', 'like', "%{$s}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('LMS/Enrollments/Index', [
            'enrollments' => $enrollments,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:lms_courses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Enrollment::create($data);

        return back()->with('success', 'Enrollment created.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Enrollment removed.');
    }
}
