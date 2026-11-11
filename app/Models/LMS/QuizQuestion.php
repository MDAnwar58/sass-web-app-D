<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'lms_quiz_questions';

    protected $fillable = ['quiz_id', 'question', 'options', 'correct_option', 'marks', 'order'];

    protected $casts = ['options' => 'array'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
