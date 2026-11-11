<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Assignment;
use App\Models\LMS\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'total_marks' => 'required|integer|min:1',
        ]);

        $data['course_id'] = $course->id;
        Assignment::create($data);

        return back()->with('success', 'Assignment created.');
    }

    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'total_marks' => 'required|integer|min:1',
        ]);

        $assignment->update($data);

        return back()->with('success', 'Assignment updated.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Assignment deleted.');
    }
}
