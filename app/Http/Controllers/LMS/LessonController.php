<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Lesson;
use App\Models\LMS\Section;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function index(Section $section)
    {
        return Inertia::render('LMS/Lessons/Index', [
            'section' => $section->load('lessons'),
        ]);
    }

    public function store(Request $request, Section $section)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,text,quiz',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_free_preview' => 'boolean',
        ]);

        $data['section_id'] = $section->id;
        $data['order'] = $section->lessons()->count() + 1;

        Lesson::create($data);

        return back()->with('success', 'Lesson created.');
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,text,quiz',
            'content' => 'nullable|string',
            'video_url' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:0',
            'is_free_preview' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $lesson->update($data);

        return back()->with('success', 'Lesson updated.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return back()->with('success', 'Lesson deleted.');
    }

    public function markComplete(Lesson $lesson)
    {
        $enrollment = auth()->user()->enrollments()
            ->where('course_id', $lesson->section->course_id)
            ->first();

        if ($enrollment) {
            $enrollment->progress()->updateOrCreate(
                ['lesson_id' => $lesson->id],
                ['is_completed' => true, 'completed_at' => now()]
            );
        }

        return back();
    }
}
