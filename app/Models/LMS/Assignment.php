<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table = 'lms_assignments';

    protected $fillable = ['course_id', 'title', 'description', 'due_date', 'total_marks'];

    protected $casts = ['due_date' => 'date'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
