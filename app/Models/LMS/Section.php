<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'lms_sections';

    protected $fillable = ['course_id', 'title', 'order'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'section_id')->orderBy('order');
    }
}
