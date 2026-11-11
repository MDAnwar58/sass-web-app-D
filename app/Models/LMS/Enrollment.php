<?php

namespace App\Models\LMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $table = 'lms_enrollments';

    protected $fillable = ['user_id', 'course_id', 'paid_amount', 'status', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id');
    }
}
