<?php

namespace App\Models\LMS;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lms_lessons';

    protected $fillable = [
        'section_id', 'title', 'type', 'content', 'video_url',
        'file_path', 'duration_minutes', 'is_free_preview', 'order',
    ];

    protected $casts = ['is_free_preview' => 'boolean'];

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'lesson_id');
    }
}
