<?php

namespace App\Models\LMS;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'lms_certificates';

    protected $fillable = [
        'user_id', 'course_id', 'certificate_number',
        'file_path', 'issued_at',
    ];

    protected $casts = ['issued_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
