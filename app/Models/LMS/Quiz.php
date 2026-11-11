<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'lms_quizzes';

    protected $fillable = ['lesson_id', 'title', 'pass_mark', 'time_limit_minutes'];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('order');
    }
}
