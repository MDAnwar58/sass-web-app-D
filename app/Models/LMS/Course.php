<?php

namespace App\Models\LMS;

use App\Models\Core\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $table = 'lms_courses';

    protected $fillable = [
        'organization_id', 'instructor_id', 'title', 'slug',
        'description', 'thumbnail', 'price', 'level', 'language',
        'duration_minutes', 'status', 'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id')->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class, 'course_id', 'section_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
