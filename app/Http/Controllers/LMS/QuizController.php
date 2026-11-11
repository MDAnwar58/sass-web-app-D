<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Quiz;
use App\Models\LMS\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'pass_mark' => 'required|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:0',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_option' => 'required|integer',
            'questions.*.marks' => 'nullable|integer|min:1',
        ]);

        $quiz = $lesson->quiz()->create([
            'title' => $data['title'],
            'pass_mark' => $data['pass_mark'],
            'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
        ]);

        foreach ($data['questions'] as $i => $q) {
            $quiz->questions()->create([
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_option' => $q['correct_option'],
                'marks' => $q['marks'] ?? 1,
                'order' => $i + 1,
            ]);
        }

        return back()->with('success', 'Quiz created.');
    }

    public function update(Request $request, Quiz $quiz)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'pass_mark' => 'required|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:0',
        ]);

        $quiz->update($data);

        return back()->with('success', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz deleted.');
    }
}
